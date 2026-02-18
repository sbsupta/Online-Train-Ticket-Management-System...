<?php
session_start();
require_once '../includes/db.php';

// Authentication check
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Handle all announcement operations
try {
    // Insert Announcement
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_announcement'])) {
        $title = trim($_POST['title']);
        $msg = trim($_POST['message']);
        
        if (!empty($title) && !empty($msg)) {
            $stmt = $conn->prepare("INSERT INTO announcements (title, message) VALUES (?, ?)");
            $stmt->execute([$title, $msg]);
            $_SESSION['message'] = 'Announcement added successfully';
        } else {
            $_SESSION['error'] = 'Title and message cannot be empty';
        }
        header("Location: manage_announcement.php");
        exit();
    }

    // Update Announcement
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_announcement'])) {
        $id = (int)$_POST['id'];
        $title = trim($_POST['title']);
        $msg = trim($_POST['message']);
        
        if (!empty($title) && !empty($msg)) {
            $stmt = $conn->prepare("UPDATE announcements SET title=?, message=? WHERE id=?");
            $stmt->execute([$title, $msg, $id]);
            $_SESSION['message'] = 'Announcement updated successfully';
        } else {
            $_SESSION['error'] = 'Title and message cannot be empty';
        }
        header("Location: manage_announcement.php");
        exit();
    }

    // Delete Announcement
    if (isset($_GET['delete'])) {
        $id = (int)$_GET['delete'];
        $stmt = $conn->prepare("DELETE FROM announcements WHERE id=?");
        $stmt->execute([$id]);
        $_SESSION['message'] = 'Announcement deleted successfully';
        header("Location: manage_announcement.php");
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['error'] = 'Database error: ' . $e->getMessage();
    header("Location: manage_announcement.php");
    exit();
}

// Get edit data if needed
$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM announcements WHERE id=?");
    $stmt->execute([$id]);
    $edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch all announcements
$stmt = $conn->query("SELECT * FROM announcements ORDER BY created_at DESC");
$announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Announcements | OTTMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #34495e;
            --accent: #3498db;
            --light: #ecf0f1;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
        }
        
        .card-header {
            background-color: var(--primary);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            font-weight: 600;
        }
        
        .announcement-card {
            transition: transform 0.2s;
            border-left: 4px solid var(--accent);
        }
        
        .announcement-card:hover {
            transform: translateY(-3px);
        }
        
        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }
        
        .action-btn {
            min-width: 80px;
            margin: 2px;
        }
        
        .timestamp {
            font-size: 0.85rem;
            color: #6c757d;
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
            <h2><i class="fas fa-bullhorn"></i> Manage Announcements</h2>
            <a href="dashboard.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Announcement Form -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-<?= $edit ? 'edit' : 'plus' ?>"></i> 
                <?= $edit ? 'Edit Announcement' : 'Create New Announcement' ?>
            </div>
            <div class="card-body">
                <form method="post">
                    <?php if ($edit): ?>
                        <input type="hidden" name="id" value="<?= $edit['id'] ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control" 
                               placeholder="Important announcement..." required 
                               value="<?= htmlspecialchars($edit['title'] ?? '') ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea name="message" id="message" class="form-control" 
                                  rows="4" placeholder="Enter announcement details..." required><?= 
                                  htmlspecialchars($edit['message'] ?? '') ?></textarea>
                    </div>
                    
                    <button type="submit" name="<?= $edit ? 'update_announcement' : 'add_announcement' ?>" 
                            class="btn btn-<?= $edit ? 'info' : 'primary' ?>">
                        <i class="fas fa-save"></i> 
                        <?= $edit ? 'Update Announcement' : 'Publish Announcement' ?>
                    </button>
                    
                    <?php if ($edit): ?>
                        <a href="manage_announcement.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- Announcements List -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-list"></i> All Announcements
            </div>
            <div class="card-body">
                <?php if (empty($announcements)): ?>
                    <div class="alert alert-info">
                        No announcements found. Create your first announcement above.
                    </div>
                <?php else: ?>
                    <div class="row g-3">
                        <?php foreach ($announcements as $announcement): ?>
                            <div class="col-md-6">
                                <div class="card announcement-card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($announcement['title']) ?></h5>
                                        <p class="card-text"><?= nl2br(htmlspecialchars($announcement['message'])) ?></p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="timestamp">
                                                <i class="far fa-clock"></i> 
                                                <?= date('M j, Y g:i a', strtotime($announcement['created_at'])) ?>
                                            </small>
                                            <div>
                                                <a href="?edit=<?= $announcement['id'] ?>" 
                                                   class="btn btn-sm btn-warning action-btn">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="?delete=<?= $announcement['id'] ?>" 
                                                   class="btn btn-sm btn-danger action-btn"
                                                   onclick="return confirm('Are you sure you want to delete this announcement?');">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Focus on title field when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const titleField = document.getElementById('title');
            if (titleField) {
                titleField.focus();
            }
        });
    </script>
</body>
</html>