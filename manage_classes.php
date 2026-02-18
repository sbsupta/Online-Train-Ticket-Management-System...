<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Add class
if (isset($_POST['add'])) {
    $train_id = $_POST['train_id'];
    $class_name = $_POST['class_name'];
    $fare = $_POST['fare'];

    $stmt = $conn->prepare("INSERT INTO train_classes (train_id, class_name, fare) VALUES (?, ?, ?)");
    $stmt->bind_param("isd", $train_id, $class_name, $fare);
    $stmt->execute();
    header('Location: manage_classes.php');
    exit();
}

// Update class
if (isset($_POST['update'])) {
    $id = $_POST['class_id'];
    $class_name = $_POST['class_name'];
    $fare = $_POST['fare'];

    $stmt = $conn->prepare("UPDATE train_classes SET class_name=?, fare=? WHERE id=?");
    $stmt->bind_param("sdi", $class_name, $fare, $id);
    $stmt->execute();
    header('Location: manage_classes.php');
    exit();
}

// Delete class
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM train_classes WHERE id = $id");
    header('Location: manage_classes.php');
    exit();
}

// Get all trains
$trains = $conn->query("SELECT * FROM trains");

// Get all train classes with train info
$classes = $conn->query("
    SELECT tc.id, tc.class_name, tc.fare, t.name AS train_name
    FROM train_classes tc
    JOIN trains t ON tc.train_id = t.id
    ORDER BY t.name ASC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Train Classes - Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3>🚃 Manage Train Classes</h3>

    <!-- Add Class Form -->
    <div class="card my-4">
        <div class="card-header">➕ Add New Class</div>
        <div class="card-body">
            <form method="post">
                <div class="form-row">
                    <div class="col">
                        <select name="train_id" class="form-control" required>
                            <option value="">Select Train</option>
                            <?php while($train = $trains->fetch_assoc()): ?>
                                <option value="<?= $train['id'] ?>"><?= $train['name'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col">
                        <input type="text" name="class_name" class="form-control" placeholder="Class Name (e.g. AC, Sleeper)" required>
                    </div>
                    <div class="col">
                        <input type="number" name="fare" class="form-control" placeholder="Fare (BDT)" step="0.01" required>
                    </div>
                    <div class="col">
                        <button type="submit" name="add" class="btn btn-primary btn-block">Add Class</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Class Table -->
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Train</th>
                <th>Class</th>
                <th>Fare (BDT)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $classes->fetch_assoc()): ?>
                <tr>
                    <form method="post">
                        <input type="hidden" name="class_id" value="<?= $row['id'] ?>">
                        <td><?= htmlspecialchars($row['train_name']) ?></td>
                        <td><input type="text" name="class_name" value="<?= htmlspecialchars($row['class_name']) ?>" class="form-control" required></td>
                        <td><input type="number" name="fare" value="<?= $row['fare'] ?>" step="0.01" class="form-control" required></td>
                        <td>
                            <button type="submit" name="update" class="btn btn-sm btn-success">Update</button>
                            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this class?');" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </form>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-secondary mt-3">⬅️ Back to Admin Dashboard</a>
</div>
</body>
</html>
