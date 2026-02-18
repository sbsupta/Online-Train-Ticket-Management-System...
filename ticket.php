<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['booking_id'])) {
    $stmt = $conn->prepare("DELETE FROM bookings WHERE id=? AND user_id=?");
    if ($stmt->execute([$_GET['booking_id'], $_SESSION['user_id']])) {
        header("Location: dashboard.php?cancel_success=1");
    } else {
        header("Location: dashboard.php?error=cancel_failed");
    }
    exit();
}
header("Location: dashboard.php?error=invalid_request");
?>