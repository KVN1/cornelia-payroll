"""Quick test - run this to check if the server binds correctly"""
from http.server import HTTPServer, BaseHTTPRequestHandler
import json

class Handler(BaseHTTPRequestHandler):
    def log_message(self, format, *args): pass
    def do_GET(self):
        body = json.dumps({"status": "ok"}).encode()
        self.send_response(200)
        self.send_header("Content-Type", "application/json")
        self.send_header("Content-Length", len(body))
        self.end_headers()
        self.wfile.write(body)

print("Starting test server on port 7788...")
print("Test with: curl http://127.0.0.1:7788/")
try:
    server = HTTPServer(("0.0.0.0", 7788), Handler)
    server.serve_forever()
except Exception as e:
    print(f"Error: {e}")
