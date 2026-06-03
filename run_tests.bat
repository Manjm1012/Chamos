@echo off
setlocal enabledelayedexpansion
cd /d "c:\Users\Manuel Maldonado\Desktop\Proyectos\Chamos-main"

echo ============================================
echo Step 1: Running composer install
echo ============================================
call composer install 2>&1

echo.
echo ============================================
echo Step 2: Checking .env file
echo ============================================
if exist ".env" (
    echo .env file exists
    type .env | findstr /R "^APP_KEY=" > nul
    if errorlevel 1 (
        echo APP_KEY not found in .env
    ) else (
        echo APP_KEY exists in .env
    )
) else (
    echo .env file not found
)

echo.
echo ============================================
echo Step 3: Running php artisan key:generate
echo ============================================
php artisan key:generate 2>&1

echo.
echo ============================================
echo Step 4: Checking database configuration
echo ============================================
echo Current .env DB_CONNECTION setting:
findstr /R "^DB_CONNECTION=" .env 2>&1

echo.
echo ============================================
echo Step 5: Checking database file
echo ============================================
if exist "database\database.sqlite" (
    echo database\database.sqlite exists
) else (
    echo database\database.sqlite does not exist - creating it
    type nul > database\database.sqlite
    echo Created database\database.sqlite
)

echo.
echo ============================================
echo Step 6: Running migrations for testing
echo ============================================
php artisan migrate --env=testing 2>&1

echo.
echo ============================================
echo Step 7: Running tests
echo ============================================
php artisan test --ansi 2>&1

echo.
echo ============================================
echo Test run completed
echo ============================================
pause
