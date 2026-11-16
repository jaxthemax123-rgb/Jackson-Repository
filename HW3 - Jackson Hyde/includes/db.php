<?php
try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/../database/rewards.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA foreign_keys = ON');
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    exit('Database temporarily unavailable.');
}
