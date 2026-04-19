<?php
$base_path = "";
include('database.php');
include('includes/header.php');

if (!isset($_GET['id'])) {
    echo "Invalid Property!";
    exit();
}

$id = $_GET['id'];

$sql = "SELECT properties.*, users.name AS owner_name, users.phone AS owner_phone
        FROM properties
        JOIN users ON properties.owner_id = users.id
        WHERE properties.id = '$id'";

$result = $conn->query($sql);

if ($result->num_rows != 1) {
    echo "Property not found!";
    exit();
}

$row = $result->fetch_assoc();
$is_logged_in = isset($_SESSION['user_id']);
$is_owner = $is_logged_in && $_SESSION['user_id'] == $row['owner_id'];
$is_admin = $is_logged_in && $_SESSION['role'] == 'admin';
$is_visible_to_user = ($row['approval_status'] == 'approved') || $is_owner || $is_admin;

if (!$is_visible_to_user) {
    echo "<p class='message'>This property is not publicly available right now.</p>";
    include('includes/footer.php');
    exit();
}

$img_sql = "SELECT * FROM property_images WHERE property_id = '$id' ORDER BY id DESC";
$img_result = $conn->query($img_sql);

$approval_label = ucfirst($row['approval_status']);
$availability_label = ($row['status'] == 'available') ? 'Available' : 'Rented';
$request_sent = false;

if ($is_logged_in && $_SESSION['role'] == 'tenant') {
    $request_check_sql = "SELECT id FROM requests
                          WHERE property_id = '$id' AND user_id = '" . $_SESSION['user_id'] . "'";
    $request_check_result = $conn->query($request_check_sql);
    $request_sent = $request_check_result && $request_check_result->num_rows > 0;
}
?>

<div class="property-card" style="max-width:600px; margin:auto;">
    <h2><?php echo $row['title']; ?></h2>

    <?php if (isset($_GET['request']) && $_GET['request'] == 'success'): ?>
        <p class="success-message">Request sent successfully!</p>
    <?php elseif (isset($_GET['request']) && $_GET['request'] == 'exists'): ?>
        <p class="message">You have already sent a request for this property.</p>
    <?php elseif (isset($_GET['request']) && $_GET['request'] == 'failed'): ?>
        <p class="message">Could not send request. Please try again.</p>
    <?php endif; ?>

    <?php if ($img_result && $img_result->num_rows > 0): ?>
        <div class="image-gallery">
            <?php while($img = $img_result->fetch_assoc()): ?>
                <img src="uploads/<?php echo $img['image_path']; ?>" alt="<?php echo $row['title']; ?>">
            <?php endwhile; ?>
        </div>
    <?php endif; ?>

    <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
    <p><strong>Rent:</strong> &#2547;<?php echo $row['rent']; ?></p>
    <p><strong>Type:</strong> <?php echo $row['property_type']; ?></p>
    <p><strong>Description:</strong> <?php echo $row['description']; ?></p>
    <p><strong>Owner:</strong> <?php echo $row['owner_name']; ?></p>
    <?php if ($is_logged_in): ?>
        <p><strong>Phone:</strong> <?php echo $row['owner_phone']; ?></p>
    <?php else: ?>
        <p><strong>Phone:</strong> Login to view owner's phone number</p>
    <?php endif; ?>
    <p><strong>Approval:</strong> <?php echo $approval_label; ?></p>
    <p><strong>Availability:</strong> <?php echo $availability_label; ?></p>

    <?php if ($is_logged_in && $_SESSION['role'] == 'tenant' && $row['approval_status'] == 'approved' && $row['status'] == 'available'): ?>
        <h3>Interested in this property?</h3>

        <?php if ($request_sent): ?>
            <p class="message">You have already sent a request for this property.</p>
        <?php else: ?>
            <form method="POST" action="user/send_request.php">
                <input type="hidden" name="property_id" value="<?php echo $row['id']; ?>">

                <textarea name="message" placeholder="Write a message to owner..." required></textarea>

                <button type="submit" class="btn">Send Request</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>
