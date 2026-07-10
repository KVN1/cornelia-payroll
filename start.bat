@echo off
title Cornelia Street Bistro Payroll
color 0A

:: Auto-detect drive and paths
set DRIVE=%~d0
set XAMPP_PATH=%DRIVE%\xampp
set PROJECT_PATH=%DRIVE%\xampp\htdocs\Cornelia
set MYSQL_DATA=%DRIVE%\xampp\mysql\data
set PHP=%XAMPP_PATH%\php\php.exe
set URL=http://127.0.0.1:8000

echo =======================================
echo   Cornelia Street Bistro Payroll
echo   Starting system, please wait...
echo =======================================
echo.

:: Start MySQL
echo [1/3] Starting MySQL...
"%XAMPP_PATH%\mysql\bin\mysqld.exe" --datadir="%MYSQL_DATA%" --standalone >nul 2>&1 &
timeout /t 5 /nobreak >nul
echo MySQL started.
echo.

:: Start Laravel server
echo [2/3] Starting Laravel server...
cd /d "%PROJECT_PATH%"
start "Laravel Server" /min cmd /c ""%PHP%" artisan serve --host=127.0.0.1 --port=8000"
timeout /t 8 /nobreak >nul
echo Server started.
echo.

:: Open in app mode (no address bar - looks like a real app)
echo [3/3] Opening application...

:: Try Chrome first
set CHROME="C:\Program Files\Google\Chrome\Application\chrome.exe"
set CHROME_ALT="C:\Program Files (x86)\Google\Chrome\Application\chrome.exe"

:: Try Edge as fallback
set EDGE="C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"
set EDGE_ALT="C:\Program Files\Microsoft\Edge\Application\msedge.exe"

if exist %CHROME% (
    start "" %CHROME% --app=%URL% --window-size=1280,800 --window-position=100,50 --no-first-run
    goto :done
)
if exist %CHROME_ALT% (
    start "" %CHROME_ALT% --app=%URL% --window-size=1280,800 --window-position=100,50 --no-first-run
    goto :done
)
if exist %EDGE% (
    start "" %EDGE% --app=%URL% --window-size=1280,800 --window-position=100,50 --no-first-run
    goto :done
)
if exist %EDGE_ALT% (
    start "" %EDGE_ALT% --app=%URL% --window-size=1280,800 --window-position=100,50 --no-first-run
    goto :done
)

:: Fallback - open in default browser if Chrome and Edge not found
echo Chrome and Edge not found, opening in default browser...
start "" "%URL%"

:done
echo.
echo =======================================
echo   Cornelia Street Bistro is running!
echo   Close this window to STOP the server
echo =======================================
echo.
pause