<?php
// Lab 7: user management tests (UM-01 to UM-09). Uses an in-memory database, never tutoring.db.
require_once dirname(__DIR__).'/services/userService.php';
require_once dirname(__DIR__).'/services/requestService.php';
$pdo=new PDO('sqlite::memory:');$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);$pdo->exec('PRAGMA foreign_keys=ON');
$pdo->exec(file_get_contents(dirname(__DIR__).'/database/setup-database.sql'));
$pdo->exec(file_get_contents(dirname(__DIR__).'/database/upgrade-to-user-management.sql'));
$failed=0;function check($ok, $label){global $failed;echo ($ok?'PASS ':'FAIL ').$label.PHP_EOL;if(!$ok)$failed++;}
function rejected($fn){try{$fn();return false;}catch(InvalidArgumentException $e){return true;}}

$student=['full_name'=>'Test Student','email'=>'Student1@Example.com','password'=>'StudentPass123','confirm_password'=>'StudentPass123','role'=>'Student'];
$sid=registerUser($pdo,$student);$u=findUserById($pdo,$sid);
check($u['role']==='Student'&&$u['status']==='Active','UM-01 student registration creates Active user');
check($u['email']==='student1@example.com','UM-01 email stored in lowercase');
$hash=findPasswordHash($pdo,$sid);check($hash!=='StudentPass123'&&password_verify('StudentPass123',$hash),'UM-01 password stored as hash');
check(findStudentByUserId($pdo,$sid)!==false,'UM-01 student registration creates linked students row');
check(rejected(function()use($pdo,$student){registerUser($pdo,$student);}),'UM-01 duplicate email rejected');
$admin=$student;$admin['email']='sneaky@example.com';$admin['role']='Administrator';
check(rejected(function()use($pdo,$admin){registerUser($pdo,$admin);}),'UM-01 public registration cannot create Administrator');
$bad=$student;$bad['email']='mismatch@example.com';$bad['confirm_password']='Different12345';
check(rejected(function()use($pdo,$bad){registerUser($pdo,$bad);}),'UM-01 password mismatch rejected');
$bad['confirm_password']=$bad['password']='short';
check(rejected(function()use($pdo,$bad){registerUser($pdo,$bad);}),'UM-01 password under 12 characters rejected');
$tutor=['full_name'=>'Test Tutor','email'=>'tutor1@example.com','password'=>'TutorPass1234','confirm_password'=>'TutorPass1234','role'=>'Tutor'];
$tid=registerUser($pdo,$tutor);check(findUserById($pdo,$tid)['role']==='Tutor'&&findStudentByUserId($pdo,$tid)===false,'UM-01 tutor registration creates user with no students row');

check(authenticateUser($pdo,'student1@example.com','StudentPass123')!==false,'UM-09 valid login accepted');
check(authenticateUser($pdo,'student1@example.com','WrongPassword1')===false,'UM-09 wrong password rejected');
check(authenticateUser($pdo,'nobody@example.com','StudentPass123')===false,'UM-09 unknown email rejected');

check(findUserById($pdo,$sid)['full_name']==='Test Student','UM-02 own profile retrieved by user_id');
updateOwnAccount($pdo,$sid,['full_name'=>'Test Student Two','email'=>'student1@example.com']);
check(findUserById($pdo,$sid)['full_name']==='Test Student Two'&&findStudentByUserId($pdo,$sid)['student_name']==='Test Student Two','UM-04 name update saved to users and students');
check(rejected(function()use($pdo,$sid){updateOwnAccount($pdo,$sid,['full_name'=>'X','email'=>'tutor1@example.com']);}),'UM-04 duplicate email rejected');
updateOwnAccount($pdo,$sid,['full_name'=>'Test Student Two','email'=>'student1@example.com','role'=>'Administrator']);
check(findUserById($pdo,$sid)['role']==='Student','UM-04 profile edit cannot change role');

check(rejected(function()use($pdo,$sid){changePassword($pdo,$sid,['current_password'=>'WrongPassword1','new_password'=>'NewStudentPass1','confirm_password'=>'NewStudentPass1']);}),'UM-05 wrong current password rejected');
changePassword($pdo,$sid,['current_password'=>'StudentPass123','new_password'=>'NewStudentPass1','confirm_password'=>'NewStudentPass1']);
check(authenticateUser($pdo,'student1@example.com','NewStudentPass1')!==false&&authenticateUser($pdo,'student1@example.com','StudentPass123')===false,'UM-05 new password works and old one fails');

$aid=createAdministrator($pdo,'Test Administrator','admin@example.com','AdminPass12345');
check(findUserById($pdo,$aid)['role']==='Administrator','Admin script creates Administrator');
changeUserRole($pdo,$tid,'Student');check(findUserById($pdo,$tid)['role']==='Student','UM-06 administrator changes role');
check(rejected(function()use($pdo,$tid){changeUserRole($pdo,$tid,'Owner');}),'UM-06 invalid role rejected');
check(rejected(function()use($pdo,$aid){changeUserRole($pdo,$aid,'Student');}),'UM-06 last active administrator role protected');

changeUserStatus($pdo,$tid,'Inactive');
check(authenticateUser($pdo,'tutor1@example.com','TutorPass1234')===false,'UM-07 inactive user cannot log in');
changeUserStatus($pdo,$tid,'Active');
check(authenticateUser($pdo,'tutor1@example.com','TutorPass1234')!==false,'UM-07 reactivated user can log in');
check(rejected(function()use($pdo,$aid){changeUserStatus($pdo,$aid,'Inactive');}),'UM-07 last active administrator cannot be deactivated');

$req=['course'=>'IS 6465','reason'=>'Help with sessions and login.','topics'=>['Exam review'],'preferreddate'=>(new DateTimeImmutable('+5 days'))->format('Y-m-d'),'preferredtime'=>'Evening','student_name'=>'Test Student Two','student_email'=>'student1@example.com'];
submitRequest($pdo,$req,$sid);
check(rejected(function()use($pdo,$sid){deleteUserAccount($pdo,$sid);})&&findUserById($pdo,$sid)!==false,'UM-08 delete blocked by related tutoring requests');
deleteUserAccount($pdo,$tid);check(findUserById($pdo,$tid)===false,'UM-08 account with no requests deleted');
check(rejected(function()use($pdo,$aid){deleteUserAccount($pdo,$aid);}),'UM-08 last active administrator cannot be deleted');

echo $failed?'FAILURES: '.$failed.PHP_EOL:'All user management checks passed'.PHP_EOL;exit($failed?1:0);
