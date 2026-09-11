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

        <?php if (($_GET['error'] ?? '') === 'invalid_reference'): ?>
            <div class="alert alert--error">Please enter a valid GCash reference code (numbers and dashes only).</div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/place_order.php" id="checkoutForm">
            <div class="form-group">
                <label>Payment Method</label>
                <div class="payment-options">
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="cod" checked>
                        Cash on Delivery
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="gcash">
                        GCash
                    </label>
                </div>
            </div>

            <div id="gcashPanel" class="gcash-panel" hidden>
                <img
                    class="gcash-qr"
                    src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=https://www.youtube.com/watch?v=dQw4w9WgXcQ"
                    alt="GCash payment QR code"
                    width="200" height="200">
                <p>Please enter your reference code</p>
                <div class="form-group">
                    <label for="reference_code">Reference Code</label>
                    <input
                        type="text"
                        id="reference_code"
                        name="reference_code"
                        pattern="[0-9-]+"
                        inputmode="numeric"
                        maxlength="50"
                        placeholder="e.g. 1234-5678-9012"
                        title="Numbers and dashes only">
                </div>
            </div>

            <button type="submit" class="btn btn--gold btn--full">Place Order</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
