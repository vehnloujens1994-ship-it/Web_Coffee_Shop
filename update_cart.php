<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

requireLogin('/cart.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/cart.php');
}

$menuItemId = (int) ($_POST['menu_item_id'] ?? 0);
$action = $_POST['action'] ?? 'update';

if ($action === 'remove') {
    unset($_SESSION['cart'][$menuItemId]);
} else {
    $quantity = (int) ($_POST['quantity'] ?? 1);
    if ($quantity < 1) {
        unset($_SESSION['cart'][$menuItemId]);
    } else {
        $_SESSION['cart'][$menuItemId] = min($quantity, 20);
    }
}

redirect('/cart.php');
