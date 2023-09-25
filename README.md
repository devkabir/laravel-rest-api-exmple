# Task Flow - Simple Task Management Api
A simple task management API implementation with Laravel

## Installation
1. Clone this repository
1. Create a new database
1. Run `cp .env.example .env`
1. Update env
1. Run
```bash
composer install
php artisan migrate
php artisan key:generate
php artisan serve
```
1. Open another terminal to process email notifications `php artisan queue:work`
