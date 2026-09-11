<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/admin/menu.php');
}

$id = (int) ($_POST['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare('SELECT image FROM menu_items WHERE id = ?');
    $stmt->execute([$id]);
    $item = $stmt->fetch();

    try {
        $stmt = $pdo->prepare('DELETE FROM menu_items WHERE id = ?');
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        // Item is referenced by existing order_items (ON DELETE RESTRICT).
        redirect('/admin/menu.php?error=in_use');
    }

    // Only remove files that were actually uploaded through this form — static
    // seed photos under assets/img are shared assets, not per-item uploads.
    if ($item && !empty($item['image']) && strpos($item['image'], 'uploads/') === 0) {
        $path = __DIR__ . '/../' . $item['image'];
        if (is_file($path)) {
            unlink($path);
        }
    }
}

redirect('/admin/menu.php?deleted=1');
