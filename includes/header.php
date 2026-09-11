<?php
// Expects (optionally) $pageTitle and $bodyClass to be set before including this file.
$pageTitle = $pageTitle ?? 'Dizon Coffee Roasters';
$bodyClass = $bodyClass ?? '';
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
<body class="<?= e($bodyClass) ?>">

<header class="site-header">
    <div class="container site-header__inner">
        <a class="brand" href="<?= BASE_URL ?>/index.php">
            <img src="<?= BASE_URL ?>/assets/img/logo.png" alt="Dizon Coffee Roasters logo" class="brand__logo">
            <span class="brand__text">DIZON<br><small>COFFEE ROASTERS</small></span>
        </a>

        <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">&#9776;</button>

        <nav class="main-nav" id="mainNav">
            <a href="<?= BASE_URL ?>/index.php#mission">ABOUT</a>
            <a href="<?= BASE_URL ?>/menu.php">MENU</a>
            <a href="<?= BASE_URL ?>/index.php#visit">VISIT</a>
            <?php if (isAdmin()): ?>
                <a href="<?= BASE_URL ?>/admin/dashboard.php">ADMIN DASHBOARD</a>
                <span class="nav-user">Admin</span>
                <a href="<?= BASE_URL ?>/logout.php" class="btn btn--nav">Logout</a>
            <?php elseif (isLoggedIn()): ?>
                <a href="<?= BASE_URL ?>/my_orders.php">MY ORDERS</a>
                <a href="<?= BASE_URL ?>/cart.php">
                    CART<?php $c = cartItemCount(); if ($c > 0): ?> (<?= $c ?>)<?php endif; ?>
                </a>
                <span class="nav-user"><?= e($_SESSION['name']) ?></span>
                <a href="<?= BASE_URL ?>/logout.php" class="btn btn--nav">Logout</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/auth.php" class="btn btn--nav">LOGIN / SIGN UP</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main>
