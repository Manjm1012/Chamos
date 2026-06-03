@echo off
cd /d "%~dp0"
title CHAMOS - Setup y Servidor
color 0A

echo.
echo =============================================
echo   CHAMOS - Setup completo
echo =============================================
echo.

REM --- Instalar estructura Super Panel ---
echo [PASO 0] Instalando estructura del panel Super Admin...
php install_super_panel.php
echo.

REM --- PHP ---
echo [PASO 1] Verificando PHP...
php --version
if errorlevel 1 (
    echo.
    echo *** ERROR: PHP no encontrado en el PATH ***
    echo Descarga PHP 8.2 de https://windows.php.net/download/
    echo y asegurate de agregarlo a las variables de entorno.
    echo.
    pause
    exit /b 1
)
echo.

REM --- Composer ---
echo [PASO 2] Verificando Composer...
composer --version
if errorlevel 1 (
    echo.
    echo *** ERROR: Composer no encontrado ***
    echo Descarga desde https://getcomposer.org/download/
    echo.
    pause
    exit /b 1
)
echo.

REM --- Instalar dependencias ---
echo [PASO 3] Instalando dependencias PHP (puede tardar 2-5 min)...
echo.
call composer install --no-interaction --prefer-dist
if errorlevel 1 (
    echo.
    echo *** ERROR en composer install. Lee el error de arriba. ***
    echo.
    pause
    exit /b 1
)
echo.
echo Dependencias instaladas OK
echo.

REM --- Instalar dependencias Node y compilar assets ---
echo [PASO 3b] Instalando dependencias Node.js y compilando assets...
call npm install --silent
call npm run build
if errorlevel 1 (
    echo.
    echo *** ERROR compilando assets. Lee el error de arriba. ***
    echo.
    pause
    exit /b 1
)
echo Assets compilados OK
echo.

REM --- APP KEY ---
echo [PASO 4] Generando clave de aplicacion...
php artisan key:generate --force
if errorlevel 1 (
    echo *** ERROR generando APP_KEY ***
    pause
    exit /b 1
)
echo.

REM --- SQLite ---
echo [PASO 5] Preparando base de datos...
if not exist "database\database.sqlite" (
    echo Creando database.sqlite...
    type nul > "database\database.sqlite"
)
echo Base de datos lista.
echo.

REM --- Migraciones ---
echo [PASO 6] Corriendo migraciones...
php artisan migrate --force
if errorlevel 1 (
    echo.
    echo *** ERROR en migraciones. Lee el error de arriba. ***
    echo.
    pause
    exit /b 1
)
echo.

REM --- Seeder ---
echo [PASO 6b] Ejecutando seeder (datos iniciales)...
php artisan db:seed --force
echo.

REM --- Cache ---
echo [PASO 7] Limpiando cache...
php artisan config:clear
php artisan cache:clear
echo.

echo =============================================
echo   TODO LISTO - Arrancando servidor...
echo   Panel de usuario: http://localhost:8000/app
echo   Panel super admin: http://localhost:8000/super
echo.
echo   Credenciales super admin:
echo     Email:    super@chamos.local
echo     Password: superpassword
echo.
echo   Credenciales admin de demo:
echo     Email:    admin@fleet.local
echo     Password: password
echo.
echo   Presiona Ctrl+C para detener
echo =============================================
echo.

start "" "http://localhost:8000/app"
php artisan serve --host=127.0.0.1 --port=8000

pause
