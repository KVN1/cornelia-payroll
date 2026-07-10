CORNELIA FINGERPRINT BRIDGE — SETUP GUIDE
==========================================

WHAT THIS DOES
--------------
A small Python desktop app that:
1. Reads fingerprints from your WA28 scanner via Windows Biometric Framework
2. Sends the data to your Laravel app
3. Auto-detects Time In / Break Out / Break In / Time Out
4. Works alongside your existing PIN attendance system

FILES
-----
- fingerprint_bridge.py  → Run this on the computer with the WA28 plugged in
- bridge_setup.php       → Contains Laravel routes, controller, and middleware to add

STEP 1 — INSTALL PYTHON REQUIREMENTS
-------------------------------------
Open CMD and run:

    pip install requests pywin32

STEP 2 — ADD LARAVEL API ROUTES
---------------------------------
1. Open routes/api.php and add:

    use App\Http\Controllers\BridgeController;

    Route::middleware('bridge.token')->prefix('bridge')->group(function () {
        Route::get('employees',  [BridgeController::class, 'employees']);
        Route::post('enroll',    [BridgeController::class, 'enroll']);
        Route::post('clock',     [BridgeController::class, 'clock']);
    });

2. Create app/Http/Controllers/BridgeController.php
   (copy from bridge_setup.php — see "BridgeController" section)

3. Create app/Http/Middleware/BridgeTokenMiddleware.php
   (copy from bridge_setup.php — see "BridgeTokenMiddleware" section)

4. Register middleware in bootstrap/app.php:
   (copy from bridge_setup.php — see last section)

5. Run: php artisan route:clear

STEP 3 — RUN THE BRIDGE APP
-----------------------------
1. Make sure php artisan serve is running (Laravel)
2. Plug in the WA28 scanner
3. Double-click fingerprint_bridge.py OR run:

    python fingerprint_bridge.py

STEP 4 — ENROLL EMPLOYEES
---------------------------
1. Click the "Enroll Employee" tab
2. Select the employee from the dropdown
3. Have the employee place their finger on the scanner
4. Click "Enroll Fingerprint"
5. Done! Their fingerprint is now linked to their account.

STEP 5 — DAILY ATTENDANCE
---------------------------
1. Keep the bridge app open on the attendance computer
2. Employees just place their finger on the WA28
3. Click "Scan Fingerprint" — auto-detects Time In / Break / Time Out
4. Attendance is logged in Laravel automatically

TROUBLESHOOTING
---------------
- "Scanner not detected" → Make sure WA28 is plugged in BEFORE opening the app
- "Cannot connect to Laravel" → Make sure php artisan serve is running
- "No matching fingerprint" → Employee needs to be enrolled first (Step 4)
- "Access denied" → Run fingerprint_bridge.py as Administrator

CONFIG
------
Edit the top of fingerprint_bridge.py to change:
    LARAVEL_URL = "http://127.0.0.1:8000"   ← your app URL
    API_TOKEN   = "cornelia-bridge-2026"     ← keep this secret
