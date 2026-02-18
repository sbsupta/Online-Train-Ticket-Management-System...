<?php
session_start();
require_once '../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get booking details
$booking_id = $_GET['id'];
$stmt = $conn->prepare("
    SELECT b.*, t.name as train_name, tc.class_name 
    FROM bookings b 
    JOIN trains t ON b.train_id = t.id 
    JOIN train_classes tc ON b.class_id = tc.id 
    WHERE b.id = ? AND b.user_id = ?
");
$stmt->execute([$booking_id, $_SESSION['user_id']]);
$booking = $stmt->fetch();

if (!$booking) {
    die("Booking not found.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation - OTTMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            font-family: 'Segoe UI', sans-serif;
        }
        .confirmation-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        .success-icon {
            color: #28a745;
            font-size: 48px;
        }
        .details-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #007bff;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="confirmation-container">
        <div class="text-center mb-4">
            <i class="fas fa-check-circle success-icon"></i>
            <h2 class="mt-2 text-success">Booking Confirmed!</h2>
        </div>
        
        <p class="text-center">Your booking has been confirmed. A confirmation SMS has been sent to your registered phone number.</p>
        
        <div class="details-card mt-4">
            <h4 class="mb-3"><i class="fas fa-ticket-alt"></i> Booking Details</h4>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Booking ID:</strong> <?php echo $booking['id']; ?></p>
                    <p><strong>Train:</strong> <?php echo $booking['train_name']; ?></p>
                    <p><strong>Class:</strong> <?php echo $booking['class_name']; ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Seats:</strong> <?php echo $booking['seats_booked']; ?></p>
                    <p><strong>Travel Date:</strong> <?php echo $booking['travel_date']; ?></p>
                    <p><strong>Status:</strong> <span class="badge bg-success">Confirmed</span></p>
                </div>
            </div>
        </div>
        
        <div class="mt-4 text-center">
            <a href="download_ticket.php?id=<?php echo $booking['id']; ?>" class="btn btn-primary">
                <i class="fas fa-download"></i> Download Ticket
            </a>
            <a href="dashboard.php" class="btn btn-outline-secondary">
                <i class="fas fa-home"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>