#!/bin/bash

# Установка зависимостей для Laravel 12
#sudo apt update
#sudo apt install -y php8.4 php8.4-cli php8.4-fpm php8.4-common php8.4-sqlite3 php8.4-curl php8.4-mbstring php8.4-xml php8.4-zip php8.4-bcmath php8.4-gd curl unzip git

# Установка Composer
#curl -sS https://getcomposer.org/installer | php
#sudo mv composer.phar /usr/local/bin/composer
#sudo chmod +x /usr/local/bin/composer

# Создание проекта Laravel 12
composer create-project laravel/laravel short-link "12.*"
cd short-link

# Установка Filament v3
composer require filament/filament:"^3.0"

# Публикация ресурсов Filament
# php artisan filament:install --panels
php artisan filament:install --panels --no-interaction

# Создание модели Link
php artisan make:model Link -m

# Создание модели Click
php artisan make:model Click -m

composer require pestphp/pest pestphp/pest-plugin-laravel --dev --with-all-dependencies

php artisan pest:install

rm tests/Feature/ExampleTest.php
rm tests/Unit/ExampleTest.php

cp .env.example .env

php artisan view:clear
php artisan config:clear
php artisan cache:clear
php artisan route:clear
composer dump-autoload

php artisan migrate

php artisan test

# php artisan make:filament-user

echo "Установка завершена!"
echo "Создайте администратора: php artisan make:filament-user"
