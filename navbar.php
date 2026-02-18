<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTTMS</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="../index.php">
            <i class="fas fa-train"></i> OTTMS
        </a>
        <div class="collapse navbar-collapse">
            <?php if(isset($_SESSION['user_id'])) : ?>
                <!-- User menu -->
            <?php elseif(isset($_SESSION['admin_id'])) : ?>
                <!-- Admin menu -->
            <?php else : ?>
                <a href="users/register.php" class="btn btn-light ml-auto">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>