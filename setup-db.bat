@echo off
echo Setting up SQLite database...
cd /d %~dp0

echo. 2>nul > database\database.sqlite

if exist database\database.sqlite (
    echo Database file created successfully.
    echo Running migrations...
    call php artisan migrate --force
    echo Setup complete!
) else (
    echo Failed to create database file. Please check permissions.
    pause
)
