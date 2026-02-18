<?php
session_start();
include '../includes/db.php';

// Production settings
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php_error.log');
error_reporting(E_ALL);

try {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request method.");
    }
    
    $user_id = $_SESSION['user_id'];
    $train_id = filter_input(INPUT_POST, 'train_id', FILTER_VALIDATE_INT);
    $class_id = filter_input(INPUT_POST, 'class_id', FILTER_VALIDATE_INT);
    $journey_date = $_POST['journey_date'] ?? '';
    $selected_seats = $_POST['seats'] ?? [];

    // Validation
    if (!$train_id || !$class_id || empty($journey_date) || empty($selected_seats)) {
        throw new Exception("Missing required booking information.");
    }
    
    if (!is_array($selected_seats)) {
        throw new Exception("Invalid seat data.");
    }

    // Sanitize seat names
    $sanitized_seats = [];
    foreach ($selected_seats as $seat) {
        if(preg_match('/^[A-Z][0-9]+$/', $seat)) {
            $sanitized_seats[] = $seat;
        }
    }
    
    if (empty($sanitized_seats)) {
        throw new Exception("No valid seats were selected.");
    }

    // Check if seats are already booked (CRITICAL ADDITION)
    $placeholders = rtrim(str_repeat('?,', count($sanitized_seats)), ',');
    $stmt = $conn->prepare("
        SELECT seat_number 
        FROM bookings 
        WHERE train_id = ? 
        AND class_id = ? 
        AND journey_date = ? 
        AND seat_number IN ($placeholders)
    ");
    
    $params = array_merge([$train_id, $class_id, $journey_date], $sanitized_seats);
    $stmt->execute($params);
    $already_booked = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (!empty($already_booked)) {
        throw new Exception("Some seats are already booked: " . implode(', ', $already_booked));
    }

    // Begin transaction
    $conn->beginTransaction();

    try {
        // Insert booking
        $stmt = $conn->prepare("
            INSERT INTO bookings (user_id, train_id, class_id, journey_date, seats_booked, payment_status) 
            VALUES (?, ?, ?, ?, ?, 'pending')
        ");
        $stmt->execute([
            $user_id, 
            $train_id, 
            $class_id, 
            $journey_date, 
            implode(',', $sanitized_seats)
        ]);

        $booking_id = $conn->lastInsertId();

        // Insert individual seat records (optional but recommended)
        foreach ($sanitized_seats as $seat) {
            $stmt = $conn->prepare("
                INSERT INTO tickets (user_id, class_id, seat_number, journey_date, from_station, to_station)
                SELECT ?, ?, ?, ?, t.from_station, t.to_station
                FROM trains t
                WHERE t.id = ?
            ");
            $stmt->execute([$user_id, $class_id, $seat, $journey_date, $train_id]);
        }

        $conn->commit();

        header("Location: payment.php?booking_id=" . urlencode($booking_id));
        exit();

    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }

} catch (PDOException $e) {
    error_log("Database Exception in confirm_booking.php: " . $e->getMessage());
    header("Location: dashboard.php?error=booking_failed");
    exit();
} catch (Exception $e) {
    error_log("General Exception in confirm_booking.php: " . $e->getMessage());
    header("Location: select_seat.php?" . http_build_query([
        'train_id' => $_POST['train_id'] ?? '',
        'class_id' => $_POST['class_id'] ?? '',
        'journey_date' => $_POST['journey_date'] ?? '',
        'error' => $e->getMessage()
    ]));
    exit();
}