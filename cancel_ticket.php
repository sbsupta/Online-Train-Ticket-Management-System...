<?php
session_start();
include '../includes/db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if booking_id is provided
if (isset($_GET['booking_id'])) {
    $booking_id = (int)$_GET['booking_id'];
    $user_id = (int)$_SESSION['user_id'];

    // Prepare a statement to delete the booking only if it belongs to the current user
    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ? AND user_id = ?");
    
    if ($stmt->execute([$booking_id, $user_id])) {
        // Check if any row was actually deleted
        if ($stmt->rowCount() > 0) {
            header("Location: dashboard.php?cancel_success=1");
        } else {
            // No booking found with that ID for this user
            header("Location: dashboard.php?error=cancel_failed_not_found");
        }
    } else {
        // SQL execution failed
        header("Location: dashboard.php?error=cancel_failed_db_error");
    }
    exit();
}

// Redirect if booking_id is not set
header("Location: dashboard.php?error=invalid_request");
exit();
?>