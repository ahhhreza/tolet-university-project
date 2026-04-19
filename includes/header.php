<?php
session_start();

if (!isset($base_path)) {
    $base_path = "";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>To-Let System</title>
    <link rel="stylesheet" href="<?php echo $base_path; ?>style.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-left">
        <h2>To-Let</h2>
    </div>

    <div class="nav-right">
        <a href="<?php echo $base_path; ?>index.php">Home</a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="<?php echo $base_path; ?>profile.php">Profile</a>
            
            <?php if ($_SESSION['role'] == 'owner'): ?>
                <a href="<?php echo $base_path; ?>owner/add_property.php">Add Property</a>
            <?php endif; ?>

            <?php if ($_SESSION['role'] == 'owner'): ?>
                <a href="<?php echo $base_path; ?>owner/my_properties.php">My Properties</a>
            <?php endif; ?>

            <?php if ($_SESSION['role'] == 'owner'): ?>
                <a href="<?php echo $base_path; ?>owner/requests.php">Requests</a>
            <?php endif; ?>

            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="<?php echo $base_path; ?>admin/dashboard.php">Admin</a>
            <?php endif; ?>

            <span class="welcome">Hi, <?php echo $_SESSION['name']; ?></span>
            <a href="<?php echo $base_path; ?>logout.php">Logout</a>

        <?php else: ?>
            <a href="<?php echo $base_path; ?>auth.php">Login</a>
        <?php endif; ?>
    </div>
</nav>

<div class="main-content">
