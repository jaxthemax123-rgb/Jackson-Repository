<?php
// UM-02 View Own Account (WF-02)
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../services/requestService.php';
$user = requireLogin(openDatabase());
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/nav.php'; ?>
<main class="container py-4">
    <h1>My Profile</h1>
    <?php if (isset($_GET['saved'])): ?><div class="alert alert-success">Your changes were saved.</div><?php endif; ?>
    <?php if (isset($_GET['password'])): ?><div class="alert alert-success">Your password was updated.</div><?php endif; ?>
    <dl>
        <dt>Name</dt><dd><?= escapeHtml($user['full_name']) ?></dd>
        <dt>Email</dt><dd><?= escapeHtml($user['email']) ?></dd>
        <dt>Role</dt><dd><?= escapeHtml($user['role']) ?></dd>
        <dt>Status</dt><dd><?= escapeHtml($user['status']) ?></dd>
    </dl>
    <a class="btn btn-primary" href="edit-profile.php">Edit Profile</a>
    <a class="btn btn-secondary" href="change-password.php">Change Password</a>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
