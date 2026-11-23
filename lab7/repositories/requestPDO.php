<?php
// Lab 7: $userId is optional. When it is given, the query only returns or changes
// requests owned by that student user (ownership check in SQL).
function insertTutoringRequest($pdo, $studentId, $data) {
    $stmt=$pdo->prepare('INSERT INTO tutoring_requests(student_id,course,topic,preferred_date,preferred_time) VALUES(?,?,?,?,?)');
    $stmt->execute([$studentId,$data['course'],$data['topic'],$data['preferred_date'],$data['preferred_time']]);
    return (int)$pdo->lastInsertId();
}
function findRequest($pdo, $id, $userId = null) {
    $sql='SELECT r.*,s.student_name,s.student_email FROM tutoring_requests r JOIN students s ON r.student_id=s.student_id WHERE r.request_id=?';$args=[$id];
    if($userId!==null){$sql.=' AND s.user_id=?';$args[]=$userId;}
    $stmt=$pdo->prepare($sql);
    $stmt->execute($args);return $stmt->fetch();
}
function listRequests($pdo, $course = '', $status = '', $userId = null) {
    $sql='SELECT r.*,s.student_name FROM tutoring_requests r JOIN students s ON r.student_id=s.student_id WHERE 1=1';$args=[];
    if($course!==''){$sql.=' AND r.course = ?';$args[]=$course;}
    if($status!==''){$sql.=' AND r.status = ?';$args[]=$status;}
    if($userId!==null){$sql.=' AND s.user_id = ?';$args[]=$userId;}
    $sql.=' ORDER BY r.request_id DESC';$stmt=$pdo->prepare($sql);$stmt->execute($args);return $stmt->fetchAll();
}
function updateOpenRequest($pdo, $id, $data, $userId = null) {
    $sql="UPDATE tutoring_requests SET course=?,topic=?,preferred_date=?,preferred_time=? WHERE request_id=? AND status='Open'";
    $args=[$data['course'],$data['topic'],$data['preferred_date'],$data['preferred_time'],$id];
    if($userId!==null){$sql.=' AND student_id IN (SELECT student_id FROM students WHERE user_id=?)';$args[]=$userId;}
    $stmt=$pdo->prepare($sql);
    $stmt->execute($args);return $stmt->rowCount()===1;
}
function cancelOpenRequest($pdo, $id, $userId = null) {
    $sql="UPDATE tutoring_requests SET status='Cancelled' WHERE request_id=? AND status='Open'";$args=[$id];
    if($userId!==null){$sql.=' AND student_id IN (SELECT student_id FROM students WHERE user_id=?)';$args[]=$userId;}
    $stmt=$pdo->prepare($sql);
    $stmt->execute($args);return $stmt->rowCount()===1;
}
