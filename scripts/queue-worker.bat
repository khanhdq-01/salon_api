@echo off
setlocal EnableExtensions
title Salon MVP - Queue Worker (Redis)

cd /d "%~dp0.."

echo [%date% %time%] Starting queue worker...
echo Project: %CD%
echo Redis must be running. Press Ctrl+C to stop.
echo.

:loop
php artisan queue:work redis --sleep=3 --tries=3 --timeout=90 --max-time=3600
set EXIT_CODE=%ERRORLEVEL%

if %EXIT_CODE% EQU 0 (
    echo [%date% %time%] Worker exited normally. Restarting in 5s...
) else (
    echo [%date% %time%] Worker crashed (exit %EXIT_CODE%). Restarting in 5s...
)

timeout /t 5 /nobreak >nul
goto loop
