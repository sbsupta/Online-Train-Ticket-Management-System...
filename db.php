<?php
$host = "sql103.infinityfree.com"; // Your MySQL Hostname
$dbname = "if0_39667088_if0_39667088_ottms"; // Your Database Name
$username = "if0_39667088"; // Your Database Username
$password = "sbsupta12345678"; // Your Database Password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}
