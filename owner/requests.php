<?php
$base_path = "../";
include('../database.php');
include('../includes/header.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'owner') {
    header("Location: ../auth.php");
    exit();
}

$owner_id = $_SESSION['user_id'];

$sql = "SELECT requests.*, properties.title, users.name AS tenant_name, users.phone AS tenant_phone, users.email AS tenant_email
        FROM requests
        JOIN properties ON requests.property_id = properties.id
        JOIN users ON requests.user_id = users.id
        WHERE properties.owner_id = '$owner_id'
        ORDER BY requests.id DESC";

$result = $conn->query($sql);
?>

<h2>Incoming Requests</h2>

<div class="property-list">

<?php while($row = $result->fetch_assoc()): ?>

    <div class="property-card">

        <h3><?php echo $row['title']; ?></h3>
        <p><strong>From:</strong> <?php echo $row['tenant_name']; ?></p>
        <p><strong>Phone:</strong> <?php echo $row['tenant_phone']; ?></p>
        <p><strong>Email:</strong> <?php echo $row['tenant_email'] ? $row['tenant_email'] : 'N/A'; ?></p>
        <p><strong>Message:</strong> <?php echo $row['message']; ?></p>
        <p><strong>Status:</strong> <?php echo $row['status']; ?></p>

    </div>

<?php endwhile; ?>

</div>

<?php include('../includes/footer.php'); ?>
