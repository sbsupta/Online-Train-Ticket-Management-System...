<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['booking_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$booking_id = (int)$_GET['booking_id'];

try {
    // Fetch booking with train and class info
    $stmt = $conn->prepare("
        SELECT b.*, t.name AS train_name, c.class_name, c.fare
        FROM bookings b
        JOIN trains t ON b.train_id = t.id
        JOIN train_classes c ON b.class_id = c.id
        WHERE b.id = ? AND b.user_id = ?
    ");
    $stmt->execute([$booking_id, $user_id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$booking) {
        die("Booking not found or access denied.");
    }
    
    if ($booking['payment_status'] == 'paid') {
        header("Location: dashboard.php?error=already_paid");
        exit();
    }

    $seats = [];
    $total_price = 0;
    if (!empty($booking['seats_booked'])) {
        $seats = array_filter(explode(',', $booking['seats_booked']));
        $total_seats = count($seats);
        if ($total_seats > 0) {
            $total_price = $total_seats * $booking['fare'];
        } else {
            die("Error: No valid seats found in this booking. Please cancel and try again.");
        }
    } else {
        die("Error: Booking contains no seats. Please cancel and try again.");
    }

    // Handle payment confirmation form submit
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_payment'])) {
        $update = $conn->prepare("
            UPDATE bookings
            SET payment_status='paid', payment_date=NOW()
            WHERE id = ? AND user_id = ?
        ");
        if ($update->execute([$booking_id, $user_id])) {
            // *** FIX: Changed redirect to dashboard.php ***
            echo "<script>
                    alert('Payment confirmed! Thank you. You will now be redirected.');
                    window.location.href = 'dashboard.php?payment_success=1';
                  </script>";
            exit();
        } else {
            echo "<script>alert('Failed to update payment status. Please contact support.');</script>";
        }
    }
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complete Payment - OTTMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .container { max-width: 600px; background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); padding: 30px; margin-top: 40px; }
        .payment-method { cursor: pointer; transition: all 0.2s ease-in-out; }
        .payment-method:hover { background-color: #e9ecef; transform: scale(1.02); }
        .payment-details { transition: all 0.3s ease; background-color: #f8f9fa; }
        .btn-success { padding: 12px 30px; font-weight: 500; font-size: 1.1rem; }
        .card-title { font-weight: bold; }
        .list-group-item img { width: 40px; margin-right: 15px; }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="mb-4 text-center">
        <h3 class="mb-3 fw-bold">Payment for Booking #<?= htmlspecialchars($booking_id) ?></h3>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title text-primary">Booking Summary</h5>
            <p class="mb-1"><strong>Train:</strong> <?= htmlspecialchars($booking['train_name']) ?></p>
            <p class="mb-1"><strong>Class:</strong> <?= htmlspecialchars($booking['class_name']) ?></p>
            <p class="mb-1"><strong>Journey Date:</strong> <?= htmlspecialchars($booking['journey_date']) ?></p>
            <p class="mb-1"><strong>Seats:</strong> <?= htmlspecialchars(implode(', ', $seats)) ?></p>
            <hr>
            <p class="mb-0 fs-5 fw-bold"><strong>Total Amount:</strong> ৳<?= htmlspecialchars(number_format($total_price, 2)) ?></p>
        </div>
    </div>

    <div>
        <h4 class="mb-3 text-center">Choose Payment Method</h4>
        <div class="list-group mb-3">
            <a href="#" class="list-group-item list-group-item-action payment-method" onclick="showPayment('bkash')">
                <div class="d-flex align-items-center"><i class="fa-solid fa-wallet fa-2x me-3 text-danger"></i> <span>bKash</span></div>
            </a>
            <a href="#" class="list-group-item list-group-item-action payment-method" onclick="showPayment('nagad')">
                <div class="d-flex align-items-center"><i class="fa-solid fa-money-bill-wave fa-2x me-3 text-warning"></i> <span>Nagad</span></div>
            </a>
            <a href="#" class="list-group-item list-group-item-action payment-method" onclick="showPayment('upay')">
                <div class="d-flex align-items-center"><i class="fa-solid fa-credit-card fa-2x me-3 text-info"></i> <span>Upay</span></div>
            </a>
        </div>

        <div id="payment-details" class="mt-3 p-4 border rounded payment-details" style="display:none;"></div>

        <form method="post" class="mt-4 text-center" id="confirm-payment-form" style="display:none;">
            <input type="hidden" name="confirm_payment" value="1">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-check-circle me-2"></i> Confirm Payment
            </button>
        </form>
    </div>
</div>

<script>
function showPayment(method) {
    const details = document.getElementById('payment-details');
    const form = document.getElementById('confirm-payment-form');
    let content = '';
    const bookingId = <?= json_encode($booking_id) ?>;
    const totalPrice = <?= json_encode(number_format($total_price, 2)) ?>;
    
    const instructions = {
        bkash: {
            title: "bKash Payment Instructions",
            number: "01700000000",
            icon: "fa-solid fa-wallet text-danger"
        },
        nagad: {
            title: "Nagad Payment Instructions",
            number: "01800000000",
            icon: "fa-solid fa-money-bill-wave text-warning"
        },
        upay: {
            title: "Upay Payment Instructions",
            number: "01900000000",
            icon: "fa-solid fa-credit-card text-info"
        }
    };
    
    const selected = instructions[method];
    if (selected) {
        content = `<h5 class="mb-3"><i class="${selected.icon} me-2"></i>${selected.title}</h5>
            <ol class="ps-4">
                <li>Open the ${method} app and select "Send Money".</li>
                <li>Enter merchant number: <strong>${selected.number}</strong></li>
                <li>Enter amount: <strong>৳${totalPrice}</strong></li>
                <li>Use reference: <strong>OTTMS-${bookingId}</strong></li>
                <li>Complete the transaction with your PIN.</li>
            </ol>
            <p class="mb-0 text-muted small">After completing the payment, click the "Confirm Payment" button below.</p>`;
    }
    
    details.innerHTML = content;
    details.style.display = 'block';
    form.style.display = 'block';
    
    details.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
</script>

</body>
</html>