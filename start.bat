@echo off
cd /d "%~dp0"

echo.
echo ============================================
echo  CHAMOS - Iniciando servidor
echo  URL: http://localhost:8000
echo  Panel admin: http://localhost:8000/admin
echo  Presiona Ctrl+C para detener
echo ============================================
echo.

php artisan serve
