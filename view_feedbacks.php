<?php
session_start();
require_once '../includes/db.php';

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Handle delete with prepared statement
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    try {
        $stmt = $conn->prepare("DELETE FROM feedbacks WHERE id = ?");
        $stmt->execute([$id]);
        
        $_SESSION['message'] = 'Feedback deleted successfully';
        header('Location: view_feedbacks.php');
        exit();
    } catch (PDOException $e) {
        error_log("Error deleting feedback: " . $e->getMessage());
        $_SESSION['error'] = 'Error deleting feedback';
    }
}

// Get feedbacks with user names using prepared statement
try {
    $stmt = $conn->prepare("
        SELECT f.*, u.name AS user_name
        FROM feedbacks f
        JOIN users u ON f.user_id = u.id
        ORDER BY f.submitted_at DESC
    ");
    $stmt->execute();
    $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching feedbacks: " . $e->getMessage());
    $feedbacks = [];
    $_SESSION['error'] = 'Error loading feedbacks';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedbacks - Admin | OTTMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .star { color: gold; font-size: 1.2rem; }
        .feedback-card { transition: transform 0.2s; }
        .feedback-card:hover { transform: translateY(-3px); }
        .rating-container { margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>User Feedbacks</h2>
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['message']) ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (empty($feedbacks)): ?>
            <div class="alert alert-info">No feedbacks found</div>
        <?php else: ?>
            <?php foreach ($feedbacks as $fb): ?>
                <div class="card mb-4 feedback-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title"><?= htmlspecialchars($fb['user_name']) ?></h5>
                            <small class="text-muted"><?= date('M j, Y g:i a', strtotime($fb['submitted_at'])) ?></small>
                        </div>
                        
                        <p class="card-text"><?= nl2br(htmlspecialchars($fb['feedback'])) ?></p>
                        
                        <div class="rating-container">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="star"><?= $i <= $fb['rating'] ? '★' : '☆' ?></span>
                            <?php endfor; ?>
                        </div>
                        
                        <div class="mt-3">
                            <a href="?delete=<?= $fb['id'] ?>" class="btn btn-sm btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this feedback?');">
                                Delete Feedback
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>