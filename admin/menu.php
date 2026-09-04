<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

$menuItems = $pdo->query('SELECT * FROM menu_items ORDER BY category, name')->fetchAll();

$pageTitle = 'Menu Items — Admin';
$activePage = 'menu';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h1>Menu Items</h1>

<?php if (isset($_GET['saved'])): ?>
    <div class="alert alert--success">Menu item saved.</div>
<?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert--success">Menu item deleted.</div>
<?php endif; ?>
<?php if (($_GET['error'] ?? '') === 'in_use'): ?>
    <div class="alert alert--error">Can't delete this item — it's part of existing orders.</div>
<?php endif; ?>

<div style="margin-bottom: 20px;">
    <a href="<?= BASE_URL ?>/admin/menu_form.php" class="btn btn--gold">+ Add Menu Item</a>
</div>

<div class="card">
    <?php if (empty($menuItems)): ?>
        <div class="empty-state">No menu items yet. Add your first one above.</div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th class="num">Price</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($menuItems as $item): ?>
                    <tr>
                        <td>
                            <?php if (!empty($item['image'])): ?>
                                <img src="<?= BASE_URL ?>/uploads/menu/<?= e($item['image']) ?>" alt="" style="width:50px; height:50px; object-fit:cover; border-radius:6px;">
                            <?php else: ?>
                                &#9749;
                            <?php endif; ?>
                        </td>
                        <td><?= e($item['name']) ?><br><small style="color:var(--brown);"><?= e($item['description']) ?></small></td>
                        <td><?= e($item['category']) ?></td>
                        <td class="num"><?= formatPrice($item['price']) ?></td>
                        <td>
                            <div class="actions">
                                <a href="<?= BASE_URL ?>/admin/menu_form.php?id=<?= (int) $item['id'] ?>" class="btn btn--outline-dark btn--small">Edit</a>
                                <form method="POST" action="<?= BASE_URL ?>/admin/menu_delete.php" onsubmit="return confirm('Delete this menu item?');">
                                    <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                                    <button type="submit" class="btn btn--small" style="background:#F6E2DD; color:#A33B2E;">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
