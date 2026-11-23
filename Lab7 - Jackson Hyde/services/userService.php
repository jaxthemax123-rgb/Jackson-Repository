<?php
// Lab 7: validation and business rules for user accounts (UM-01 to UM-09).
require_once dirname(__DIR__) . '/repositories/userPDO.php';
require_once dirname(__DIR__) . '/repositories/studentPDO.php';

function normalizeEmail($email) {
    return strtolower(trim((string)$email));
}

function validatePassword($password, $confirm) {
    $errors = [];
    if (strlen((string)$password) < 12) {
        $errors[] = 'Password must be at least 12 characters.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }
    return $errors;
}

function validateNameEmail($fullName, $email) {
    $errors = [];
    if ($fullName === '' || mb_strlen($fullName) > 120) {
        $errors[] = 'Full name is required (120 characters or less).';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email address.';
    }
    return $errors;
}

// UM-01 Create User Account. Public registration only allows Student or Tutor.
function validateRegistration($pdo, $input) {
    $fullName = trim((string)($input['full_name'] ?? ''));
    $email = normalizeEmail($input['email'] ?? '');
    $password = (string)($input['password'] ?? '');
    $confirm = (string)($input['confirm_password'] ?? '');
    $role = (string)($input['role'] ?? '');

    $errors = validateNameEmail($fullName, $email);
    $errors = array_merge($errors, validatePassword($password, $confirm));
    if (!in_array($role, ['Student', 'Tutor'], true)) {
        $errors[] = 'Choose Student or Tutor.';
    }
    if (!$errors && findUserByEmail($pdo, $email)) {
        $errors[] = 'An account with that email already exists.';
    }
    return [$errors, ['full_name' => $fullName, 'email' => $email, 'password' => $password, 'role' => $role]];
}

function registerUser($pdo, $input) {
    [$errors, $data] = validateRegistration($pdo, $input);
    if ($errors) {
        throw new InvalidArgumentException(implode(' ', $errors));
    }
    $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

    $pdo->beginTransaction();
    try {
        $userId = insertUser($pdo, $data['full_name'], $data['email'], $passwordHash, $data['role']);
        if ($data['role'] === 'Student') {
            // Reuse a Lab 6 student row with the same email, otherwise create one.
            $student = findStudentByEmail($pdo, $data['email']);
            if ($student) {
                linkStudentToUser($pdo, (int)$student['student_id'], $userId, $data['full_name']);
            } else {
                insertStudentForUser($pdo, $userId, $data['full_name'], $data['email']);
            }
        }
        $pdo->commit();
        return $userId;
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}

// Used only by database/create-admin.php (command line).
function createAdministrator($pdo, $fullName, $email, $password) {
    $fullName = trim((string)$fullName);
    $email = normalizeEmail($email);
    $errors = validateNameEmail($fullName, $email);
    $errors = array_merge($errors, validatePassword($password, $password));
    if (!$errors && findUserByEmail($pdo, $email)) {
        $errors[] = 'An account with that email already exists.';
    }
    if ($errors) {
        throw new InvalidArgumentException(implode(' ', $errors));
    }
    return insertUser($pdo, $fullName, $email, password_hash($password, PASSWORD_DEFAULT), 'Administrator');
}

// UM-09 Log In. Returns the user row, or false with one generic failure.
function authenticateUser($pdo, $email, $password) {
    $user = findUserByEmail($pdo, normalizeEmail($email));
    if (!$user || $user['status'] !== 'Active' || !password_verify((string)$password, $user['password_hash'])) {
        return false;
    }
    return $user;
}

// UM-04 Update Own Account (name and email only).
function updateOwnAccount($pdo, $userId, $input) {
    $fullName = trim((string)($input['full_name'] ?? ''));
    $email = normalizeEmail($input['email'] ?? '');
    $errors = validateNameEmail($fullName, $email);
    $existing = $errors ? false : findUserByEmail($pdo, $email);
    if ($existing && (int)$existing['user_id'] !== (int)$userId) {
        $errors[] = 'An account with that email already exists.';
    }
    if ($errors) {
        throw new InvalidArgumentException(implode(' ', $errors));
    }
    $pdo->beginTransaction();
    try {
        updateUserNameEmail($pdo, $userId, $fullName, $email);
        updateStudentForUser($pdo, $userId, $fullName, $email);
        $pdo->commit();
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}

// UM-05 Change Password.
function changePassword($pdo, $userId, $input) {
    $current = (string)($input['current_password'] ?? '');
    $errors = [];
    $hash = findPasswordHash($pdo, $userId);
    if (!$hash || !password_verify($current, $hash)) {
        $errors[] = 'Current password is incorrect.';
    }
    $errors = array_merge($errors, validatePassword($input['new_password'] ?? '', $input['confirm_password'] ?? ''));
    if ($errors) {
        throw new InvalidArgumentException(implode(' ', $errors));
    }
    updateUserPassword($pdo, $userId, password_hash((string)$input['new_password'], PASSWORD_DEFAULT));
}

// True when this change would leave no Active Administrator.
function removesLastAdministrator($pdo, $user) {
    return $user['role'] === 'Administrator' && $user['status'] === 'Active'
        && countActiveAdministrators($pdo) <= 1;
}

// UM-06 Update User Role (administrator only, checked on the page).
function changeUserRole($pdo, $userId, $role) {
    if (!in_array($role, ['Student', 'Tutor', 'Administrator'], true)) {
        throw new InvalidArgumentException('Choose a valid role.');
    }
    $user = findUserById($pdo, $userId);
    if (!$user) {
        throw new InvalidArgumentException('User not found.');
    }
    if ($role !== 'Administrator' && removesLastAdministrator($pdo, $user)) {
        throw new InvalidArgumentException('The last active administrator cannot be changed.');
    }
    updateUserRole($pdo, $userId, $role);
}

// UM-07 Activate or Deactivate Account.
function changeUserStatus($pdo, $userId, $status) {
    if (!in_array($status, ['Active', 'Inactive'], true)) {
        throw new InvalidArgumentException('Choose a valid status.');
    }
    $user = findUserById($pdo, $userId);
    if (!$user) {
        throw new InvalidArgumentException('User not found.');
    }
    if ($status === 'Inactive' && removesLastAdministrator($pdo, $user)) {
        throw new InvalidArgumentException('The last active administrator cannot be deactivated.');
    }
    updateUserStatus($pdo, $userId, $status);
}

// UM-08 Delete User Account. Blocked when the user has tutoring requests,
// so related records are never removed silently.
function deleteUserAccount($pdo, $userId) {
    $user = findUserById($pdo, $userId);
    if (!$user) {
        throw new InvalidArgumentException('User not found.');
    }
    if (removesLastAdministrator($pdo, $user)) {
        throw new InvalidArgumentException('The last active administrator cannot be deleted.');
    }
    if (countRequestsForUser($pdo, $userId) > 0) {
        throw new InvalidArgumentException('This account has tutoring requests, so it cannot be deleted. Deactivate it instead.');
    }
    $pdo->beginTransaction();
    try {
        deleteStudentForUser($pdo, $userId);
        deleteUser($pdo, $userId);
        $pdo->commit();
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}
