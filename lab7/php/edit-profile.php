<?php
// UM-04 Update Own Account (WF-02). Role and status are read only here.
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../services/userService.php';
require_once __DIR__ . '/../services/requestService.php';
$pdo = openDatabase();
$user = requireLogin($pdo);

$errors = [];
$input = ['full_name' => $user['full_name'], 'email' => $user['email']];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken($_POST);
    $input = ['full_name' => (string)($_POST['full_name'] ?? ''), 'email' => (string)($_POST['email'] ?? '')];
    try {
        updateOwnAccount($pdo, (int)$user['user_id'], $input);
        header('Location: profile.php?saved=1', true, 303);
        exit;
    } catch (InvalidArgumentException $e) {
        $errors[] = $e->getMessage();
    }
}
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/nav.php'; ?>
<main class="container py-4">
    <h1>Edit Profile</h1>
    <?php foreach ($errors as $error): ?><div class="alert alert-danger"><?= escapeHtml($error) ?></div><?php endforeach; ?>
    <form method="post" action="edit-profile.php">
        <?= csrfField() ?>
        <label class="form-label" for="full_name">Name</label>
        <input class="form-control mb-3" id="full_name" name="full_name" maxlength="120" required value="<?= escapeHtml($input['full_name']) ?>">
        <label class="form-label" for="email">Email</label>
        <input class="form-control mb-3" id="email" name="email" type="email" required value="<?= escapeHtml($input['email']) ?>">
        <p>Role: <?= escapeHtml($user['role']) ?> (read only)<br>Status: <?= escapeHtml($user['status']) ?> (read only)</p>
        <button class="btn btn-primary">Save Changes</button>
        <a class="btn btn-secondary" href="profile.php">Cancel</a>
    </form>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
