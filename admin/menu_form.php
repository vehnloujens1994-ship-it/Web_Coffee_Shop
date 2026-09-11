<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

$id = (int) ($_GET['id'] ?? 0);
$item = ['name' => '', 'description' => '', 'price' => '', 'category' => '', 'image' => ''];
$error = '';

if ($id > 0) {
    $stmt = $pdo->prepare('SELECT * FROM menu_items WHERE id = ?');
    $stmt->execute([$id]);
    $found = $stmt->fetch();
    if (!$found) {
        redirect('/admin/menu.php');
    }
    $item = $found;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = $_POST['price'] ?? '';
    $category = trim($_POST['category'] ?? '');
    $imageName = $item['image'];

    $item = array_merge($item, compact('name', 'description', 'price', 'category'));

    if ($name === '' || $category === '' || $price === '' || !is_numeric($price) || $price < 0) {
        $error = 'Please fill in the name, category, and a valid price.';
    } else {
        // Optional image upload
        if (!empty($_FILES['image']['name'])) {
            $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $imageInfo = @getimagesize($_FILES['image']['tmp_name']);

            if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                $error = 'Image upload failed. Please try again.';
            } elseif (!in_array($ext, $allowedExt, true) || $imageInfo === false) {
                $error = 'Image must be a JPG, PNG, or WEBP file.';
            } else {
                $newName = uniqid('menu_', true) . '.' . $ext;
                $destination = __DIR__ . '/../uploads/menu/' . $newName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                    $imageName = 'uploads/menu/' . $newName;
                } else {
                    $error = 'Could not save the uploaded image.';
                }
            }
        }

        if ($error === '') {
            if ($id > 0) {
                $stmt = $pdo->prepare('UPDATE menu_items SET name = ?, description = ?, price = ?, category = ?, image = ? WHERE id = ?');
                $stmt->execute([$name, $description, $price, $category, $imageName, $id]);
            } else {
                $stmt = $pdo->prepare('INSERT INTO menu_items (name, description, price, category, image) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([$name, $description, $price, $category, $imageName]);
            }
            redirect('/admin/menu.php?saved=1');
        }
    }
}

$pageTitle = ($id > 0 ? 'Edit' : 'Add') . ' Menu Item — Admin';
$activePage = 'menu';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h1><?= $id > 0 ? 'Edit' : 'Add' ?> Menu Item</h1>

<div class="card" style="max-width: 560px;">
    <?php if ($error): ?>
        <div class="alert alert--error"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required value="<?= e($item['name']) ?>">
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <input type="text" id="category" name="category" required value="<?= e($item['category']) ?>" placeholder="e.g. Coffee, Cold Brew, Beans">
        </div>
        <div class="form-group">
            <label for="price">Price (PHP)</label>
            <input type="number" id="price" name="price" step="0.01" min="0" required value="<?= e($item['price']) ?>">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="3"><?= e($item['description']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <?php if (!empty($item['image'])): ?>
                <img src="<?= BASE_URL ?>/<?= e($item['image']) ?>" alt="" style="width:80px; height:80px; object-fit:cover; border-radius:6px; margin-bottom:8px;">
            <?php endif; ?>
            <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp">
        </div>
        <div class="actions">
            <button type="submit" class="btn btn--dark">Save Menu Item</button>
            <a href="<?= BASE_URL ?>/admin/menu.php" class="btn btn--outline-dark">Cancel</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
