' microservicio_python.vbs
Dim shell, fso, pythonExe, scriptPath, logDir, logPath, fecha, hora, logName
Set shell = CreateObject("WScript.Shell")
Set fso = CreateObject("Scripting.FileSystemObject")

pythonExe = "python"

' Cambia el directorio de trabajo al de este script
shell.CurrentDirectory = fso.GetParentFolderName(WScript.ScriptFullName)

scriptPath = fso.GetAbsolutePathName("service_dynamic_price.py")
logDir = fso.GetAbsolutePathName("logs")

If Not fso.FolderExists(logDir) Then
    fso.CreateFolder(logDir)
End If

' Generar nombre de log único por sesión
fecha = Year(Now) & Right("0" & Month(Now),2) & Right("0" & Day(Now),2)
hora = Right("0" & Hour(Now),2) & Right("0" & Minute(Now),2) & Right("0" & Second(Now),2)
logName = "microservicio_python_vbs_" & fecha & "_" & hora & ".log"
logPath = logDir & "\" & logName

Do
    shell.Run "cmd /c """ & pythonExe & """ """ & scriptPath & """ >> """ & logPath & """ 2^>^&1", 0, True
    WScript.Sleep 2000
Loop