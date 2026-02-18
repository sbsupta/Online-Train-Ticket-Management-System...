<?php
session_start();
require_once '../includes/db.php';

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// You could add additional security checks here if needed
// For example, verify the admin_id exists in database
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - OTTMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-btn {
            min-height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: 0.3s;
            color: white;
            text-decoration: none;
        }
        .card-btn:hover {
            transform: translateY(-3px);
            color: white;
        }
        .card-header {
            background-color: #343a40;
            color: white;
        }
        .dashboard-container {
            margin-top: 2rem;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="container dashboard-container">
        <h2 class="text-center mb-4">🚉 Admin Dashboard - OTTMS</h2>

        <div class="row">
            <div class="col-md-4 mb-4">
                <a href="manage_trains.php" class="btn btn-primary w-100 card-btn">Manage Trains</a>
            </div>
            <div class="col-md-4 mb-4">
                <a href="manage_announcements.php" class="btn btn-info w-100 card-btn">Manage Announcements</a>
            </div>
            <div class="col-md-4 mb-4">
                <a href="view_feedbacks.php" class="btn btn-dark w-100 card-btn">View User Feedback</a>
            </div>
            <div class="col-md-4 mb-4">
                <a href="user_login_details.php" class="btn btn-secondary w-100 card-btn">User Login Details</a>
            </div>
            <div class="col-md-4 mb-4">
                <a href="logout.php" class="btn btn-danger w-100 card-btn">Logout</a>
            </div>
        </div>
    </div>

    <!-- Optional: You can add footer or additional scripts here -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>