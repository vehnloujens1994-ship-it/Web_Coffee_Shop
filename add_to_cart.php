<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/menu.php');
}

$menuItemId = (int) ($_POST['menu_item_id'] ?? 0);
$quantity = max(1, (int) ($_POST['quantity'] ?? 1));

if (!isLoggedIn()) {
    // Not logged in yet — remember what they were adding and send them to
    // log in/sign up. auth.php will finish adding it and bring them back.
    $_SESSION['pending_cart_add'] = ['menu_item_id' => $menuItemId, 'quantity' => $quantity];
    rememberRedirect('/menu.php?added=1');
    redirect('/auth.php');
}

addToCart($pdo, $menuItemId, $quantity);

redirect('/menu.php?added=1');
