# Project Name

This project is designed to handle authors, posts, and comments with a large dataset for testing purposes. It includes functionality for database seeding, CRUD operations for posts and comments, and various tests to ensure the integrity of the data.

## Prerequisites

Before you begin, ensure that you have the following installed:

-   [PHP](https://www.php.net/downloads) (Version 8.0 or higher)
-   [Composer](https://getcomposer.org/) (PHP dependency manager)
-   [Laravel](https://laravel.com/docs) (Web application framework)
-   [MySQL](https://www.mysql.com/) or any other database supported by Laravel
-   [Node.js](https://nodejs.org/) and [npm](https://www.npmjs.com/) for frontend assets (if applicable)

## Installation

Follow these steps to install and set up the project locally:

### Clone the Repository

```bash
git clone https://github.com/feliste/blog-api.git
cd project-name
```
### Install Dependencies
```bash
composer install
cp .env.example .env
composer require darkaonline/l5-swagger
```

### Configure Environment Variables
```bash
cp .env.example .env
```
- Update the .env file with your database credentials and other environment-specific configurations.
For example

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

### Generate Application Key
```bash
php artisan key:generate
```

###  Run Migrations and Seed Database
```bash
php artisan migrate
php artisan db:seed
```

### Run the Project
```bash
php artisan serve
```

### Run all Tests
```bash
php artisan test
```

**API Documentation with Swagger**: 
  - Publish Configuration Files `php artisan vendor:publish --provider="L5Swagger\L5SwaggerServiceProvider"`.
  - Access the Swagger UI (`http://localhost:8000/api/documentation`).
  - To generate the Swagger documentation use `php artisan l5-swagger:generate`.
