<?php
$base_path = "../";
include('../database.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'tenant') {
    header("Location: ../auth.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['property_id'], $_POST['message'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$property_id = $_POST['property_id'];
$message = $_POST['message'];

$property_sql = "SELECT id FROM properties
                 WHERE id = '$property_id'
                 AND approval_status = 'approved'
                 AND status = 'available'";
$property_result = $conn->query($property_sql);

if (!$property_result || $property_result->num_rows != 1) {
    header("Location: ../property_details.php?id=$property_id&request=failed");
    exit();
}

$existing_sql = "SELECT id FROM requests
                 WHERE property_id = '$property_id' AND user_id = '$user_id'";
$existing_result = $conn->query($existing_sql);

if ($existing_result && $existing_result->num_rows > 0) {
    header("Location: ../property_details.php?id=$property_id&request=exists");
    exit();
}

$sql = "INSERT INTO requests (property_id, user_id, message)
        VALUES ('$property_id', '$user_id', '$message')";

if ($conn->query($sql)) {
    header("Location: ../property_details.php?id=$property_id&request=success");
    exit();
}

header("Location: ../property_details.php?id=$property_id&request=failed");
exit();
?>
