<?php
$base_path = "../";
include('../database.php');

// Security check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'owner') {
    header("Location: ../auth.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: my_properties.php");
    exit();
}

$property_id = $_GET['id'];
$owner_id = $_SESSION['user_id'];

// Make sure owner owns this property
$sql = "DELETE FROM properties 
        WHERE id = '$property_id' 
        AND owner_id = '$owner_id'";

if ($conn->query($sql)) {
    header("Location: my_properties.php");
} else {
    echo "Error deleting property!";
}
?>