@echo off
setlocal EnableExtensions

set "ROOT=%~dp0.."
set "SCRIPTS=%~dp0"

echo Opening Queue Worker and Scheduler in separate windows...
echo Close those windows to stop background jobs.
echo.

start "Salon MVP Queue Worker" cmd /k ""%SCRIPTS%queue-worker.bat""
start "Salon MVP Scheduler" cmd /k ""%SCRIPTS%schedule-run.bat""

echo Done. Keep both windows running while developing.
