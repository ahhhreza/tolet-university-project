<?php
$base_path = "../";
include('../database.php');
include('../includes/header.php');

// Security check: only owner
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'owner') {
    header("Location: ../auth.php");
    exit();
}

$owner_id = $_SESSION['user_id'];

// Fetch only this owner's properties
$sql = "SELECT * FROM properties WHERE owner_id = '$owner_id' ORDER BY id DESC";
$result = $conn->query($sql);
?>

<h2>My Properties</h2>

<div class="property-list">

<?php if ($result->num_rows > 0): ?>
    
    <?php while($row = $result->fetch_assoc()): ?>

        <div class="property-card">
            <h3><?php echo $row['title']; ?></h3>

            <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
            <p><strong>Rent:</strong> ৳<?php echo $row['rent']; ?></p>
            <p><strong>Type:</strong> <?php echo $row['property_type']; ?></p>

            <?php if ($row['status'] == 'available'): ?>
                <p style="color:green;"><strong>Status:</strong> Available</p>
            <?php else: ?>
                <p style="color:red;"><strong>Status:</strong> Rented</p>
            <?php endif; ?>

            <a class="btn" href="../property_details.php?id=<?php echo $row['id']; ?>">
                View
            </a>

            <!-- <a class="btn" href="../property_details.php?id=<?php echo $row['id']; ?>">
                View
            </a> -->

            <a class="btn" style="background:red;"
            href="delete_property.php?id=<?php echo $row['id']; ?>"
            onclick="return confirm('Are you sure?');">
                Delete
            </a>

        </div>

    <?php endwhile; ?>

<?php else: ?>
    <p>No properties added yet.</p>
<?php endif; ?>

</div>

<?php include('../includes/footer.php'); ?>