<?php
function findStudentByEmail($pdo, $email) {
    $stmt = $pdo->prepare('SELECT student_id, student_name, student_email FROM students WHERE student_email = ?');
    $stmt->execute([$email]); return $stmt->fetch();
}
function insertStudentIfAbsent($pdo, $name, $email) {
    $stmt = $pdo->prepare('INSERT OR IGNORE INTO students(student_name,student_email) VALUES(?,?)');
    $stmt->execute([$name,$email]);
}
// Lab 7: students linked to user accounts through students.user_id.
function findStudentByUserId($pdo, $userId) {
    $stmt = $pdo->prepare('SELECT student_id, student_name, student_email, user_id FROM students WHERE user_id = ?');
    $stmt->execute([$userId]); return $stmt->fetch();
}
function insertStudentForUser($pdo, $userId, $name, $email) {
    $stmt = $pdo->prepare('INSERT INTO students(student_name,student_email,user_id) VALUES(?,?,?)');
    $stmt->execute([$name,$email,$userId]);
}
function linkStudentToUser($pdo, $studentId, $userId, $name) {
    $stmt = $pdo->prepare('UPDATE students SET user_id = ?, student_name = ? WHERE student_id = ? AND user_id IS NULL');
    $stmt->execute([$userId,$name,$studentId]);
}
function updateStudentForUser($pdo, $userId, $name, $email) {
    $stmt = $pdo->prepare('UPDATE students SET student_name = ?, student_email = ? WHERE user_id = ?');
    $stmt->execute([$name,$email,$userId]);
}
function deleteStudentForUser($pdo, $userId) {
    $stmt = $pdo->prepare('DELETE FROM students WHERE user_id = ?');
    $stmt->execute([$userId]);
}
