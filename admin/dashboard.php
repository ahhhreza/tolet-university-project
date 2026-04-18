<?php
$base_path = "../";
include('../database.php');
include('../includes/header.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth.php");
    exit();
}

$sql = "SELECT properties.*, users.name AS owner_name
        FROM properties
        JOIN users ON properties.owner_id = users.id
        ORDER BY properties.id DESC";

$result = $conn->query($sql);
?>

<h2>Admin Dashboard - Property Listings</h2>

<div class="property-list">

<?php while($row = $result->fetch_assoc()): ?>

    <div class="property-card">
        <h3><?php echo $row['title']; ?></h3>
        <p><strong>Owner:</strong> <?php echo $row['owner_name']; ?></p>
        <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
        <p><strong>Status:</strong> <?php echo $row['status']; ?></p>

        <!-- Approve -->
        <a class="btn" href="update_status.php?id=<?php echo $row['id']; ?>&status=available">
            Approve
        </a>

        <!-- Reject -->
        <a class="btn" style="background:red;"
           href="update_status.php?id=<?php echo $row['id']; ?>&status=rented">
            Reject
        </a>

    </div>

<?php endwhile; ?>

</div>

<?php include('../includes/footer.php'); ?>