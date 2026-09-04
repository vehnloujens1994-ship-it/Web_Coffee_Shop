<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

requireLogin();

$cart = getCartDetails($pdo);

if (empty($cart['items'])) {
    redirect('/cart.php');
}

$pageTitle = 'Checkout — Dizon Coffee Roasters';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page">
    <h1>Checkout</h1>

    <div class="card" style="margin-bottom: 24px;">
        <h3>Order Summary</h3>
        <table>
            <tbody>
                <?php foreach ($cart['items'] as $item): ?>
                    <tr>
                        <td><?= e($item['name']) ?> &times; <?= (int) $item['quantity'] ?></td>
                        <td class="num"><?= formatPrice($item['line_total']) ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="cart-total-row">
                    <td>Total</td>
                    <td class="num"><?= formatPrice($cart['total']) ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="card">
        <h3>Payment Method</h3>
        <p style="color: var(--brown); margin-bottom: 20px;">Cash on Delivery (COD) is the only payment method currently available.</p>

        <form method="POST" action="<?= BASE_URL ?>/place_order.php">
            <div class="form-group">
                <label>Payment Method</label>
                <input type="text" value="Cash on Delivery" disabled>
            </div>
            <button type="submit" class="btn btn--gold btn--full">Place Order</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
