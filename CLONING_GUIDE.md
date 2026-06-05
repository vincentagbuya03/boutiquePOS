# Cloning Guide

This guide explains how to clone the full Boutique POS system to another computer.

## Requirements

Before cloning, make sure the other computer has:

- Git
- PHP
- Composer
- MySQL or MariaDB
- Laragon, XAMPP, or another local server stack

## 1. Get the repository link

Copy the GitHub repository URL.

Example:

```bash
https://github.com/YOUR_USERNAME/YOUR_REPOSITORY.git
```

## 2. Open the target parent folder

On the other computer, open a terminal in the folder where the system should be placed.

Example:

```bash
cd C:\laragon\www
```

## 3. Clone the full system

Run:

```bash
git clone https://github.com/YOUR_USERNAME/YOUR_REPOSITORY.git Boutique-Pos
```

This will create the project folder here:

```bash
C:\laragon\www\Boutique-Pos
```

## 4. Open the project folder

After cloning:

```bash
cd Boutique-Pos
```

## 5. Install PHP dependencies

Run:

```bash
composer install
```

## 6. Create the environment file

If the project includes `.env.example`, copy it:

```bash
copy .env.example .env
```

If `.env.example` does not exist yet, copy the correct `.env` values manually.

## 7. Generate the Laravel app key

Run:

```bash
php artisan key:generate
```

## 8. Configure the database

Update the `.env` file with the correct database settings:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

## 9. Import or create the database

Use one of these options:

- import an existing SQL database backup
- run migrations if the project is ready for fresh setup

Example migration command:

```bash
php artisan migrate
```

If your project uses seeders:

```bash
php artisan db:seed
```

## 10. Start the system

If using Laragon, place the folder in `C:\laragon\www` and start Apache and MySQL from Laragon.

You can also run Laravel locally with:

```bash
php artisan serve
```

## 11. Optional front-end setup

If the project uses Node.js assets, also run:

```bash
npm install
npm run dev
```

## Common problems

If cloning works but the system does not run, check:

- Git is installed correctly
- Composer dependencies are installed
- `.env` exists
- database credentials are correct
- the database has been imported or migrated
- PHP version is compatible with the project

## Quick Summary

```bash
cd C:\laragon\www
git clone https://github.com/YOUR_USERNAME/YOUR_REPOSITORY.git Boutique-Pos
cd Boutique-Pos
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

https://github.com/vincentagbuya03/boutiquePOS
