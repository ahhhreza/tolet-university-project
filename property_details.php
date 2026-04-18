<?php
$base_path = "";
include('database.php');
include('includes/header.php');

if (!isset($_GET['id'])) {
    echo "Invalid Property!";
    exit();
}

$id = $_GET['id'];

// Get property + owner info
$sql = "SELECT properties.*, users.name AS owner_name 
        FROM properties 
        JOIN users ON properties.owner_id = users.id 
        WHERE properties.id = '$id'";

$result = $conn->query($sql);

if ($result->num_rows != 1) {
    echo "Property not found!";
    exit();
}

$row = $result->fetch_assoc();
// $img_sql = "SELECT * FROM property_images WHERE property_id = '$property_id'";
// $img_result = $conn->query($img_sql);
?>

<div class="property-card" style="max-width:600px; margin:auto;">

    <h2><?php echo $row['title']; ?></h2>

    <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
    <p><strong>Rent:</strong> ৳<?php echo $row['rent']; ?></p>
    <p><strong>Type:</strong> <?php echo $row['property_type']; ?></p>
    <p><strong>Description:</strong> <?php echo $row['description']; ?></p>
    <p><strong>Owner:</strong> <?php echo $row['owner_name']; ?></p>

    <p><strong>Status:</strong> 
        <?php echo $row['status']; ?>
    </p>
 
    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'tenant'): ?>

    <h3>Interested in this property?</h3>

    <form method="POST" action="send_request.php">
        <input type="hidden" name="property_id" value="<?php echo $row['id']; ?>">

        <textarea name="message" placeholder="Write a message to owner..." required></textarea>

        <button type="submit" class="btn">Send Request</button>
    </form>

<?php endif; ?>

</div>

<?php include('includes/footer.php'); ?>