<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

requireLogin('/checkout.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/cart.php');
}

$cart = getCartDetails($pdo);

if (empty($cart['items'])) {
    redirect('/cart.php');
}

$paymentMethod = $_POST['payment_method'] ?? 'cod';
if (!in_array($paymentMethod, ['cod', 'gcash'], true)) {
    $paymentMethod = 'cod';
}

$referenceCode = null;
if ($paymentMethod === 'gcash') {
    $referenceCode = trim($_POST['reference_code'] ?? '');
    if ($referenceCode === '' || !preg_match('/^[0-9-]+$/', $referenceCode)) {
        redirect('/checkout.php?error=invalid_reference');
    }
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare('INSERT INTO orders (user_id, status, total, payment_method, reference_code) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$_SESSION['user_id'], 'pending', $cart['total'], $paymentMethod, $referenceCode]);
    $orderId = $pdo->lastInsertId();

    $stmt = $pdo->prepare('INSERT INTO order_items (order_id, menu_item_id, quantity, price_at_order) VALUES (?, ?, ?, ?)');
    foreach ($cart['items'] as $item) {
        $stmt->execute([$orderId, $item['id'], $item['quantity'], $item['price']]);
    }

    $pdo->commit();
} catch (PDOException $e) {
    $pdo->rollBack();
    die('Something went wrong while placing your order. Please try again.');
}

unset($_SESSION['cart']);

redirect('/order_success.php?order_id=' . $orderId);
