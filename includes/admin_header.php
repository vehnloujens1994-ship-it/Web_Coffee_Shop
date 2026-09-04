<?php
// Expects requireAdmin() to already have been called, and $pageTitle optionally set.
$pageTitle = $pageTitle ?? 'Admin — Dizon Coffee Roasters';
$activePage = $activePage ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle) ?></title>
<link rel="icon" href="<?= BASE_URL ?>/assets/img/logo.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body class="admin-body">

<header class="admin-header">
    <div class="container admin-header__inner">
        <a class="brand" href="<?= BASE_URL ?>/admin/dashboard.php">
            <img src="<?= BASE_URL ?>/assets/img/logo.png" alt="Dizon Coffee Roasters logo" class="brand__logo">
            <span class="brand__text">DIZON<br><small>COFFEE ROASTERS ADMIN</small></span>
        </a>
        <nav class="admin-nav">
            <a href="<?= BASE_URL ?>/admin/dashboard.php" class="<?= $activePage === 'orders' ? 'active' : '' ?>">Orders</a>
            <a href="<?= BASE_URL ?>/admin/menu.php" class="<?= $activePage === 'menu' ? 'active' : '' ?>">Menu Items</a>
            <a href="<?= BASE_URL ?>/index.php">View Site</a>
            <span class="nav-user admin-nav__user">Admin</span>
            <a href="<?= BASE_URL ?>/logout.php" class="admin-nav__logout">Logout</a>
        </nav>
    </div>
</header>

<main class="admin-main">
