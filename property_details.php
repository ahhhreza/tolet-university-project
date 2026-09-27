<?php
$base_path = "";
include('database.php');
include('includes/header.php');

if (!isset($_GET['id'])) { echo "Invalid Property!"; exit(); }
$id = $_GET['id'];
$sql = "SELECT properties.*, users.name AS owner_name, users.phone AS owner_phone FROM properties JOIN users ON properties.owner_id = users.id WHERE properties.id = '$id'";
$result = $conn->query($sql);
if ($result->num_rows != 1) { echo "Property not found!"; exit(); }

$row = $result->fetch_assoc();
$is_logged_in = isset($_SESSION['user_id']);
$is_owner = $is_logged_in && $_SESSION['user_id'] == $row['owner_id'];
$is_admin = $is_logged_in && $_SESSION['role'] == 'admin';
$is_visible_to_user = ($row['approval_status'] == 'approved') || $is_owner || $is_admin;
if (!$is_visible_to_user) { echo "<p class='message'>This property is not publicly available right now.</p>"; include('includes/footer.php'); exit(); }

$img_sql = "SELECT * FROM property_images WHERE property_id = '$id' ORDER BY id DESC";
$img_result = $conn->query($img_sql);
$images = $img_result ? $img_result->fetch_all(MYSQLI_ASSOC) : [];
$approval_label = ucfirst($row['approval_status']);
$availability_label = ($row['status'] == 'available') ? 'Available' : 'Rented';
$request_sent = false;
if ($is_logged_in && $_SESSION['role'] == 'tenant') {
    $request_check_sql = "SELECT id FROM requests WHERE property_id = '$id' AND user_id = '" . $_SESSION['user_id'] . "'";
    $request_check_result = $conn->query($request_check_sql);
    $request_sent = $request_check_result && $request_check_result->num_rows > 0;
}
?>

<div class="container detail-page">
    <a class="back-link" href="index.php"><i class="bi bi-arrow-left"></i> Back to listings</a>
    <header class="detail-heading">
        <div><div class="eyebrow">Property details</div><h1><?php echo htmlspecialchars($row['title']); ?></h1><p><i class="bi bi-geo-alt-fill"></i> <?php echo htmlspecialchars($row['location']); ?> <span>&middot;</span> <?php echo htmlspecialchars($row['property_type']); ?></p></div>
        <div class="detail-price"><span>Monthly rent</span><strong>BDT <?php echo number_format((float) $row['rent']); ?></strong><div><span class="status-pill available"><?php echo htmlspecialchars($availability_label); ?></span><span class="status-pill type"><?php echo htmlspecialchars($row['property_type']); ?></span></div></div>
    </header>

    <?php if (isset($_GET['request']) && $_GET['request'] == 'success'): ?><p class="success-message">Request sent successfully!</p><?php elseif (isset($_GET['request']) && $_GET['request'] == 'exists'): ?><p class="message">You have already sent a request for this property.</p><?php elseif (isset($_GET['request']) && $_GET['request'] == 'failed'): ?><p class="message">Could not send request. Please try again.</p><?php endif; ?>

    <section class="detail-gallery">
        <?php if ($images): foreach ($images as $image): ?><img src="uploads/<?php echo htmlspecialchars($image['image_path']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>"><?php endforeach; else: ?><div class="detail-image-empty"><i class="bi bi-house-door"></i><span>No image available</span></div><?php endif; ?>
    </section>

    <div class="detail-layout">
        <div class="detail-main">
            <section class="detail-section"><h2>About this property</h2><p class="detail-description"><?php echo nl2br(htmlspecialchars($row['description'])); ?></p></section>
            <section class="detail-section"><h2>Property overview</h2><div class="detail-facts"><div><i class="bi bi-geo-alt"></i><span>Location</span><strong><?php echo htmlspecialchars($row['location']); ?></strong></div><div><i class="bi bi-building"></i><span>Property type</span><strong><?php echo htmlspecialchars($row['property_type']); ?></strong></div><div><i class="bi bi-calendar3"></i><span>Listed</span><strong><?php echo date('M j, Y', strtotime($row['created_at'])); ?></strong></div></div></section>
            <?php if ($is_owner || $is_admin): ?><section class="detail-section management-info"><h2>Listing status</h2><div><span>Approval: <strong><?php echo htmlspecialchars($approval_label); ?></strong></span><span>Availability: <strong><?php echo htmlspecialchars($availability_label); ?></strong></span></div></section><?php endif; ?>
        </div>
        <aside class="detail-sidebar">
            <section class="owner-panel"><div class="owner-icon"><i class="bi bi-person"></i></div><div class="eyebrow">Listed by</div><h2><?php echo htmlspecialchars($row['owner_name']); ?></h2><?php if ($is_logged_in): ?><a class="owner-phone" href="tel:<?php echo htmlspecialchars($row['owner_phone']); ?>"><i class="bi bi-telephone"></i> <?php echo htmlspecialchars($row['owner_phone']); ?></a><?php else: ?><p class="owner-note"><i class="bi bi-lock"></i> Sign in to view the owner's phone number.</p><a class="btn btn-outline-success w-100" href="auth.php">Sign in to contact</a><?php endif; ?></section>
            <?php if ($is_logged_in && $_SESSION['role'] == 'tenant' && $row['approval_status'] == 'approved' && $row['status'] == 'available'): ?><section class="request-panel"><h2>Interested in this property?</h2><?php if ($request_sent): ?><p class="message mb-0">You have already sent a request for this property.</p><?php else: ?><form method="POST" action="user/send_request.php"><input type="hidden" name="property_id" value="<?php echo $row['id']; ?>"><label for="request-message">Message to owner</label><textarea id="request-message" name="message" placeholder="Write a message to the owner..." required></textarea><button type="submit" class="btn btn-primary w-100">Send request</button></form><?php endif; ?></section><?php endif; ?>
        </aside>
    </div>
</div>

<?php include('includes/footer.php'); ?>
