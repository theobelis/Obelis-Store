@echo off
REM === microservicio_python.bat ===
REM Arranca y reinicia automáticamente el microservicio Flask de precios dinámicos
REM Guarda logs en microservicio\logs\microservicio_python_<fecha-hora>.log y muestra fecha/hora de cada reinicio

cd /d %~dp0

REM --- Limpieza de procesos colgados de python relacionados con service_dynamic_price.py ---
for /f "tokens=2" %%a in ('wmic process where "name='python.exe' and commandline like '%%service_dynamic_price.py%%'" get ProcessId /value 2^>NUL ^| find "ProcessId="') do (
    echo [LIMPIEZA] Cerrando proceso python.exe colgado con PID %%a relacionado con service_dynamic_price.py...
    taskkill /PID %%a /F
)

REM --- Generar nombre de log único por sesión ---
for /f "tokens=2-4 delims=/ " %%a in ('date /t') do set FECHA=%%a-%%b-%%c
for /f "tokens=1-2 delims=: " %%a in ('time /t') do set HORA=%%a%%b
set LOGFILE=logs\microservicio_python_%%FECHA%%_%%HORA%%.log

:loop
REM Marca el reinicio en el log con fecha y hora
for /f "tokens=2-4 delims=/ " %%a in ('date /t') do set FECHA=%%a-%%b-%%c
for /f "tokens=1-2 delims=: " %%a in ('time /t') do set HORA=%%a:%%b

echo [INICIO] %FECHA% %HORA% - Iniciando microservicio Flask... >> %LOGFILE%

REM Ejecuta el microservicio y redirige toda la salida al log
python service_dynamic_price.py >> %LOGFILE% 2>>&1

REM Si el microservicio termina, espera 2 segundos y reinicia
ping -n 3 127.0.0.1 > nul
goto loop
