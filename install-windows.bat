@echo off
echo Menginstall dependency Laravel...
composer install

echo Membuat file database SQLite jika belum ada...
if not exist database\database.sqlite type nul > database\database.sqlite

echo Generate key aplikasi...
php artisan key:generate

echo Migrasi dan isi data demo...
php artisan migrate:fresh --seed

echo.
echo Selesai. Jalankan run-laravel.bat dan run-reverb.bat di dua terminal berbeda.
pause
