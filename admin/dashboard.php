<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

$totalOrders = (int) $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$pendingOrders = (int) $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn();
$revenue = (float) $pdo->query("SELECT COALESCE(SUM(total), 0) FROM orders WHERE status != 'cancelled'")->fetchColumn();
$totalUsers = (int) $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();

$orders = $pdo->query(
    "SELECT o.*, u.name AS customer_name, u.email AS customer_email
     FROM orders o
     JOIN users u ON u.id = o.user_id
     ORDER BY o.created_at DESC"
)->fetchAll();

$itemsStmt = $pdo->prepare(
    'SELECT oi.quantity, oi.price_at_order, mi.name
     FROM order_items oi
     JOIN menu_items mi ON mi.id = oi.menu_item_id
     WHERE oi.order_id = ?'
);

$statuses = ['pending', 'preparing', 'out for delivery', 'completed', 'cancelled'];

$pageTitle = 'Orders — Admin';
$activePage = 'orders';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h1>Orders</h1>

<?php if (isset($_GET['updated'])): ?>
    <div class="alert alert--success">Order status updated.</div>
<?php endif; ?>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-card__label">Total Orders</div>
        <div class="stat-card__value"><?= $totalOrders ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-card__label">Pending</div>
        <div class="stat-card__value"><?= $pendingOrders ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-card__label">Revenue</div>
        <div class="stat-card__value"><?= formatPrice($revenue) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-card__label">Customers</div>
        <div class="stat-card__value"><?= $totalUsers ?></div>
    </div>
</div>

<div class="card">
    <?php if (empty($orders)): ?>
        <div class="empty-state">No orders yet.</div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th class="num">Total</th>
                    <th>Payment</th>
                    <th>Placed</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <?php
                    $itemsStmt->execute([$order['id']]);
                    $lines = $itemsStmt->fetchAll();
                    $badgeClass = 'badge--' . str_replace(' ', '-', $order['status']);
                    ?>
                    <tr>
                        <td>#<?= (int) $order['id'] ?></td>
                        <td><?= e($order['customer_name']) ?><br><small style="color:var(--brown);"><?= e($order['customer_email']) ?></small></td>
                        <td>
                            <?php foreach ($lines as $line): ?>
                                <?= e($line['name']) ?> &times; <?= (int) $line['quantity'] ?><br>
                            <?php endforeach; ?>
                        </td>
                        <td class="num"><?= formatPrice($order['total']) ?></td>
                        <td>
                            <?= $order['payment_method'] === 'gcash' ? 'GCash' : 'Cash on Delivery' ?>
                            <?php if ($order['payment_method'] === 'gcash' && !empty($order['reference_code'])): ?>
                                <br><small style="color:var(--brown);">Ref: <?= e($order['reference_code']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?= e(date('M j, Y g:i A', strtotime($order['created_at']))) ?></td>
                        <td>
                            <span class="badge <?= e($badgeClass) ?>"><?= e(ucwords($order['status'])) ?></span>
                            <form method="POST" action="<?= BASE_URL ?>/admin/update_order_status.php" class="status-form" style="margin-top:8px;">
                                <input type="hidden" name="order_id" value="<?= (int) $order['id'] ?>">
                                <select name="status">
                                    <?php foreach ($statuses as $status): ?>
                                        <option value="<?= e($status) ?>" <?= $status === $order['status'] ? 'selected' : '' ?>>
                                            <?= e(ucwords($status)) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn btn--dark btn--small">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
