' =============================================
' Cornelia Street Bistro Payroll System
' Silent Launcher v7
' =============================================
Set oShell = CreateObject("WScript.Shell")
Set oFSO   = CreateObject("Scripting.FileSystemObject")
Dim scriptPath, drive, xamppPath, projectPath
Dim phpExe, mysqlExe, mysqlData, splashPath, pythonExe
scriptPath  = WScript.ScriptFullName
drive       = Left(scriptPath, 2)
xamppPath   = drive & "\xampp"
projectPath = drive & "\xampp\htdocs\Cornelia"
phpExe      = xamppPath & "\php\php.exe"
mysqlExe    = xamppPath & "\mysql\bin\mysqld.exe"
mysqlData   = xamppPath & "\mysql\data"
splashPath  = projectPath & "\splash.hta"

' ── Find Python ───────────────────────────────
Dim pythonPaths(5)
pythonPaths(0) = "C:\Users\" & oShell.ExpandEnvironmentStrings("%USERNAME%") & "\AppData\Local\Programs\Python\Python312\python.exe"
pythonPaths(1) = "C:\Python312\python.exe"
pythonPaths(2) = "C:\Python311\python.exe"
pythonPaths(3) = "C:\Program Files\Python312\python.exe"
pythonPaths(4) = drive & "\Python312\python.exe"
pythonExe = "python"  ' fallback to PATH
Dim j
For j = 0 To 4
    If oFSO.FileExists(pythonPaths(j)) Then
        pythonExe = pythonPaths(j)
        Exit For
    End If
Next

' ── 1. Start MySQL hidden ─────────────────────
oShell.Run """" & mysqlExe & """ --datadir=""" & mysqlData & """ --standalone", 0, False

' ── 2. Start Laravel hidden immediately ───────
WScript.Sleep 1500
oShell.Run """" & phpExe & """ """ & projectPath & "\artisan"" serve --host=127.0.0.1 --port=8000", 0, False

' ── 3. Start Fingerprint Server hidden ────────
WScript.Sleep 500
oShell.Run """" & pythonExe & """ """ & projectPath & "\fingerprint_server.py""", 0, False

' ── 4. Show splash (5.5 seconds then auto closes) ──
oShell.Run "mshta.exe """ & splashPath & """", 1, True

' ── 5. Find browser ──────────────────────────
Dim browserPath, browserFound
browserFound = False
Dim chromePaths(4)
chromePaths(0) = "C:\Program Files\Google\Chrome\Application\chrome.exe"
chromePaths(1) = "C:\Program Files (x86)\Google\Chrome\Application\chrome.exe"
chromePaths(2) = drive & "\Program Files\Google\Chrome\Application\chrome.exe"
chromePaths(3) = drive & "\Program Files (x86)\Google\Chrome\Application\chrome.exe"
Dim edgePaths(4)
edgePaths(0) = "C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"
edgePaths(1) = "C:\Program Files\Microsoft\Edge\Application\msedge.exe"
edgePaths(2) = drive & "\Program Files\Microsoft\Edge\Application\msedge.exe"
edgePaths(3) = drive & "\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"
Dim i
For i = 0 To 3
    If oFSO.FileExists(chromePaths(i)) Then
        browserPath  = chromePaths(i)
        browserFound = True
        Exit For
    End If
Next
If Not browserFound Then
    For i = 0 To 3
        If oFSO.FileExists(edgePaths(i)) Then
            browserPath  = edgePaths(i)
            browserFound = True
            Exit For
        End If
    Next
End If

' ── 6. Open login maximized ───────────────────
If browserFound Then
    oShell.Run """" & browserPath & """ --app=http://127.0.0.1:8000/login --start-maximized --no-first-run --disable-extensions", 1, False
Else
    oShell.Run "http://127.0.0.1:8000/login"
End If