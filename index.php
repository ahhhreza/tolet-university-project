<?php
$base_path = "";
include('database.php');
include('includes/header.php');

$sql = "SELECT properties.*, users.name AS owner_name
        FROM properties
        JOIN users ON properties.owner_id = users.id
        WHERE properties.status = 'available'
        AND properties.approval_status = 'approved'
        ORDER BY properties.id DESC";

$result = $conn->query($sql);
?>

<h2>Available Properties</h2>

<div class="property-list">

<?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="property-card">
            <h3><?php echo $row['title']; ?></h3>
            <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
            <p><strong>Rent:</strong> &#2547;<?php echo $row['rent']; ?></p>
            <p><strong>Type:</strong> <?php echo $row['property_type']; ?></p>
            <p><strong>Owner:</strong> <?php echo $row['owner_name']; ?></p>

            <a href="property_details.php?id=<?php echo $row['id']; ?>" class="btn">
                View Details
            </a>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No properties available.</p>
<?php endif; ?>

</div>

<?php include('includes/footer.php'); ?>
