<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

$stmt = $pdo->query('SELECT * FROM menu_items ORDER BY category, name');
$menuItems = $stmt->fetchAll();

$pageTitle = 'Menu — Dizon Coffee Roasters';
require_once __DIR__ . '/includes/header.php';
?>

<section class="menu-page">
    <div class="container">
        <h1>Our Menu</h1>
        <p class="menu-page__sub">Fresh grind, brewed to order.</p>

        <?php if (isset($_GET['added'])): ?>
            <div class="alert alert--success">Added to your bag.</div>
        <?php endif; ?>

        <?php if (empty($menuItems)): ?>
            <div class="empty-state">The menu is being updated. Please check back soon.</div>
        <?php else: ?>
            <div class="menu-grid">
                <?php foreach ($menuItems as $item): ?>
                    <div class="menu-card">
                        <?php if (!empty($item['image'])): ?>
                            <img class="menu-card__image" src="<?= BASE_URL ?>/<?= e($item['image']) ?>" alt="<?= e($item['name']) ?>">
                        <?php else: ?>
                            <div class="menu-card__placeholder">&#9749;</div>
                        <?php endif; ?>
                        <span class="menu-card__category"><?= e($item['category']) ?></span>
                        <h3><?= e($item['name']) ?></h3>
                        <p><?= e($item['description']) ?></p>
                        <div class="menu-card__price"><?= formatPrice($item['price']) ?></div>

                        <?php if (isAdmin()): ?>
                            <span class="badge badge--pending">Admin view — login as customer to order</span>
                        <?php else: ?>
                            <form method="POST" action="<?= BASE_URL ?>/add_to_cart.php">
                                <input type="hidden" name="menu_item_id" value="<?= (int) $item['id'] ?>">
                                <input type="number" name="quantity" value="1" min="1" max="20">
                                <button type="submit" class="btn btn--dark btn--small">Add to Bag</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
