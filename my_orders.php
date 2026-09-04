<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

requireLogin();

$stmt = $pdo->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();

$itemsStmt = $pdo->prepare(
    'SELECT oi.quantity, oi.price_at_order, mi.name
     FROM order_items oi
     JOIN menu_items mi ON mi.id = oi.menu_item_id
     WHERE oi.order_id = ?'
);

$pageTitle = 'My Orders — Dizon Coffee Roasters';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page">
    <h1>My Orders</h1>

    <?php if (empty($orders)): ?>
        <div class="card empty-state">
            You haven't placed any orders yet.
            <br><br>
            <a href="<?= BASE_URL ?>/menu.php" class="btn btn--dark">Browse the Menu</a>
        </div>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <?php
            $itemsStmt->execute([$order['id']]);
            $lines = $itemsStmt->fetchAll();
            $badgeClass = 'badge--' . str_replace(' ', '-', $order['status']);
            ?>
            <div class="card" style="margin-bottom: 18px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 10px;">
                    <div>
                        <strong>Order #<?= (int) $order['id'] ?></strong>
                        &nbsp;<span style="color:var(--brown); font-size:13px;"><?= e(date('M j, Y g:i A', strtotime($order['created_at']))) ?></span>
                    </div>
                    <span class="badge <?= e($badgeClass) ?>"><?= e(ucwords($order['status'])) ?></span>
                </div>
                <table>
                    <tbody>
                        <?php foreach ($lines as $line): ?>
                            <tr>
                                <td><?= e($line['name']) ?> &times; <?= (int) $line['quantity'] ?></td>
                                <td class="num"><?= formatPrice($line['price_at_order'] * $line['quantity']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="cart-total-row">
                            <td>Total</td>
                            <td class="num"><?= formatPrice($order['total']) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
