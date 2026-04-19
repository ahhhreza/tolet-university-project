<?php
$base_path = "../";
include('../database.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth.php");
    exit();
}

if (!isset($_GET['id'], $_GET['approval_status'])) {
    header("Location: dashboard.php");
    exit();
}

$property_id = $_GET['id'];
$approval_status = $_GET['approval_status'];

if ($approval_status != 'approved' && $approval_status != 'rejected') {
    header("Location: dashboard.php");
    exit();
}

$sql = "UPDATE properties SET approval_status = '$approval_status' WHERE id = '$property_id'";
$conn->query($sql);

header("Location: dashboard.php");
exit();
?>
