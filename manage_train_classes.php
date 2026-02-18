<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['train_id'])) {
    die("Train not selected.");
}

$train_id = $_GET['train_id'];

// Add new class
if (isset($_POST['add'])) {
    $class_name = $_POST['class_name'];
    $fare = $_POST['fare'];
    $seat_rows = $_POST['seat_rows'];
    $seat_columns = $_POST['seat_columns'];

    $stmt = $conn->prepare("INSERT INTO train_classes (train_id, class_name, fare, seat_rows, seat_columns) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isdii", $train_id, $class_name, $fare, $seat_rows, $seat_columns);
    $stmt->execute();
    header("Location: manage_train_classes.php?train_id=$train_id");
    exit();
}

// Update class
if (isset($_POST['update'])) {
    $id = $_POST['class_id'];
    $class_name = $_POST['class_name'];
    $fare = $_POST['fare'];
    $seat_rows = $_POST['seat_rows'];
    $seat_columns = $_POST['seat_columns'];

    $stmt = $conn->prepare("UPDATE train_classes SET class_name=?, fare=?, seat_rows=?, seat_columns=? WHERE id=?");
    $stmt->bind_param("sdiii", $class_name, $fare, $seat_rows, $seat_columns, $id);
    $stmt->execute();
    header("Location: manage_train_classes.php?train_id=$train_id");
    exit();
}

// Delete class
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM train_classes WHERE id = $id");
    header("Location: manage_train_classes.php?train_id=$train_id");
    exit();
}

// Get train info
$train = $conn->query("SELECT * FROM trains WHERE id = $train_id")->fetch_assoc();

// Get classes for train
$classes = $conn->query("SELECT * FROM train_classes WHERE train_id = $train_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Classes for <?= htmlspecialchars($train['name']) ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h3>🚆 Manage Classes for <strong><?= htmlspecialchars($train['name']) ?></strong></h3>

    <!-- Add Form -->
    <form method="post" class="form-inline my-3">
        <input type="text" name="class_name" placeholder="Class Name" class="form-control mr-2" required>
        <input type="number" name="fare" placeholder="Fare (৳)" step="0.01" class="form-control mr-2" required>
        <input type="number" name="seat_rows" placeholder="Seat Rows" class="form-control mr-2" min="1" required>
        <input type="number" name="seat_columns" placeholder="Seat Columns" class="form-control mr-2" min="1" required>
        <button type="submit" name="add" class="btn btn-success">➕ Add Class</button>
    </form>

    <!-- Class Table -->
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Class Name</th>
                <th>Fare (৳)</th>
                <th>Seat Rows</th>
                <th>Seat Columns</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($cls = $classes->fetch_assoc()): ?>
            <tr>
                <form method="post">
                    <input type="hidden" name="class_id" value="<?= $cls['id'] ?>">
                    <td><input type="text" name="class_name" value="<?= htmlspecialchars($cls['class_name']) ?>" class="form-control" required></td>
                    <td><input type="number" name="fare" value="<?= $cls['fare'] ?>" step="0.01" class="form-control" required></td>
                    <td><input type="number" name="seat_rows" value="<?= $cls['seat_rows'] ?>" min="1" class="form-control" required></td>
                    <td><input type="number" name="seat_columns" value="<?= $cls['seat_columns'] ?>" min="1" class="form-control" required></td>
                    <td>
                        <button name="update" class="btn btn-sm btn-primary">Update</button>
                        <a href="?train_id=<?= $train_id ?>&delete=<?= $cls['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this class?')">Delete</a>
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <a href="manage_trains.php" class="btn btn-secondary">⬅️ Back to Train List</a>
</div>
</body>
</html>
