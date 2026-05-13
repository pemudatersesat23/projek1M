@echo off
cd /d "%~dp0static-dist"
echo Membuka static server di http://localhost:5173
py -m http.server 5173
pause
