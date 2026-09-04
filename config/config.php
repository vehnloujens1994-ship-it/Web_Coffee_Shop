<?php
// Dizon Coffee Roasters — database & app configuration
// Update DB_USER / DB_PASS below if your XAMPP MySQL user is not the default.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('DB_HOST', 'localhost');
define('DB_NAME', 'dizon_coffee');
define('DB_USER', 'root');
define('DB_PASS', '');

// The account that logs in with this email is treated as the admin.
define('ADMIN_EMAIL', 'admin@dizoncoffee.com');

// Base URL path of the app (folder name under htdocs). Used for links/redirects.
define('BASE_URL', '/Web_Coffee_Shop');

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die('Database connection failed. Make sure XAMPP\'s MySQL is running and the "dizon_coffee" database has been imported. (' . $e->getMessage() . ')');
}
