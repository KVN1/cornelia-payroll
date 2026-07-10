Set WshShell = CreateObject("WScript.Shell")
WshShell.Run "cmd /c cd /d D:\xampp\htdocs\Cornelia && python fingerprint_server.py", 0, False