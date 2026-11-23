<?php
// UM-09 Log In (WF-08)
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../services/userService.php';
require_once __DIR__ . '/../services/requestService.php';
startSecureSession();

$error = '';
$email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken($_POST);
    $email = (string)($_POST['email'] ?? '');
    $user = authenticateUser(openDatabase(), $email, $_POST['password'] ?? '');
    if ($user) {
        loginSession($user);
        header('Location: profile.php', true, 303);
        exit;
    }
    $error = 'Invalid email or password.';
}
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/nav.php'; ?>
<main class="container py-4">
    <h1>Log In</h1>
    <?php if (isset($_GET['expired'])): ?><div class="alert alert-warning">Your session expired. Please log in again.</div><?php endif; ?>
    <?php if (isset($_GET['inactive'])): ?><div class="alert alert-warning">Your account is not active.</div><?php endif; ?>
    <?php if (isset($_GET['logged_out'])): ?><div class="alert alert-info">You have been logged out.</div><?php endif; ?>
    <?php if ($error !== ''): ?><div class="alert alert-danger"><?= escapeHtml($error) ?></div><?php endif; ?>
    <form method="post" action="login.php">
        <?= csrfField() ?>
        <label class="form-label" for="email">Email</label>
        <input class="form-control mb-3" id="email" name="email" type="email" required value="<?= escapeHtml($email) ?>">
        <label class="form-label" for="password">Password</label>
        <input class="form-control mb-3" id="password" name="password" type="password" required>
        <button class="btn btn-primary">Log In</button>
    </form>
    <p class="mt-3">Need an account? <a href="register.php">Create Account</a></p>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
