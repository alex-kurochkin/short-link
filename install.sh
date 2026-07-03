#!/bin/bash
set -e

# Установка зависимостей для Laravel 12
# Однако, неизвестно какая ОС используется
#sudo apt update
#sudo apt install -y php8.4 php8.4-cli php8.4-fpm php8.4-common php8.4-sqlite3 php8.4-curl php8.4-mbstring php8.4-xml php8.4-zip php8.4-bcmath php8.4-gd curl unzip git
# Так же потребуется rsync

# Установка Composer
#curl -sS https://getcomposer.org/installer | php
#sudo mv composer.phar /usr/local/bin/composer
#sudo chmod +x /usr/local/bin/composer

echo "=== 1. Создание проекта Laravel 12 ==="

composer create-project laravel/laravel _temp_laravel "12.*" --quiet --no-interaction

echo "Laravel 12 создан"

echo ""
echo "=== 2. Установка Filament v3 ==="

cd _temp_laravel
composer require filament/filament:"^3.0" --quiet --no-interaction
php artisan filament:install --panels --no-interaction --quiet
cd ..

echo "Filament v3 установлен"

echo ""
echo "=== 3. Копирование кастомных файлов проекта ==="

# Копируем кастомные файлы поверх Laravel+Filament
if [ -d "app" ]; then
    cp -r app/* _temp_laravel/app/ 2>/dev/null || true
    echo "app/ скопирован"
fi

if [ -d "config" ]; then
    cp -r config/* _temp_laravel/config/ 2>/dev/null || true
    echo "config/ скопирован"
fi

if [ -d "database" ]; then
    cp -r database/* _temp_laravel/database/ 2>/dev/null || true
    echo "database/ скопирован"
fi

if [ -d "resources" ]; then
    cp -r resources/* _temp_laravel/resources/ 2>/dev/null || true
    echo "resources/ скопирован"
fi

if [ -d "routes" ]; then
    cp -r routes/* _temp_laravel/routes/ 2>/dev/null || true
    echo "routes/ скопирован"
fi

if [ -d "tests" ]; then
    cp -r tests/* _temp_laravel/tests/ 2>/dev/null || true
    echo "tests/ скопирован"
fi

if [ -d "bootstrap" ]; then
    cp -r bootstrap/* _temp_laravel/bootstrap/ 2>/dev/null || true
    echo "bootstrap/ скопирован"
fi

echo ""
echo "=== 4. Перемещение проекта в текущую директорию ==="

rsync -a _temp_laravel/ .
rm -rf _temp_laravel

echo "Проект перемещён"

echo ""
echo "=== 5. Настройка окружения ==="

if [ ! -f .env ]; then
    cp .env.example .env
    echo ".env создан из .env.example"
else
    echo ".env уже существует"
fi

php artisan key:generate --quiet --no-interaction
echo "APP_KEY сгенерирован"

echo ""
echo "=== 6. Создание базы данных SQLite ==="

touch database/database.sqlite
echo "database/database.sqlite создан"

echo ""
echo "=== 7. Установка Pest ==="

# --with-all-dependencies обязателен, чтобы Composer мог заменить PHPUnit на Pest
composer require pestphp/pest pestphp/pest-plugin-laravel --dev --with-all-dependencies --no-interaction

mkdir -p tests/Feature tests/Unit

if [ ! -f tests/Pest.php ]; then
    cat > tests/Pest.php << 'PEST_EOF'
<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature');
uses(TestCase::class)->in('Unit');

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});
PEST_EOF
    echo "tests/Pest.php создан"
else
    echo "tests/Pest.php уже существует"
fi

rm -f tests/Feature/ExampleTest.php
rm -f tests/Unit/ExampleTest.php
touch tests/Unit/.gitkeep

echo "Pest установлен и настроен"

echo ""
echo "=== 8. Применение миграций ==="

php artisan migrate --quiet --no-interaction
echo "Миграции выполнены"

echo ""
echo "=== 9. Очистка кэша ==="

php artisan config:clear --quiet
php artisan cache:clear --quiet
php artisan view:clear --quiet
php artisan route:clear --quiet
echo "Кэш очищен"

echo ""
echo "=== 10. Запуск тестов ==="

php artisan test

echo ""
echo "=== 11. Создание администратора ==="

php artisan make:filament-user \
  --name="Admin" \
  --email="admin@example.com" \
  --password="secret123" \
  --no-interaction

echo "Администратор создан (admin@example.com / secret123)"
echo "  !!!  Не забудьте сменить пароль после первого входа!"

echo ""
echo "=========================================="
echo "Установка завершена!"
echo "=========================================="
echo ""
echo "Запустите сервер:"
echo "php artisan serve"
