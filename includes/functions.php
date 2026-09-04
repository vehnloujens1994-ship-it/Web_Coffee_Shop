<?php
// Shared helper functions. config.php must already be included (session + $pdo).

function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect($path) {
    header('Location: ' . BASE_URL . $path);
    exit;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isLoggedIn() && ($_SESSION['role'] ?? '') === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect('/auth.php');
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        redirect('/auth.php');
    }
}

function formatPrice($amount) {
    return 'PHP ' . number_format((float) $amount, 2);
}

// ---------------------------------------------------------------
// Cart helpers — cart is stored in the session as [menu_item_id => quantity]
// ---------------------------------------------------------------

function getCart() {
    return $_SESSION['cart'] ?? [];
}

function cartItemCount() {
    $count = 0;
    foreach (getCart() as $qty) {
        $count += (int) $qty;
    }
    return $count;
}

// Returns the cart as a list of rows (menu item details + quantity + line_total),
// and the overall total. Skips any item that no longer exists in the menu.
function getCartDetails(PDO $pdo) {
    $cart = getCart();
    $items = [];
    $total = 0.0;

    if (!empty($cart)) {
        $ids = array_map('intval', array_keys($cart));
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $menuItems = $stmt->fetchAll();

        foreach ($menuItems as $item) {
            $qty = (int) $cart[$item['id']];
            if ($qty < 1) {
                continue;
            }
            $lineTotal = $item['price'] * $qty;
            $total += $lineTotal;
            $items[] = [
                'id'          => $item['id'],
                'name'        => $item['name'],
                'price'       => $item['price'],
                'image'       => $item['image'],
                'quantity'    => $qty,
                'line_total'  => $lineTotal,
            ];
        }
    }

    return ['items' => $items, 'total' => $total];
}
