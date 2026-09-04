<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

if (isLoggedIn()) {
    redirect(isAdmin() ? '/admin/dashboard.php' : '/menu.php');
}

$mode = ($_GET['mode'] ?? 'login') === 'signup' ? 'signup' : 'login';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'signup') {
        $mode = 'signup';
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($name === '' || $email === '' || $password === '') {
            $error = 'Please fill in all fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } elseif ($password !== $confirmPassword) {
            $error = 'Passwords do not match.';
        } else {
            $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'An account with that email already exists.';
            } else {
                $role = (strcasecmp($email, ADMIN_EMAIL) === 0) ? 'admin' : 'customer';
                $hash = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
                $stmt->execute([$name, $email, $hash, $role]);

                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['name'] = $name;
                $_SESSION['role'] = $role;

                redirect($role === 'admin' ? '/admin/dashboard.php' : '/menu.php');
            }
        }
    } elseif ($action === 'login') {
        $mode = 'login';
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            $error = 'Incorrect email or password.';
        } else {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            redirect($user['role'] === 'admin' ? '/admin/dashboard.php' : '/menu.php');
        }
    }
}

$pageTitle = ($mode === 'signup' ? 'Sign Up' : 'Login') . ' — Dizon Coffee Roasters';
$bodyClass = 'auth-page';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page page--narrow">
    <div class="auth-tabs">
        <a href="?mode=login" class="<?= $mode === 'login' ? 'active' : '' ?>">Login</a>
        <a href="?mode=signup" class="<?= $mode === 'signup' ? 'active' : '' ?>">Sign Up</a>
    </div>

    <div class="card">
        <?php if ($error): ?>
            <div class="alert alert--error"><?= e($error) ?></div>
        <?php endif; ?>

        <?php if ($mode === 'signup'): ?>
            <h1 style="font-size:22px;">Create Your Account</h1>
            <form method="POST" action="?mode=signup">
                <input type="hidden" name="action" value="signup">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required value="<?= e($_POST['name'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="signup_email">Email</label>
                    <input type="email" id="signup_email" name="email" required value="<?= e($_POST['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="signup_password">Password</label>
                    <input type="password" id="signup_password" name="password" required minlength="6">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                </div>
                <button type="submit" class="btn btn--dark btn--full">Sign Up</button>
            </form>
        <?php else: ?>
            <h1 style="font-size:22px;">Welcome Back</h1>
            <form method="POST" action="?mode=login">
                <input type="hidden" name="action" value="login">
                <div class="form-group">
                    <label for="login_email">Email</label>
                    <input type="email" id="login_email" name="email" required value="<?= e($_POST['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="login_password">Password</label>
                    <input type="password" id="login_password" name="password" required>
                </div>
                <button type="submit" class="btn btn--dark btn--full">Login</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
