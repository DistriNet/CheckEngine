#! /bin/sh

php artisan config:cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan serve --host=0.0.0.0
