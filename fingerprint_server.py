"""
Cornelia Fingerprint Server — ZKTeco Live10R
=============================================
Usage:
    python fingerprint_server.py

Requirements:
    pip install pyzkfp requests
    Install ZKFinger SDK from ZKTeco website first.
"""

import time
import json
import base64
import struct
import threading
from http.server import HTTPServer, BaseHTTPRequestHandler
import requests

try:
    from pyzkfp import ZKFP2
    ZK_AVAILABLE = True
except ImportError:
    ZK_AVAILABLE = False

# ── Config ───────────────────────────────────────────────────
PORT        = 7788
LARAVEL_URL = "http://127.0.0.1:8000"
API_TOKEN   = "cornelia-bridge-2026"

# ── Scanner state ────────────────────────────────────────────
zkfp2  = None
_lock  = threading.Lock()
_fp_db = {}  # { employee_id: { name, template } }

# ── Scanner init ─────────────────────────────────────────────
def init_scanner():
    global zkfp2
    zkfp2 = ZKFP2()
    zkfp2.Init()
    count = zkfp2.GetDeviceCount()
    if count == 0:
        raise Exception("No scanner found. Make sure Live10R is plugged in via USB.")
    zkfp2.OpenDevice(0)
    print(f"  Scanner    : Live10R connected ({count} device found)")

# ── Fingerprint DB ───────────────────────────────────────────
def load_fp_db():
    global _fp_db
    try:
        r = requests.get(
            f"{LARAVEL_URL}/api/bridge/templates",
            headers={"X-Bridge-Token": API_TOKEN}, timeout=5
        )
        data      = r.json()
        templates = data.get("templates", [])
        _fp_db    = {}

        if not templates:
            print("  Templates  : No enrolled fingerprints yet")
            return

        for t in templates:
            emp_id   = t["employee_id"]
            hex_data = t["template"]
            name     = t["name"]
            if hex_data:
                try:
                    template_bytes = bytes.fromhex(hex_data)
                    _fp_db[emp_id] = {"name": name, "template": template_bytes}
                except Exception:
                    pass

        if zkfp2 and _fp_db:
            zkfp2.DBClear()
            for emp_id, d in _fp_db.items():
                zkfp2.DBAdd(int(emp_id), d["template"])
            print(f"  Templates  : {len(_fp_db)} fingerprints loaded")
            # Rebuild dict with int keys to match DBIdentify return type
            _fp_db = {int(k): v for k, v in _fp_db.items()}
        else:
            print("  Templates  : Scanner not ready")

    except Exception as e:
        print(f"  Templates  : Could not load — {e}")

# ── Helpers ──────────────────────────────────────────────────
def capture_once(timeout=15):
    """Wait for a fingerprint. Returns (tmp_bytes, img_bytes) or raises."""
    zkfp2.Light("green")
    start = time.time()
    while time.time() - start < timeout:
        capture = zkfp2.AcquireFingerprint()
        if capture:
            tmp, img = capture
            if tmp and len(tmp) >= 100:
                return tmp, img
            else:
                zkfp2.Light("red")
                time.sleep(0.2)
                zkfp2.Light("green")
                raise Exception("Poor scan quality — press finger firmly and try again.")
        time.sleep(0.05)
    raise Exception("Timed out — no finger detected.")

def image_to_b64(img, fallback_w=256, fallback_h=360):
    """Convert raw grayscale image bytes to base64 BMP string."""
    try:
        raw   = img if isinstance(img, (bytes, bytearray)) else bytes(img)
        total = len(raw)
        w, h  = fallback_w, fallback_h
        for dw, dh in [(256,360),(288,448),(300,400),(256,256),(288,360)]:
            if dw * dh == total:
                w, h = dw, dh
                break

        # Build grayscale palette
        pal = b"".join(bytes([i, i, i, 0]) for i in range(256))

        # BMP rows are bottom-up and padded to 4 bytes
        row_size = (w + 3) & ~3
        pixel_data = b""
        for y in range(h - 1, -1, -1):
            row = raw[y * w:(y + 1) * w]
            pixel_data += row.ljust(row_size, b"\x00")

        pixel_offset = 54 + 1024
        file_size    = pixel_offset + len(pixel_data)
        header = struct.pack("<2sIHHI", b"BM", file_size, 0, 0, pixel_offset)
        dib    = struct.pack("<IiiHHIIiiII", 40, w, h, 1, 8, 0,
                             len(pixel_data), 2835, 2835, 256, 256)
        bmp = header + dib + pal + pixel_data
        return base64.b64encode(bmp).decode(), w, h
    except Exception as e:
        print(f"  Image error: {e}")
        return None, fallback_w, fallback_h

