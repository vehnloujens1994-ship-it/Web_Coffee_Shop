<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

requireLogin();

$cart = getCartDetails($pdo);

$pageTitle = 'Your Bag — Dizon Coffee Roasters';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page">
    <h1>Your Bag</h1>

    <?php if (empty($cart['items'])): ?>
        <div class="card empty-state">
            Your bag is empty.
            <br><br>
            <a href="<?= BASE_URL ?>/menu.php" class="btn btn--dark">Browse the Menu</a>
        </div>
    <?php else: ?>
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="num">Price</th>
                        <th>Quantity</th>
                        <th class="num">Line Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart['items'] as $item): ?>
                        <tr>
                            <td><?= e($item['name']) ?></td>
                            <td class="num"><?= formatPrice($item['price']) ?></td>
                            <td>
                                <form method="POST" action="<?= BASE_URL ?>/update_cart.php" class="qty-form">
                                    <input type="hidden" name="menu_item_id" value="<?= (int) $item['id'] ?>">
                                    <input type="hidden" name="action" value="update">
                                    <input type="number" name="quantity" value="<?= (int) $item['quantity'] ?>" min="1" max="20">
                                    <button type="submit" class="btn btn--outline-dark btn--small">Update</button>
                                </form>
                            </td>
                            <td class="num"><?= formatPrice($item['line_total']) ?></td>
                            <td>
                                <form method="POST" action="<?= BASE_URL ?>/update_cart.php">
                                    <input type="hidden" name="menu_item_id" value="<?= (int) $item['id'] ?>">
                                    <input type="hidden" name="action" value="remove">
                                    <button type="submit" class="btn btn--small" style="background:#F6E2DD; color:#A33B2E;">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="cart-total-row">
                        <td colspan="3">Total</td>
                        <td class="num"><?= formatPrice($cart['total']) ?></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <br>
        <div style="text-align:right;">
            <a href="<?= BASE_URL ?>/checkout.php" class="btn btn--gold">Proceed to Checkout &rarr;</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
