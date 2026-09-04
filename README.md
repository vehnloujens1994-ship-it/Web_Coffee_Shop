# Dizon Coffee Roasters

A simple coffee shop ordering website built with PHP, MySQL (PDO), HTML/CSS/JS —
built to run on XAMPP.

## Setup (XAMPP)

1. Copy/clone this folder into `htdocs` so it's reachable at `http://localhost/Web_Coffee_Shop`.
2. Start **Apache** and **MySQL** in the XAMPP control panel.
3. Import the database: open phpMyAdmin and import `database/dizon_coffee.sql`
   (or run `mysql -u root < database/dizon_coffee.sql` from a terminal).
4. If your MySQL root user has a password, update `DB_USER` / `DB_PASS` in `config/config.php`.
5. Visit `http://localhost/Web_Coffee_Shop/` in your browser.

## Logging in

- **Admin:** `admin@dizoncoffee.com` / `admin123` (seeded in the SQL file — change the password after first login).
  The admin email is set in `config/config.php` via `ADMIN_EMAIL`; anyone who signs up with that exact email
  is automatically made an admin.
- **Customer:** sign up with any other email from the login/signup page.

## Project structure

```
config/         Database connection + app settings (config.php)
includes/       Shared PHP includes: functions.php, header/footer, admin header/footer
admin/          Admin-only pages: dashboard (orders), menu item management
uploads/menu/   Uploaded menu item photos
assets/         CSS, JS, and site images
database/       SQL schema + seed data
*.php (root)    Public/customer-facing pages (home, menu, cart, checkout, auth, orders)
```

## Features

- Single login/signup page (`auth.php`), passwords hashed with `password_hash`/`password_verify`.
- Customers: browse the menu, add items to a session-based cart, checkout with Cash on Delivery,
  and view their order history (`my_orders.php`).
- Admins: view all orders and update their status, and add/edit/delete menu items (with optional photo upload).
- All database access uses PDO with prepared statements.
