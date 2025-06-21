<?php
$host = 'sql313.infinityfree.com';
$db   = 'your_db_name'; // Replace with actual DB name
$user = 'your_db_user'; // Replace with actual username
$pass = 'your_db_password'; // Replace with actual password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
