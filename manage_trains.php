<?php
session_start();
require_once '../includes/db.php';

// Authentication check
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Handle all actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Add/Update Train
        if (isset($_POST['train_action'])) {
            $id = $_POST['train_id'] ?? null;
            $name = trim($_POST['name']);
            $from = trim($_POST['from_station']);
            $to = trim($_POST['to_station']);
            $time = $_POST['departure_time'];
            $seats = (int)$_POST['seats'];

            if ($_POST['train_action'] === 'add') {
                $stmt = $conn->prepare("INSERT INTO trains (name, from_station, to_station, departure_time, seats) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$name, $from, $to, $time, $seats]);
                $_SESSION['message'] = 'Train added successfully';
            } else {
                $stmt = $conn->prepare("UPDATE trains SET name=?, from_station=?, to_station=?, departure_time=?, seats=? WHERE id=?");
                $stmt->execute([$name, $from, $to, $time, $seats, $id]);
                $_SESSION['message'] = 'Train updated successfully';
            }
        }
        
        // Add/Update Class
        if (isset($_POST['class_action'])) {
            $id = $_POST['class_id'] ?? null;
            $train_id = (int)$_POST['train_id'];
            $class_name = trim($_POST['class_name']);
            $fare = (float)$_POST['fare'];
            $seat_rows = isset($_POST['seat_rows']) ? (int)$_POST['seat_rows'] : 0;
            $seat_columns = isset($_POST['seat_columns']) ? (int)$_POST['seat_columns'] : 0;

            if ($_POST['class_action'] === 'add') {
                $stmt = $conn->prepare("INSERT INTO train_classes (train_id, class_name, fare, seat_rows, seat_columns) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$train_id, $class_name, $fare, $seat_rows, $seat_columns]);
                $_SESSION['message'] = 'Class added successfully';
            } else {
                $stmt = $conn->prepare("UPDATE train_classes SET class_name=?, fare=?, seat_rows=?, seat_columns=? WHERE id=?");
                $stmt->execute([$class_name, $fare, $seat_rows, $seat_columns, $id]);
                $_SESSION['message'] = 'Class updated successfully';
            }
        }
        
        header("Location: manage_trains.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Database error: ' . $e->getMessage();
    }
}

// Handle delete actions
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $type = $_GET['type'] ?? 'train';
    
    try {
        if ($type === 'train') {
            // Delete train and its classes
            $conn->beginTransaction();
            $conn->prepare("DELETE FROM train_classes WHERE train_id = ?")->execute([$id]);
            $conn->prepare("DELETE FROM trains WHERE id = ?")->execute([$id]);
            $conn->commit();
            $_SESSION['message'] = 'Train and its classes deleted successfully';
        } else {
            // Delete class only
            $conn->prepare("DELETE FROM train_classes WHERE id = ?")->execute([$id]);
            $_SESSION['message'] = 'Class deleted successfully';
        }
        header("Location: manage_trains.php");
        exit();
    } catch (PDOException $e) {
        $conn->rollBack();
        $_SESSION['error'] = 'Error deleting: ' . $e->getMessage();
    }
}

