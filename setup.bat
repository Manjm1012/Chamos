@echo off
setlocal enabledelayedexpansion
cd /d "%~dp0"

echo.
echo ============================================
echo  CHAMOS - Setup inicial
echo ============================================

echo.
echo [1/3] Instalando dependencias PHP...
call composer install --no-interaction
if errorlevel 1 (
    echo ERROR: composer install fallo. Asegurate de tener Composer instalado.
    pause
    exit /b 1
)

echo.
echo [2/3] Corriendo migraciones...
php artisan migrate --force
if errorlevel 1 (
    echo ERROR: Las migraciones fallaron.
    pause
    exit /b 1
)

echo.
echo [3/3] Instalando dependencias Node.js...
call npm install
if errorlevel 1 (
    echo ADVERTENCIA: npm install fallo. El frontend puede no funcionar.
)

echo.
echo ============================================
echo  Setup completado exitosamente!
echo  Ahora ejecuta: start.bat
echo ============================================
pause
