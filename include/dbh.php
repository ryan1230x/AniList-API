<?php
$host = "localhost";
$username = "u122331496_ryan_manga";
$password = "r.harp3r";
$db_name = "u122331496_manga_tracket";
$dsn = "mysql:host={$host};dbname={$db_name};charset=utf8mb4";
try {
    $conn = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo "connection failed: " .$e->getMessage();
}
?>