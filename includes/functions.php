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

// Remembers where to send the user after they log in. Pass an explicit path
// (e.g. '/cart.php') for POST-only action endpoints; otherwise defaults to
// the current request URI (fine for plain GET pages).
function rememberRedirect($path = null) {
    $path = $path ?? ($_SERVER['REQUEST_URI'] ?? '/');
    if (strpos($path, BASE_URL) === 0) {
        $path = substr($path, strlen(BASE_URL));
    }
    $_SESSION['redirect_after_login'] = $path;
}

// Gate for pages that require a logged-in user (cart, checkout, account pages).
// Remembers the page so the user is sent back here after logging in.
function requireLogin($redirectTo = null) {
    if (!isLoggedIn()) {
        rememberRedirect($redirectTo);
        redirect('/auth.php');
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        redirect('/auth.php');
    }
}

// Called once a user has just logged in or signed up. Applies any cart add
// that was deferred by the login gate, then sends them back to whatever
// page they were trying to reach (customers only — admins always land on
// the dashboard).
function completeLoginRedirect(PDO $pdo, $role) {
    if (isset($_SESSION['pending_cart_add'])) {
        $pending = $_SESSION['pending_cart_add'];
        unset($_SESSION['pending_cart_add']);
        addToCart($pdo, (int) $pending['menu_item_id'], (int) $pending['quantity']);
    }

    $target = $_SESSION['redirect_after_login'] ?? null;
    unset($_SESSION['redirect_after_login']);

    if ($role !== 'admin' && $target) {
        redirect($target);
    }

    redirect($role === 'admin' ? '/admin/dashboard.php' : '/menu.php');
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

// Adds a menu item to the session cart. Returns false if the item doesn't exist.
function addToCart(PDO $pdo, $menuItemId, $quantity) {
    $stmt = $pdo->prepare('SELECT id FROM menu_items WHERE id = ?');
    $stmt->execute([$menuItemId]);

    if (!$stmt->fetch()) {
        return false;
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $current = $_SESSION['cart'][$menuItemId] ?? 0;
    $_SESSION['cart'][$menuItemId] = $current + $quantity;

    return true;
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
