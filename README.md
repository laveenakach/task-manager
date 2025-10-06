# Laravel Task Manager

Simple Task Management web app built with Laravel.

## Features
- Create / Read / Update / Delete tasks.
- Mark tasks completed / incomplete.
- Reorder tasks via drag & drop.
- Filter tasks: All / Completed / Incomplete.

## Requirements
- PHP 8.2.12
- Laravel 11.46.1
- Composer
- MySQL

## Quick start
```bash
git clone https://github.com/laveenakach/task-manager.git
cd task-manager
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
