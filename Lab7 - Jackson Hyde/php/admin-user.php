<?php
// UM-03 account details, UM-06 Update User Role (WF-05), UM-07 Activate or Deactivate (WF-06). Administrator only.
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../services/userService.php';
require_once __DIR__ . '/../services/requestService.php';
$pdo = openDatabase();
$admin = requireLogin($pdo);
requireRole($admin, ['Administrator']);

$id = filter_input(INPUT_GET, 'user_id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) { http_response_code(400); exit('Invalid user ID'); }

$error = '';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken($_POST);
    try {
        if (($_POST['action'] ?? '') === 'role') {
            changeUserRole($pdo, $id, (string)($_POST['role'] ?? ''));
            $message = 'Role updated.';
        } elseif (($_POST['action'] ?? '') === 'status') {
            changeUserStatus($pdo, $id, (string)($_POST['status'] ?? ''));
            $message = 'Status updated.';
        }
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
    }
}
$u = findUserById($pdo, $id);
if (!$u) { http_response_code(404); exit('User not found'); }
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/nav.php'; ?>
<main class="container py-4">
    <h1>Manage <?= escapeHtml($u['full_name']) ?></h1>
    <?php if ($message !== ''): ?><div class="alert alert-success"><?= escapeHtml($message) ?></div><?php endif; ?>
    <?php if ($error !== ''): ?><div class="alert alert-danger"><?= escapeHtml($error) ?></div><?php endif; ?>
    <dl>
        <dt>Email</dt><dd><?= escapeHtml($u['email']) ?></dd>
        <dt>Role</dt><dd><?= escapeHtml($u['role']) ?></dd>
        <dt>Status</dt><dd><?= escapeHtml($u['status']) ?></dd>
        <dt>Created</dt><dd><?= escapeHtml($u['created_at']) ?></dd>
    </dl>

    <h2 class="h4">Update Role</h2>
    <form method="post" class="mb-4">
        <?= csrfField() ?>
        <input type="hidden" name="action" value="role">
        <select class="form-select mb-2" name="role">
            <?php foreach (['Student', 'Tutor', 'Administrator'] as $r): ?><option <?= $r === $u['role'] ? 'selected' : '' ?>><?= $r ?></option><?php endforeach; ?>
        </select>
        <button class="btn btn-primary">Save Role</button>
    </form>

    <h2 class="h4">Account Status</h2>
    <form method="post" class="mb-4">
        <?= csrfField() ?>
        <input type="hidden" name="action" value="status">
        <?php $newStatus = $u['status'] === 'Active' ? 'Inactive' : 'Active'; ?>
        <input type="hidden" name="status" value="<?= $newStatus ?>">
        <p>Current status: <?= escapeHtml($u['status']) ?>. New status: <?= $newStatus ?></p>
        <button class="btn btn-warning">Confirm</button>
    </form>

    <a class="btn btn-outline-danger" href="admin-delete-user.php?user_id=<?= (int)$u['user_id'] ?>">Delete Account</a>
    <a class="btn btn-secondary" href="admin-users.php">Back to users</a>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
