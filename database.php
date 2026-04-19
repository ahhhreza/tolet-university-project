<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "tolet_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

// Keep older local databases compatible with the newer approval flow.
$column_check = $conn->query("SHOW COLUMNS FROM properties LIKE 'approval_status'");
if ($column_check && $column_check->num_rows == 0) {
    $conn->query("ALTER TABLE properties
                  ADD COLUMN approval_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'
                  AFTER status");
    $conn->query("UPDATE properties
                  SET approval_status = CASE
                      WHEN status = 'available' THEN 'approved'
                      ELSE 'rejected'
                  END");
}
?>
