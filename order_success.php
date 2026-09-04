<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

requireLogin();

$orderId = (int) ($_GET['order_id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ? AND user_id = ?');
$stmt->execute([$orderId, $_SESSION['user_id']]);
$order = $stmt->fetch();

if (!$order) {
    redirect('/menu.php');
}

$pageTitle = 'Order Placed — Dizon Coffee Roasters';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page page--narrow" style="text-align:center;">
    <div class="card">
        <div style="font-size:50px;">&#9749;</div>
        <h1>Thank You!</h1>
        <p style="color: var(--brown); margin-bottom: 20px;">
            Your order <strong>#<?= (int) $order['id'] ?></strong> has been placed and will be paid via
            <strong>Cash on Delivery</strong>.
        </p>
        <p class="menu-card__price"><?= formatPrice($order['total']) ?></p>
        <br>
        <a href="<?= BASE_URL ?>/my_orders.php" class="btn btn--dark">View My Orders</a>
        &nbsp;
        <a href="<?= BASE_URL ?>/menu.php" class="btn btn--outline-dark">Back to Menu</a>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
