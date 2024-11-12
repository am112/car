# Project CAR

This is a project to help you get started with a Laravel-based application.

### Installation

1. Git clone this repository
2. Run `composer install` to install the dependencies
3. Run `npm install` to install the dependencies
4. copy `.env.example` to `.env` and update the values
5. Run `php artisan key:generate` to generate a new application key
6. Run `php artisan migrate --seed` to create the database tables
7. Run `composer run dev` to start the development server

### To test the application

1. navigate to `/login` page
2. use `admin@mail.com` as username and `password` as password

### To generate sample data

1. Run `php artisan simulate:create-invoices` to create invoices

### TODO

1. Saparate the external API services from application services