// Get all data
$trains = $conn->query("SELECT * FROM trains ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$classes = $conn->query("
    SELECT tc.*, t.name AS train_name 
    FROM train_classes tc
    JOIN trains t ON tc.train_id = t.id
    ORDER BY t.name, tc.class_name
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Train Management | OTTMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #34495e;
            --accent: #3498db;
            --light: #ecf0f1;
            --dark: #2c3e50;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border: none;
        }
        
        .card-header {
            background-color: var(--primary);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            font-weight: 600;
        }
        
        .table th {
            background-color: var(--secondary);
            color: white;
        }
        
        .btn-primary {
            background-color: var(--accent);
            border-color: var(--accent);
        }
        
        .btn-danger {
            background-color: #e74c3c;
            border-color: #e74c3c;
        }
        
        .btn-success {
            background-color: #2ecc71;
            border-color: #2ecc71;
        }
        
        .nav-tabs .nav-link.active {
            font-weight: bold;
            border-bottom: 3px solid var(--accent);
        }
        
        .train-icon {
            color: var(--accent);
            margin-right: 8px;
        }
        
        .action-btn {
            min-width: 80px;
            margin: 2px;
        }
        
        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <!-- Messages -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= htmlspecialchars($_SESSION['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-train train-icon"></i> Train Management</h2>
            <a href="dashboard.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Tab Navigation -->
        <ul class="nav nav-tabs mb-4" id="trainTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="trains-tab" data-bs-toggle="tab" data-bs-target="#trains" type="button" role="tab">
                    <i class="fas fa-train"></i> Trains
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="classes-tab" data-bs-toggle="tab" data-bs-target="#classes" type="button" role="tab">
                    <i class="fas fa-chair"></i> Classes
                </button>
            </li>
        </ul>

        <div class="tab-content" id="trainTabsContent">
            <!-- Trains Tab -->
            <div class="tab-pane fade show active" id="trains" role="tabpanel">
                <!-- Add Train Form -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-plus-circle"></i> Add New Train
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <input type="hidden" name="train_action" value="add">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" name="name" class="form-control" placeholder="Train Name" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="from_station" class="form-control" placeholder="From Station" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="to_station" class="form-control" placeholder="To Station" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="time" name="departure_time" class="form-control" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="seats" class="form-control" placeholder="Total Seats" min="1" required>
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-save"></i> Add
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Trains List -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-list"></i> All Trains
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Train</th>
                                        <th>Route</th>
                                        <th>Departure</th>
                                        <th>Seats</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($trains)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4">No trains found</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($trains as $train): ?>
                                            <tr>
                                                <form method="post">
                                                    <input type="hidden" name="train_action" value="update">
                                                    <input type="hidden" name="train_id" value="<?= $train['id'] ?>">
                                                    <td>
                                                        <input type="text" name="name" value="<?= htmlspecialchars($train['name']) ?>" class="form-control" required>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <input type="text" name="from_station" value="<?= htmlspecialchars($train['from_station']) ?>" class="form-control" required>
                                                            <span class="input-group-text bg-light">→</span>
                                                            <input type="text" name="to_station" value="<?= htmlspecialchars($train['to_station']) ?>" class="form-control" required>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="time" name="departure_time" value="<?= $train['departure_time'] ?>" class="form-control" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="seats" value="<?= $train['seats'] ?>" class="form-control" min="1" required>
                                                    </td>
                                                    <td class="text-nowrap">
                                                        <button type="submit" class="btn btn-sm btn-success action-btn">
                                                            <i class="fas fa-save"></i> Save
                                                        </button>
                                                        <a href="?delete=<?= $train['id'] ?>&type=train" class="btn btn-sm btn-danger action-btn" 
                                                           onclick="return confirm('Delete this train and all its classes?')">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </a>
                                                        <a href="manage_trains.php?train_id=<?= $train['id'] ?>#classes" class="btn btn-sm btn-info action-btn">
                                                            <i class="fas fa-chair"></i> Classes
                                                        </a>
                                                    </td>
                                                </form>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Classes Tab -->
            <div class="tab-pane fade" id="classes" role="tabpanel">
                <!-- Add Class Form -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-plus-circle"></i> Add New Class
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <input type="hidden" name="class_action" value="add">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <select name="train_id" class="form-select" required>
                                        <option value="">Select Train</option>
                                        <?php foreach ($trains as $train): ?>
                                            <option value="<?= $train['id'] ?>"><?= htmlspecialchars($train['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="class_name" class="form-control" placeholder="Class Name" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="fare" class="form-control" placeholder="Fare (৳)" step="0.01" min="0" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="seat_rows" class="form-control" placeholder="Seat Rows" min="1">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="seat_columns" class="form-control" placeholder="Seat Columns" min="1">
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-save"></i> Add
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Classes List -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-list"></i> All Classes
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Train</th>
                                        <th>Class</th>
                                        <th>Fare</th>
                                        <th>Seat Layout</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($classes)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4">No classes found</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($classes as $class): ?>
                                            <tr>
                                                <form method="post">
                                                    <input type="hidden" name="class_action" value="update">
                                                    <input type="hidden" name="class_id" value="<?= $class['id'] ?>">
                                                    <input type="hidden" name="train_id" value="<?= $class['train_id'] ?>">
                                                    <td><?= htmlspecialchars($class['train_name']) ?></td>
                                                    <td>
                                                        <input type="text" name="class_name" value="<?= htmlspecialchars($class['class_name']) ?>" class="form-control" required>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-text">৳</span>
                                                            <input type="number" name="fare" value="<?= $class['fare'] ?>" step="0.01" min="0" class="form-control" required>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <input type="number" name="seat_rows" value="<?= $class['seat_rows'] ?>" min="1" class="form-control" placeholder="Rows">
                                                            <span class="input-group-text">×</span>
                                                            <input type="number" name="seat_columns" value="<?= $class['seat_columns'] ?>" min="1" class="form-control" placeholder="Columns">
                                                        </div>
                                                    </td>
                                                    <td class="text-nowrap">
                                                        <button type="submit" class="btn btn-sm btn-success action-btn">
                                                            <i class="fas fa-save"></i> Save
                                                        </button>
                                                        <a href="?delete=<?= $class['id'] ?>&type=class" class="btn btn-sm btn-danger action-btn" 
                                                           onclick="return confirm('Delete this class?')">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </a>
                                                    </td>
                                                </form>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Activate tab if coming from train link
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('train_id')) {
                const tab = new bootstrap.Tab(document.getElementById('classes-tab'));
                tab.show();
            }
        });
    </script>
</body>
</html>