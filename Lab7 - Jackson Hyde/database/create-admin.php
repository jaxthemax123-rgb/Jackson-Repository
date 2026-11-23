<?php
// Usage: php create-admin.php "Full Name" email@example.com "Password"
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../services/userService.php';

if ($argc !== 4) {
    echo 'Usage: php create-admin.php "Full Name" email "Password"' . PHP_EOL;
    exit(1);
}

try {
    $userId = createAdministrator(openDatabase(), $argv[1], $argv[2], $argv[3]);
    echo 'Administrator account created. User ID: ' . $userId . PHP_EOL;
} catch (InvalidArgumentException $e) {
    echo 'Administrator account not created: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}
