<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$base_path = $base_path ?? "";
$is_logged_in = isset($_SESSION['user_id']);
$role = $_SESSION['role'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>To-Let | Find a place that feels right</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base_path; ?>style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg site-nav"><div class="container">
    <a class="navbar-brand brand" href="<?php echo $base_path; ?>index.php"><span class="brand-mark">T</span> To-Let</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#siteNav" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="siteNav"><div class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
        <a class="nav-link" href="<?php echo $base_path; ?>index.php">Home</a>
        <a class="nav-link" href="<?php echo $base_path; ?>index.php#listings">Listings</a>
        <a class="nav-link" href="<?php echo $base_path; ?>index.php#contact">Contact</a>
        <?php if ($is_logged_in): ?>
            <?php if ($role === 'owner'): ?>
                <a class="nav-link" href="<?php echo $base_path; ?>profile.php">Profile</a>
                <a class="nav-link" href="<?php echo $base_path; ?>owner/my_properties.php">My properties</a>
                <a class="nav-link" href="<?php echo $base_path; ?>owner/requests.php">Requests</a>
                <a class="btn btn-primary btn-sm px-3" href="<?php echo $base_path; ?>owner/add_property.php">Add property</a>
            <?php elseif ($role === 'admin'): ?>
                <a class="nav-link" href="<?php echo $base_path; ?>admin/dashboard.php">Properties</a>
                <a class="nav-link" href="<?php echo $base_path; ?>admin/owners.php">Owners</a>
                <a class="nav-link" href="<?php echo $base_path; ?>admin/tenants.php">Tenants</a>
                <a class="nav-link" href="<?php echo $base_path; ?>profile.php">Profile</a>
            <?php else: ?>
                <a class="nav-link" href="<?php echo $base_path; ?>profile.php">Profile</a>
            <?php endif; ?>
            <span class="user-chip ms-lg-2">Hi, <?php echo htmlspecialchars($_SESSION['name']); ?></span>
            <a class="nav-link" href="<?php echo $base_path; ?>logout.php">Log out</a>
        <?php else: ?>
            <a class="btn btn-primary btn-sm px-3 ms-lg-2" href="<?php echo $base_path; ?>auth.php">Sign in</a>
        <?php endif; ?>
    </div></div>
</div></nav>
<main class="main-content">
