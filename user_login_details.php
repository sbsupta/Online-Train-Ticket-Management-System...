<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    try {
        $conn->beginTransaction();
        $stmt = $conn->prepare("DELETE FROM bookings WHERE user_id = ?");
        $stmt->execute([$id]);
        $stmt = $conn->prepare("DELETE FROM feedbacks WHERE user_id = ?");
        $stmt->execute([$id]);
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $conn->commit();
        $_SESSION['message'] = 'User deleted successfully';
    } catch (PDOException $e) {
        $conn->rollBack();
        $_SESSION['error'] = 'Error deleting user';
    }
    header("Location: user_login_details.php");
    exit();
}

if (isset($_POST['add'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    
    if (empty($name) || empty($email) || empty($password) || empty($phone)) {
        $_SESSION['error'] = 'All fields are required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid email format';
    } elseif (!preg_match('/^[0-9]{10,15}$/', $phone)) {
        $_SESSION['error'] = 'Invalid phone number (10-15 digits)';
    } elseif (strlen($password) < 8) {
        $_SESSION['error'] = 'Password must be at least 8 characters';
    } else {
        try {
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR phone = ?");
            $stmt->execute([$email, $phone]);
            
            if ($stmt->rowCount() > 0) {
                $_SESSION['error'] = 'Email or phone already exists';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $email, $phone, $hashedPassword]);
                $_SESSION['message'] = 'User added successfully';
                header("Location: user_login_details.php");
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error adding user';
        }
    }
}

$stmt = $conn->prepare("SELECT id, name, email, phone FROM users ORDER BY id DESC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management | OTTMS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-header { font-weight: bold; }
        .password-toggle { cursor: pointer; }
        .password-toggle:hover { opacity: 0.8; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-users"></i> User Management</h2>
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['message']) ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-user-plus"></i> Add New User
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="name" placeholder="Full Name" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <input type="email" name="email" placeholder="Email" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <input type="tel" name="phone" placeholder="Phone" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <input type="password" name="password" id="password" placeholder="Password" 
                                       class="form-control" required minlength="8">
                                <span class="input-group-text password-toggle" onclick="togglePassword()">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" name="add" class="btn btn-success w-100">
                                <i class="fas fa-plus"></i> Add
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-dark text-white">
                <i class="fas fa-list"></i> User List
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="5" class="text-center">No users found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= $user['id'] ?></td>
                                        <td><?= htmlspecialchars($user['name']) ?></td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td><?= htmlspecialchars($user['phone'] ?? 'N/A') ?></td>
                                        <td>
                                            <a href="?delete=<?= $user['id'] ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Are you sure? This will delete all user data.');">
                                               <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>