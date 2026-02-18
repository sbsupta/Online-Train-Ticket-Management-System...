<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) die("Access denied");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $conn->prepare("INSERT INTO feedbacks (user_id, feedback, rating) VALUES (?,?,?)");
    if ($stmt->execute([
        $_SESSION['user_id'],
        $_POST['feedback'],
        $_POST['rating'] ?? 5
    ])) {
        header("Location: dashboard.php?feedback_success=1");
    } else {
        header("Location: dashboard.php?error=feedback_failed");
    }
    exit();
}
header("Location: dashboard.php");
?>