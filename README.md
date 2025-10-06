# Task Manager (Laravel 11)

A simple task management web application with CRUD, filters, and drag-and-drop reordering.

## Features
- Create, Edit, Delete tasks
- Mark Complete / Incomplete
- Reorder tasks
- Filter tasks (All / Completed / Incomplete)

## Requirements
- PHP 8.2.12
- Laravel 11.46.1
- Composer
- MySQL

## Installation

Follow these steps to get the project running locally:

### Clone the repository

Make sure to clone from the **main branch**:

```bash
git clone -b main https://github.com/laveenakach/task-manager.git

### navigate to project directory

cd task-manager

### run commands

composer install

cp .env.example .env

php artisan key:generate

### Configure database

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=task-manager
DB_USERNAME=root
DB_PASSWORD=

### run migrations

php artisan migrate

### seed data
php artisan db:seed --class=TaskSeeder

### start local deployment server

php artisan serve

### By default, the app runs on:

http://localhost:8000


