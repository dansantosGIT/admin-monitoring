@echo off
REM Admin Monitoring System - Quick Setup Batch Script
REM This script sets up the Laravel project for development on Windows with Laragon

echo.
echo ========================================
echo Admin Monitoring System - Quick Setup
echo ========================================
echo.

REM Check if we're in the right directory
if not exist "composer.json" (
    echo Error: composer.json not found!
    echo Please run this script from the project root directory.
    pause
    exit /b 1
)

REM Define paths
set PHP_PATH=c:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe
set COMPOSER_PATH=c:\laragon\bin\composer\composer.phar
set NPM_PATH=c:\laragon\bin\nodejs\node-v22\npm.cmd

echo Checking if .env file exists...
if not exist ".env" (
    echo Creating .env from .env.example...
    copy .env.example .env
    echo .env created successfully!
) else (
    echo .env already exists, skipping...
)

echo.
echo Installing PHP dependencies...
%PHP_PATH% %COMPOSER_PATH% install --no-interaction
if errorlevel 1 (
    echo Error during composer install!
    pause
    exit /b 1
)

echo.
echo Generating application key...
%PHP_PATH% artisan key:generate
if errorlevel 1 (
    echo Error generating key!
    pause
    exit /b 1
)

echo.
echo Running database migrations...
%PHP_PATH% artisan migrate --force
if errorlevel 1 (
    echo Error running migrations! Make sure database is configured correctly.
)

echo.
echo Installing JavaScript dependencies...
%NPM_PATH% install
if errorlevel 1 (
    echo Error during npm install!
    pause
    exit /b 1
)

echo.
echo Building frontend assets...
%NPM_PATH% run build
if errorlevel 1 (
    echo Error building assets!
    pause
    exit /b 1
)

echo.
echo ========================================
echo ✓ Setup Complete!
echo ========================================
echo.
echo Next steps:
echo 1. Start Laravel server: php artisan serve
echo 2. In another terminal: npm run dev
echo 3. Visit http://localhost:8000 in your browser
echo.
echo Useful commands:
echo   php artisan migrate       - Run database migrations
echo   php artisan db:seed ReportSeeder - Seed sample reports
echo   php artisan tinker        - Interactive shell
echo   php artisan test          - Run tests
echo   php artisan pint          - Fix code style
echo.
pause
