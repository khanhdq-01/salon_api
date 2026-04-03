@echo off
setlocal EnableExtensions
title Salon MVP - Task Scheduler

cd /d "%~dp0.."

echo [%date% %time%] Laravel scheduler loop (every 60s)
echo Project: %CD%
echo Handles: notifications:dispatch-scheduled, subscriptions:send-expiry-reminders
echo Press Ctrl+C to stop.
echo.

:loop
php artisan schedule:run --no-interaction
timeout /t 60 /nobreak >nul
goto loop
