<?php
// Lab 7: SQL for the users table.
function insertUser($pdo, $fullName, $email, $passwordHash, $role) {
    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password_hash, role, status) VALUES (?, ?, ?, ?, 'Active')");
    $stmt->execute([$fullName, $email, $passwordHash, $role]);
    return (int)$pdo->lastInsertId();
}
function findUserByEmail($pdo, $email) {
    $stmt = $pdo->prepare('SELECT user_id, full_name, email, password_hash, role, status FROM users WHERE email = ?');
    $stmt->execute([$email]);
    return $stmt->fetch();
}
function findUserById($pdo, $userId) {
    $stmt = $pdo->prepare('SELECT user_id, full_name, email, role, status, created_at FROM users WHERE user_id = ?');
    $stmt->execute([$userId]);
    return $stmt->fetch();
}
function findPasswordHash($pdo, $userId) {
    $stmt = $pdo->prepare('SELECT password_hash FROM users WHERE user_id = ?');
    $stmt->execute([$userId]);
    return $stmt->fetchColumn();
}
function searchUsers($pdo, $search = '', $role = '', $status = '') {
    $sql = 'SELECT user_id, full_name, email, role, status FROM users WHERE 1=1';
    $args = [];
    if ($search !== '') {
        $sql .= ' AND (full_name LIKE ? OR email LIKE ?)';
        $args[] = '%' . $search . '%';
        $args[] = '%' . $search . '%';
    }
    if ($role !== '') {
        $sql .= ' AND role = ?';
        $args[] = $role;
    }
    if ($status !== '') {
        $sql .= ' AND status = ?';
        $args[] = $status;
    }
    $sql .= ' ORDER BY full_name';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($args);
    return $stmt->fetchAll();
}
function updateUserNameEmail($pdo, $userId, $fullName, $email) {
    $stmt = $pdo->prepare('UPDATE users SET full_name = ?, email = ? WHERE user_id = ?');
    $stmt->execute([$fullName, $email, $userId]);
}
function updateUserPassword($pdo, $userId, $passwordHash) {
    $stmt = $pdo->prepare('UPDATE users SET password_hash = ? WHERE user_id = ?');
    $stmt->execute([$passwordHash, $userId]);
}
function updateUserRole($pdo, $userId, $role) {
    $stmt = $pdo->prepare('UPDATE users SET role = ? WHERE user_id = ?');
    $stmt->execute([$role, $userId]);
}
function updateUserStatus($pdo, $userId, $status) {
    $stmt = $pdo->prepare('UPDATE users SET status = ? WHERE user_id = ?');
    $stmt->execute([$status, $userId]);
}
function deleteUser($pdo, $userId) {
    $stmt = $pdo->prepare('DELETE FROM users WHERE user_id = ?');
    $stmt->execute([$userId]);
}
function countActiveAdministrators($pdo) {
    return (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'Administrator' AND status = 'Active'")->fetchColumn();
}
function countRequestsForUser($pdo, $userId) {
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM tutoring_requests r JOIN students s ON r.student_id = s.student_id WHERE s.user_id = ?');
    $stmt->execute([$userId]);
    return (int)$stmt->fetchColumn();
}
