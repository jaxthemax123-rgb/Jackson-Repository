<?php
// UM-01 Create User Account (WF-01)
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../services/userService.php';
require_once __DIR__ . '/../services/requestService.php';
startSecureSession();

$errors = [];
$created = false;
$input = ['full_name' => '', 'email' => '', 'role' => 'Student'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken($_POST);
    $input = array_merge($input, $_POST);
    try {
        registerUser(openDatabase(), $_POST);
        $created = true;
    } catch (InvalidArgumentException $e) {
        $errors[] = $e->getMessage();
    } catch (Throwable $e) {
        error_log($e->getMessage());
        $errors[] = 'Unable to create the account.';
    }
}
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/nav.php'; ?>
<main class="container py-4">
    <h1>Create Account</h1>
    <?php if ($created): ?>
        <div class="alert alert-success">Your account was created and is Active. <a href="login.php">Log in</a></div>
    <?php else: ?>
        <?php foreach ($errors as $error): ?><div class="alert alert-danger"><?= escapeHtml($error) ?></div><?php endforeach; ?>
        <form method="post" action="register.php">
            <?= csrfField() ?>
            <label class="form-label" for="full_name">Full Name</label>
            <input class="form-control mb-3" id="full_name" name="full_name" maxlength="120" required value="<?= escapeHtml($input['full_name']) ?>">
            <label class="form-label" for="email">Email</label>
            <input class="form-control mb-3" id="email" name="email" type="email" required value="<?= escapeHtml($input['email']) ?>">
            <label class="form-label" for="password">Password (at least 12 characters)</label>
            <input class="form-control mb-3" id="password" name="password" type="password" minlength="12" required>
            <label class="form-label" for="confirm_password">Confirm Password</label>
            <input class="form-control mb-3" id="confirm_password" name="confirm_password" type="password" minlength="12" required>
            <fieldset class="mb-3"><legend>Account type</legend>
                <?php foreach (['Student', 'Tutor'] as $role): ?>
                    <label class="me-3"><input type="radio" name="role" value="<?= $role ?>" <?= $input['role'] === $role ? 'checked' : '' ?> required> <?= $role ?></label>
                <?php endforeach; ?>
            </fieldset>
            <button class="btn btn-primary">Create Account</button>
        </form>
    <?php endif; ?>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
