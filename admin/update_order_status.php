<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/admin/dashboard.php');
}

$orderId = (int) ($_POST['order_id'] ?? 0);
$status = $_POST['status'] ?? '';
$view = ($_POST['view'] ?? 'active') === 'completed' ? 'completed' : 'active';

$allowedStatuses = ['pending', 'preparing', 'out for delivery', 'completed', 'cancelled'];

if ($orderId > 0 && in_array($status, $allowedStatuses, true)) {
    $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
    $stmt->execute([$status, $orderId]);
}

redirect('/admin/dashboard.php?view=' . $view . '&updated=1');
