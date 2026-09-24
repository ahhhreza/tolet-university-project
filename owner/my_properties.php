<?php
$base_path = "../";
include('../database.php');
include('../includes/header.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'owner') {
    header("Location: ../auth.php");
    exit();
}

$owner_id = $_SESSION['user_id'];

$sql = "SELECT * FROM properties WHERE owner_id = '$owner_id' ORDER BY id DESC";
$result = $conn->query($sql);
?>

<div class="container"><div class="page-intro"><div class="eyebrow">Owner workspace</div><h1 class="page-title">My properties</h1><p class="page-subtitle">Keep track of your listings and their approval status.</p></div>

<div class="property-list">

<?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="property-card">
            <div class="d-flex justify-content-between gap-2"><h3><?php echo htmlspecialchars($row['title']); ?></h3><span class="badge text-bg-light align-self-start"><?php echo htmlspecialchars(ucfirst($row['approval_status'])); ?></span></div>

            <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
            <p><strong>Rent:</strong> BDT <?php echo number_format((float) $row['rent']); ?></p>
            <p><strong>Type:</strong> <?php echo $row['property_type']; ?></p>
            <p><strong>Approval:</strong> <?php echo ucfirst($row['approval_status']); ?></p>
            <p><strong>Availability:</strong> <?php echo ($row['status'] == 'available') ? 'Available' : 'Rented'; ?></p>

            <a class="btn" href="../property_details.php?id=<?php echo $row['id']; ?>">
                View
            </a>

            <a class="btn" style="background:red;" href="delete_property.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?');">
                Delete
            </a>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No properties added yet.</p>
<?php endif; ?>

</div>
</div>

<?php include('../includes/footer.php'); ?>