def identify_local(tmp):
    """Match template against in-memory DB using ZKFinger DBIdentify."""
    if not _fp_db:
        raise Exception("No fingerprints enrolled yet. Enroll employees first.")
    fid, score = zkfp2.DBIdentify(tmp)
    print(f"  DBIdentify : fid={fid} (type={type(fid).__name__}), score={score}")
    print(f"  DB keys    : {list(_fp_db.keys())}")
    if fid == -1 or score < 30:
        raise Exception("Fingerprint not recognized. Please enroll first.")
    # Try int and original type
    emp = _fp_db.get(int(fid)) or _fp_db.get(fid)
    if not emp:
        raise Exception(f"Match found (id={fid}, score={score}) but employee not in DB keys {list(_fp_db.keys())}.")
    return int(fid), emp["name"], score

# ── HTTP Handler ─────────────────────────────────────────────
class FingerprintHandler(BaseHTTPRequestHandler):

    def log_message(self, format, *args):
        print(f"  [{time.strftime('%H:%M:%S')}] {self.command} {self.path}")

    def send_json(self, data, status=200):
        body     = json.dumps(data).encode()
        response = (
            f"HTTP/1.1 {status} OK\r\n"
            f"Content-Type: application/json\r\n"
            f"Content-Length: {len(body)}\r\n"
            f"Access-Control-Allow-Origin: *\r\n"
            f"Access-Control-Allow-Methods: GET, POST, OPTIONS\r\n"
            f"Access-Control-Allow-Headers: Content-Type, X-Requested-With\r\n"
            f"Connection: close\r\n"
            f"\r\n"
        ).encode() + body
        self.wfile.write(response)
        self.wfile.flush()

    def do_OPTIONS(self):
        response = (
            "HTTP/1.1 204 No Content\r\n"
            "Access-Control-Allow-Origin: *\r\n"
            "Access-Control-Allow-Methods: GET, POST, OPTIONS\r\n"
            "Access-Control-Allow-Headers: Content-Type, X-Requested-With\r\n"
            "Access-Control-Max-Age: 86400\r\n"
            "Content-Length: 0\r\n"
            "Connection: close\r\n"
            "\r\n"
        ).encode()
        self.wfile.write(response)
        self.wfile.flush()

    def do_GET(self):
        if self.path == "/status":
            if not ZK_AVAILABLE or zkfp2 is None:
                self.send_json({"status": "error", "message": "Scanner not available."})
            else:
                self.send_json({"status": "ready", "message": "Live10R connected and ready"})

        elif self.path == "/employees":
            try:
                r = requests.get(
                    f"{LARAVEL_URL}/api/bridge/employees",
                    headers={"X-Bridge-Token": API_TOKEN}, timeout=5
                )
                self.send_json(r.json())
            except Exception as e:
                self.send_json({"error": str(e)}, 500)

        else:
            self.send_json({"error": "Not found"}, 404)

    def do_POST(self):
        length = int(self.headers.get("Content-Length", 0))
        body   = json.loads(self.rfile.read(length)) if length else {}

        # ── /scan — capture one fingerprint for enrollment ────
        if self.path == "/scan":
            if not _lock.acquire(blocking=False):
                self.send_json({"success": False, "message": "Scanner is busy."})
                return
            try:
                print(f"  [{time.strftime('%H:%M:%S')}] Waiting for finger (scan)...")
                tmp, img = capture_once(timeout=15)
                finger_data = tmp.hex() if isinstance(tmp, (bytes, bytearray)) else bytes(tmp).hex()
                img_b64, w, h = image_to_b64(img)
                print(f"  [{time.strftime('%H:%M:%S')}] Scan captured ({len(tmp)} bytes)")
                self.send_json({
                    "success":     True,
                    "finger_data": finger_data,
                    "image_b64":   img_b64,
                    "width":       w,
                    "height":      h,
                })
            except Exception as e:
                print(f"  [{time.strftime('%H:%M:%S')}] Scan failed: {e}")
                self.send_json({"success": False, "message": str(e)})
            finally:
                _lock.release()

        # ── /scan-live — scan for progressive enrollment ──────
        elif self.path == "/scan-live":
            if not _lock.acquire(blocking=False):
                self.send_json({"success": False, "message": "busy"})
                return
            try:
                tmp, img = capture_once(timeout=15)
                # Convert template to hex safely
                if isinstance(tmp, (bytes, bytearray)):
                    finger_data = tmp.hex() if isinstance(tmp, (bytes, bytearray)) else bytes(tmp).hex()
                else:
                    finger_data = bytes(tmp).hex()
                img_b64, w, h = image_to_b64(img)

                # Quality estimate
                raw     = bytes(img) if not isinstance(img, bytes) else img
                nonzero = sum(1 for b in raw if b > 30)
                quality = min(100, int((nonzero / len(raw)) * 100 * 2.5))

                print(f"  [{time.strftime('%H:%M:%S')}] Live scan: quality={quality}%")
                self.send_json({
                    "success":     True,
                    "finger_data": finger_data,
                    "image_b64":   img_b64,
                    "quality":     quality,
                    "width":       w,
                    "height":      h,
                })
            except Exception as e:
                self.send_json({"success": False, "message": str(e)})
            finally:
                _lock.release()

        # ── /merge — merge two templates ──────────────────────
        elif self.path == "/merge":
            try:
                t1_hex = body.get("template1")
                t2_hex = body.get("template2")
                if not t1_hex or not t2_hex:
                    raise Exception("Missing template1 or template2")
                t1 = bytes.fromhex(t1_hex)
                t2 = bytes.fromhex(t2_hex)
                merged = zkfp2.DBMerge(t1, t2)
                if merged is None or len(merged) < 100:
                    self.send_json({
                        "success": False,
                        "message": "Scans didn't match — use the same finger for both scans."
                    })
                    return
                merged_hex = merged.hex() if isinstance(merged, (bytes, bytearray)) else bytes(merged).hex()
                print(f"  [{time.strftime('%H:%M:%S')}] Templates merged ({len(merged)} bytes)")
                self.send_json({"success": True, "merged_template": merged_hex})
            except Exception as e:
                self.send_json({"success": False, "message": str(e)})

        # ── /enroll — save fingerprint to employee in Laravel ─
        elif self.path == "/enroll":
            try:
                finger_data  = body.get("finger_data")
                employee_id  = body.get("employee_id")
                biometric_id = body.get("biometric_id", "")
                if not finger_data or not employee_id:
                    raise Exception("Missing finger_data or employee_id")
                r = requests.post(
                    f"{LARAVEL_URL}/api/bridge/enroll",
                    headers={"X-Bridge-Token": API_TOKEN, "Content-Type": "application/json"},
                    json={"employee_id": employee_id, "biometric_id": biometric_id,
                          "finger_data": finger_data},
                    timeout=5
                )
                data = r.json()
                print(f"  [{time.strftime('%H:%M:%S')}] Enrolled: {data.get('employee','?')}")
                load_fp_db()  # Reload so new enroll is immediately matchable
                self.send_json(data)
            except Exception as e:
                self.send_json({"success": False, "message": str(e)})

        # ── /watch — blocking scan for attendance ─────────────
        elif self.path == "/watch":
            if not _lock.acquire(blocking=False):
                self.send_json({"success": False, "message": "busy"})
                return
            try:
                action = body.get("action", None)
                print(f"  [{time.strftime('%H:%M:%S')}] Watching for finger (action={action})...")
                zkfp2.Light("green")

                tmp, img = None, None
                while True:
                    capture = zkfp2.AcquireFingerprint()
                    if capture:
                        tmp, img = capture
                        if tmp and len(tmp) >= 100:
                            break
                        tmp = None
                    time.sleep(0.05)

                print(f"  [{time.strftime('%H:%M:%S')}] Finger detected! Identifying...")

                try:
                    emp_id, emp_name, score = identify_local(tmp)
                    print(f"  [{time.strftime('%H:%M:%S')}] Matched: {emp_name} (score={score})")
                except Exception as ex:
                    print(f"  [{time.strftime('%H:%M:%S')}] Not matched: {ex}")
                    self.send_json({"success": False, "message": str(ex)})
                    return

                r = requests.post(
                    f"{LARAVEL_URL}/api/bridge/clock-by-id",
                    headers={"X-Bridge-Token": API_TOKEN, "Content-Type": "application/json"},
                    json={"employee_id": emp_id, "action": action},
                    timeout=5
                )
                data = r.json()
                if data.get("success"):
                    print(f"  [{time.strftime('%H:%M:%S')}] {data.get('action','?')} — {data.get('employee','?')}")
                self.send_json(data)

            except Exception as e:
                print(f"  [{time.strftime('%H:%M:%S')}] Watch error: {e}")
                self.send_json({"success": False, "message": str(e)})
            finally:
                _lock.release()

        # ── /clock — identify and log (legacy) ────────────────
        elif self.path == "/clock":
            if not _lock.acquire(blocking=False):
                self.send_json({"success": False, "message": "Scanner is busy."})
                return
            try:
                print(f"  [{time.strftime('%H:%M:%S')}] Identifying finger...")
                tmp, img    = capture_once(timeout=15)
                emp_id, emp_name, score = identify_local(tmp)
                print(f"  [{time.strftime('%H:%M:%S')}] Matched: {emp_name} (score={score})")
                r = requests.post(
                    f"{LARAVEL_URL}/api/bridge/clock-by-id",
                    headers={"X-Bridge-Token": API_TOKEN, "Content-Type": "application/json"},
                    json={"employee_id": emp_id, "action": None},
                    timeout=5
                )
                data = r.json()
                if data.get("success"):
                    print(f"  [{time.strftime('%H:%M:%S')}] {data.get('action','?')} — {data.get('employee','?')}")
                self.send_json(data)
            except Exception as e:
                print(f"  [{time.strftime('%H:%M:%S')}] Clock failed: {e}")
                self.send_json({"success": False, "message": str(e)})
            finally:
                _lock.release()

        else:
            self.send_json({"error": "Not found"}, 404)


