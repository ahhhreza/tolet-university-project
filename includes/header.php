<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>To-Let System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-left">
        <h2>To-Let</h2>
    </div>

    <div class="nav-right">
        <a href="index.php">Home</a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if ($_SESSION['role'] == 'owner'): ?>
                <a href="owner/add_property.php">Add Property</a>
            <?php endif; ?>

            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="admin/dashboard.php">Admin</a>
            <?php endif; ?>

            <span class="welcome">Hi, <?php echo $_SESSION['name']; ?></span>
            <a href="logout.php">Logout</a>

        <?php else: ?>
            <a href="auth.php">Login</a>
        <?php endif; ?>
    </div>
</nav>

<div class="main-content">