<?php
$host = 'sql211.infinityfree.com';
$dbname = 'if0_39287742_if0_39287742_payroll';
$username = 'if0_39287742';
$password = 'Harshi_Pugal'; // Or your vPanel password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
