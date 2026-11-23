<?php
// UM-05 Change Password (WF-04)
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../services/userService.php';
require_once __DIR__ . '/../services/requestService.php';
$pdo = openDatabase();
$user = requireLogin($pdo);

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken($_POST);
    try {
        changePassword($pdo, (int)$user['user_id'], $_POST);
        header('Location: profile.php?password=1', true, 303);
        exit;
    } catch (InvalidArgumentException $e) {
        $errors[] = $e->getMessage();
    }
}
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/nav.php'; ?>
<main class="container py-4">
    <h1>Change Password</h1>
    <?php foreach ($errors as $error): ?><div class="alert alert-danger"><?= escapeHtml($error) ?></div><?php endforeach; ?>
    <form method="post" action="change-password.php">
        <?= csrfField() ?>
        <label class="form-label" for="current_password">Current Password</label>
        <input class="form-control mb-3" id="current_password" name="current_password" type="password" required>
        <label class="form-label" for="new_password">New Password (at least 12 characters)</label>
        <input class="form-control mb-3" id="new_password" name="new_password" type="password" minlength="12" required>
        <label class="form-label" for="confirm_password">Confirm New Password</label>
        <input class="form-control mb-3" id="confirm_password" name="confirm_password" type="password" minlength="12" required>
        <button class="btn btn-primary">Update Password</button>
        <a class="btn btn-secondary" href="profile.php">Cancel</a>
    </form>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
