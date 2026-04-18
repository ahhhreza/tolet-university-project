<?php
include('database.php');

$hash = password_hash("admin123", PASSWORD_DEFAULT);

$sql = "INSERT INTO users (name, phone, email, password, role)
        VALUES ('Super Admin', '01700000000', 'admin@tolet.com', '$hash', 'admin')";

$conn->query($sql);

echo "Admin created successfully!";
?>