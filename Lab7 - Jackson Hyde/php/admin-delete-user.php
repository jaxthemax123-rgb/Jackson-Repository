<?php
// UM-08 Delete User Account (WF-07). Administrator only.
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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken($_POST);
    try {
        deleteUserAccount($pdo, $id);
        header('Location: admin-users.php', true, 303);
        exit;
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
    }
}
$u = findUserById($pdo, $id);
if (!$u) { http_response_code(404); exit('User not found'); }
$requestCount = countRequestsForUser($pdo, $id);
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/nav.php'; ?>
<main class="container py-4">
    <h1>Delete account for <?= escapeHtml($u['full_name']) ?>?</h1>
    <?php if ($error !== ''): ?><div class="alert alert-danger"><?= escapeHtml($error) ?></div><?php endif; ?>
    <p>Related tutoring requests: <?= $requestCount ?>. Accounts with tutoring requests cannot be deleted.</p>
    <form method="post">
        <?= csrfField() ?>
        <a class="btn btn-secondary" href="admin-user.php?user_id=<?= (int)$u['user_id'] ?>">Cancel</a>
        <button class="btn btn-danger">Confirm Delete</button>
    </form>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
