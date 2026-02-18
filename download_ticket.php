<?php
session_start();

// --- 1. Load Libraries & Database ---
require_once __DIR__.'/fpdf/fpdf.php';
require_once __DIR__.'/phpqrcode/qrlib.php';
require_once __DIR__.'/../includes/db.php';

// IMPORTANT: Any output (even a single space or invisible BOM character)
// from the files above will corrupt the PDF. Ensure they are saved as "UTF-8".

try {
    // --- 2. Verify Session and Input ---
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Access denied. Please login first.');
    }

    if (empty($_GET['booking_id'])) {
        throw new Exception('Invalid request: Booking ID is required.');
    }
    $booking_id = (int)$_GET['booking_id'];

    // --- 3. Fetch Booking Data ---
    $stmt = $conn->prepare("
        SELECT b.id, b.journey_date, b.seats_booked, b.payment_status,
               u.name AS user_name,
               t.name AS train_name, t.from_station, t.to_station, t.departure_time,
               c.class_name, c.fare
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN trains t ON b.train_id = t.id
        JOIN train_classes c ON b.class_id = c.id
        WHERE b.id = ? AND b.user_id = ?
    ");
    $stmt->execute([$booking_id, $_SESSION['user_id']]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        throw new Exception('Booking not found or you do not have permission to view it.');
    }
    if ($data['payment_status'] !== 'paid') {
        throw new Exception('Cannot download ticket until payment is confirmed.');
    }

    // --- 4. Calculate Fare and Seat Details ---
    $seats = array_filter(explode(',', $data['seats_booked']));
    $seat_count = count($seats);
    $total_fare = $seat_count * $data['fare'];

    // --- 5. Generate QR Code ---
    $qr_temp_dir = __DIR__ . '/temp/';
    if (!is_dir($qr_temp_dir) || !is_writable($qr_temp_dir)) {
        throw new Exception("Error: The temporary directory '{$qr_temp_dir}' is missing or not writable.");
    }

    $qr_file = $qr_temp_dir . 'qr_booking_' . $booking_id . '.png';
    $qr_data = "Booking Ref: OTTMS-{$booking_id}\nPassenger: {$data['user_name']}\nTrain: {$data['train_name']}\nRoute: {$data['from_station']} to {$data['to_station']}\nDate: {$data['journey_date']}";
    QRcode::png($qr_data, $qr_file, QR_ECLEVEL_L, 4);

    if (!file_exists($qr_file)) {
        throw new Exception('Failed to generate the QR code image.');
    }

    // --- 6. Generate PDF Content (without outputting it yet) ---
    $pdf = new FPDF('P', 'mm', 'A5');
    $pdf->AddPage();
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetFont('Arial', 'B', 18);
    $pdf->Cell(0, 12, 'Online Train Ticket', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 6, 'Booking Ref: OTTMS-' . str_pad($booking_id, 6, '0', STR_PAD_LEFT), 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(90, 8, "Passenger: " . htmlspecialchars($data['user_name']), 0, 0, 'L');
    $pdf->Image($qr_file, 105, 30, 35, 35, 'PNG');
    $pdf->Ln(8);
    $pdf->SetFont('Arial', '', 11);
    $field_width = 35;
    $details = [
        'Train Name' => $data['train_name'],
        'Route' => "{$data['from_station']} to {$data['to_station']}",
        'Journey Date' => date("d M Y", strtotime($data['journey_date'])),
        'Departure Time' => date("h:i A", strtotime($data['departure_time'])),
        'Class' => $data['class_name'],
        'Seats' => $data['seats_booked'],
        'Total Fare' => 'BDT ' . number_format($total_fare, 2)
    ];
    foreach ($details as $label => $value) {
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell($field_width, 7, $label . ':', 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 7, htmlspecialchars($value), 0, 1);
    }
    $pdf->Ln(15);
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->Cell(0, 6, "This is a computer-generated ticket.--by_sb", 0, 1, 'C');

    // Get the PDF content as a string
    $pdf_content = $pdf->Output('S');
    
    // Clean up the QR file
    unlink($qr_file);

    // --- 7. Manually Set Headers and Output PDF ---
    // This is the most robust way to send a file for download.
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="OTTMS_Ticket_'.$booking_id.'.pdf"');
    header('Content-Length: ' . strlen($pdf_content));
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');

    echo $pdf_content;
    exit();

} catch (Exception $e) {
    // If an error happens, display it as plain text
    header('Content-Type: text/plain');
    die('Error: ' . $e->getMessage());
}
?>