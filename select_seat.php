<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

if (!isset($_GET['train_id'], $_GET['class_id'], $_GET['journey_date'])) {
    die("Missing parameters.");
}

$train_id = (int)$_GET['train_id'];
$class_id = (int)$_GET['class_id'];
$journey_date = $_GET['journey_date'];

// Validate date is not in the past
if (strtotime($journey_date) < strtotime(date('Y-m-d'))) {
    die("Invalid date. Please select a current or future date.");
}

// Get train name
$train_q = $conn->prepare("SELECT name FROM trains WHERE id = ?");
$train_q->execute([$train_id]);
$train_name = $train_q->fetchColumn();

// Get class info
$class_q = $conn->prepare("SELECT class_name, seat_rows, seat_columns FROM train_classes WHERE id = ?");
$class_q->execute([$class_id]);
$class_res = $class_q->fetch(PDO::FETCH_ASSOC);

if (!$class_res) {
    die("Invalid class ID.");
}

$class_name = $class_res['class_name'];
$rows = (int)$class_res['seat_rows'];
$cols = (int)$class_res['seat_columns'];

// Get all booked seats for this train/class/date combination
$booked_q = $conn->prepare("SELECT seat_number FROM bookings WHERE train_id = ? AND class_id = ? AND journey_date = ?");
$booked_q->execute([$train_id, $class_id, $journey_date]);
$booked_seats = $booked_q->fetchAll(PDO::FETCH_COLUMN);

// Convert booked seats array to a format we can easily check
$booked_seats_map = array_flip($booked_seats);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Select Seats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" />
    <style>
        .seat-map {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin: 30px 0;
        }
        .seat {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.2s;
            border: 2px solid #dee2e6;
        }
        .seat:hover:not(.booked) {
            transform: scale(1.05);
        }
        .seat.selected {
            background-color: #198754;
            color: white;
            border-color: #198754;
        }
        .seat.booked {
            background-color: #dc3545;
            color: white;
            cursor: not-allowed;
            opacity: 0.7;
        }
        .seat.available {
            background-color: #f8f9fa;
        }
        .seat-label {
            font-size: 0.8rem;
            margin-bottom: 5px;
        }
    </style>
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">
                <i class="bi bi-ticket-perforated"></i> Select Your Seats
            </h2>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <h4 class="alert-heading">
                    <?= htmlspecialchars($train_name) ?> - <?= htmlspecialchars($class_name) ?>
                </h4>
                <p class="mb-0">
                    <i class="bi bi-calendar"></i> <?= htmlspecialchars($journey_date) ?>
                </p>
            </div>

            <form method="POST" action="confirm_booking.php" id="bookingForm">
                <input type="hidden" name="train_id" value="<?= $train_id ?>" />
                <input type="hidden" name="class_id" value="<?= $class_id ?>" />
                <input type="hidden" name="journey_date" value="<?= htmlspecialchars($journey_date) ?>" />

                <div class="seat-map">
                    <?php
                    for ($r = 0; $r < $rows; $r++) {
                        $row_letter = chr(65 + $r);
                        echo "<div class='w-100'></div>"; // line break for each row
                        for ($c = 1; $c <= $cols; $c++) {
                            $seat = $row_letter . $c;
                            $is_booked = isset($booked_seats_map[$seat]);
                            $class = $is_booked ? "seat booked" : "seat available";
                            $disabled = $is_booked ? "disabled" : "";
                            
                            echo '<div class="' . $class . '" data-seat="' . $seat . '" onclick="toggleSeat(this, ' . ($is_booked ? 'true' : 'false') . ')">' . $seat . '</div>';
                            echo '<input type="checkbox" class="d-none" name="seats[]" id="seat_' . $seat . '" value="' . $seat . '" ' . $disabled . ' />';
                        }
                    }
                    ?>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="dashboard_user.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Dashboard
                    </a>
                    <button type="submit" class="btn btn-primary" id="submit-btn" disabled>
                        <i class="bi bi-check-circle"></i> Confirm Selection
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Array to track selected seats
let selectedSeats = [];

function toggleSeat(el, isBooked) {
    if (isBooked) return; // Don't allow selection of booked seats
    
    const seat = el.dataset.seat;
    const cb = document.getElementById('seat_' + seat);
    
    if (el.classList.contains('selected')) {
        // Deselect seat
        el.classList.remove('selected');
        el.classList.add('available');
        cb.checked = false;
        selectedSeats = selectedSeats.filter(s => s !== seat);
    } else {
        // Select seat
        el.classList.remove('available');
        el.classList.add('selected');
        cb.checked = true;
        selectedSeats.push(seat);
    }
    
    // Update submit button state
    document.getElementById('submit-btn').disabled = selectedSeats.length === 0;
}

// Prevent form submission if any booked seats are selected
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    const invalidSeats = selectedSeats.filter(seat => {
        const seatEl = document.querySelector(`[data-seat="${seat}"]`);
        return seatEl.classList.contains('booked');
    });
    
    if (invalidSeats.length > 0) {
        e.preventDefault();
        alert('Error: One or more selected seats are already booked. Please try again.');
    }
});

// Initialize submit button state
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('submit-btn').disabled = true;
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>