# ── Main ─────────────────────────────────────────────────────
if __name__ == "__main__":
    try:
        print("=" * 56)
        print("  CORNELIA FINGERPRINT SERVER — ZKTeco Live10R")
        print("=" * 56)
        print(f"  Port       : {PORT}")
        print(f"  Laravel    : {LARAVEL_URL}")
        print()

        if not ZK_AVAILABLE:
            print("  ERROR: pyzkfp not installed. Run: pip install pyzkfp")
            input("  Press Enter to exit...")
            exit(1)

        try:
            init_scanner()
        except Exception as e:
            print(f"  Scanner    : ERROR — {e}")
            input("  Press Enter to exit...")
            exit(1)

        load_fp_db()

        print()
        print("  Listening on http://127.0.0.1:7788")
        print("  Keep this window open while using the app.")
        print("  Press Ctrl+C to stop.")
        print("=" * 56)
        print()

        server = HTTPServer(("127.0.0.1", PORT), FingerprintHandler)
        try:
            server.serve_forever()
        except KeyboardInterrupt:
            print("\n  Stopping server...")
            if zkfp2:
                try:
                    zkfp2.Terminate()
                except Exception:
                    pass
            print("  Server stopped.")

    except Exception as fatal:
        import traceback
        print()
        print("  FATAL ERROR:")
        traceback.print_exc()
        print()
        input("  Press Enter to exit...")