<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/menu.php');
}

$menuItemId = (int) ($_POST['menu_item_id'] ?? 0);
$quantity = max(1, (int) ($_POST['quantity'] ?? 1));

$stmt = $pdo->prepare('SELECT id FROM menu_items WHERE id = ?');
$stmt->execute([$menuItemId]);

if ($stmt->fetch()) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $current = $_SESSION['cart'][$menuItemId] ?? 0;
    $_SESSION['cart'][$menuItemId] = $current + $quantity;
}

redirect('/menu.php?added=1');
