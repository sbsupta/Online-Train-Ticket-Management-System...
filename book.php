<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $train_class_id = $_POST['train_class_id'];
    $seat = $_POST['seat'];
    $travel_date = $_POST['travel_date'];

    $stmt = $conn->prepare("INSERT INTO bookings (user_id, train_class_id, seat, travel_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $user_id, $train_class_id, $seat, $travel_date);
    $stmt->execute();
    $stmt->close();

    header("Location: dashboard.php");
    exit();
} else {
    // Display seat selection form
    $train_class_id = $_GET['train_class_id'];
    $travel_date = $_GET['travel_date'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Seat</title>
</head>
<body>
    <h2>Book Seat</h2>
    <form method="post">
        <input type="hidden" name="train_class_id" value="<?= $train_class_id; ?>">
        <input type="hidden" name="travel_date" value="<?= $travel_date; ?>">
        Seat Number: <input type="text" name="seat" required><br>
        <input type="submit" value="Confirm Selection">
    </form>
</body>
</html>
<?php } ?>
