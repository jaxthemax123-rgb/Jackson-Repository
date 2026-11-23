<?php
// UM-03 View and Search User Accounts (WF-03). Administrator only.
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../services/requestService.php';
$pdo = openDatabase();
$user = requireLogin($pdo);
requireRole($user, ['Administrator']);

$search = trim((string)($_GET['search'] ?? ''));
$role = (string)($_GET['role'] ?? '');
$status = (string)($_GET['status'] ?? '');
if ($role !== '' && !in_array($role, ['Student', 'Tutor', 'Administrator'], true)) { $role = ''; }
if ($status !== '' && !in_array($status, ['Active', 'Inactive'], true)) { $status = ''; }
$users = searchUsers($pdo, $search, $role, $status);
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/nav.php'; ?>
<main class="container py-4">
    <h1>Manage Users</h1>
    <form method="get" class="row g-2 mb-3">
        <div class="col"><input class="form-control" name="search" placeholder="Search name or email" value="<?= escapeHtml($search) ?>"></div>
        <div class="col"><select class="form-select" name="role"><option value="">All roles</option>
            <?php foreach (['Student', 'Tutor', 'Administrator'] as $r): ?><option <?= $r === $role ? 'selected' : '' ?>><?= $r ?></option><?php endforeach; ?></select></div>
        <div class="col"><select class="form-select" name="status"><option value="">All statuses</option>
            <?php foreach (['Active', 'Inactive'] as $s): ?><option <?= $s === $status ? 'selected' : '' ?>><?= $s ?></option><?php endforeach; ?></select></div>
        <div class="col-auto"><button class="btn btn-primary">Search</button></div>
    </form>
    <?php if (!$users): ?><p>No matching users.</p><?php else: ?>
    <table class="table">
        <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Manage</th></tr></thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= escapeHtml($u['full_name']) ?></td>
                <td><?= escapeHtml($u['email']) ?></td>
                <td><?= escapeHtml($u['role']) ?></td>
                <td><?= escapeHtml($u['status']) ?></td>
                <td><a href="admin-user.php?user_id=<?= (int)$u['user_id'] ?>">Manage</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
