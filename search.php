<?php
include '../includes/db.php';

$trains = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_id = $_POST['class_id'];
    $travel_date = $_POST['travel_date'];
    $stmt = $conn->prepare("SELECT tc.id as train_class_id, t.train_name, c.class_name, tc.total_seats, tc.price
        FROM train_classes tc
        JOIN trains t ON tc.train_id = t.id
        JOIN classes c ON tc.class_id = c.id
        WHERE c.id = ?");
    $stmt->bind_param("i", $class_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $trains[] = $row;
    }
}

$classes = $conn->query("SELECT * FROM classes");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Trains</title>
</head>
<body>
    <h2>Search Train</h2>
    <form method="post">
        Class:
        <select name="class_id" required>
            <?php while ($row = $classes->fetch_assoc()): ?>
                <option value="<?= $row['id']; ?>"><?= $row['class_name']; ?></option>
            <?php endwhile; ?>
        </select><br>
        Date of Travel: <input type="date" name="travel_date" required><br>
        <input type="submit" value="Search">
    </form>
    <h3>Available Trains:</h3>
    <ul>
        <?php foreach ($trains as $train): ?>
            <li>
                <?= $train['train_name']; ?> | <?= $train['class_name']; ?> | Seats: <?= $train['total_seats']; ?> | Price: <?= $train['price']; ?> BDT
                <a href="book.php?train_class_id=<?= $train['train_class_id']; ?>&travel_date=<?= $travel_date; ?>">Book Now</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
