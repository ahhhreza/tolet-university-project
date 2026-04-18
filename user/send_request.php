<?php
$base_path = "../";
include('../database.php');

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'tenant') {
    header("Location: auth.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$property_id = $_POST['property_id'];
$message = $_POST['message'];

$sql = "INSERT INTO requests (property_id, user_id, message)
        VALUES ('$property_id', '$user_id', '$message')";

if ($conn->query($sql)) {
    echo "Request sent successfully!";
    echo "<br><a href='index.php'>Go Back</a>";
} else {
    echo "Error: " . $conn->error;
}
?>