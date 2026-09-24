<?php
$base_path = "../";
include('../database.php');
include('../includes/header.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth.php");
    exit();
}

$per_page = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $per_page;

$count_sql = "SELECT COUNT(*) AS total FROM properties";
$count_result = $conn->query($count_sql);
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $per_page);

if ($total_pages < 1) {
    $total_pages = 1;
}

$sql = "SELECT properties.*, users.name AS owner_name
        FROM properties
        JOIN users ON properties.owner_id = users.id
        ORDER BY properties.id DESC
        LIMIT $offset, $per_page";

$result = $conn->query($sql);
?>

<div class="container"><div class="page-intro"><div class="eyebrow">Administration</div><h1 class="page-title">Property approvals</h1><p class="page-subtitle">Review every listing before it becomes visible to tenants.</p></div><div class="admin-links">
    <a class="btn" href="dashboard.php">Properties</a>
    <a class="btn" href="owners.php">Owners</a>
    <a class="btn" href="tenants.php">Tenants</a>
</div>

<div class="property-list">

<?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="property-card">
            <h3><?php echo $row['title']; ?></h3>
            <p><strong>Owner:</strong> <?php echo $row['owner_name']; ?></p>
            <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
            <p><strong>Approval:</strong> <?php echo ucfirst($row['approval_status']); ?></p>
            <p><strong>Availability:</strong> <?php echo ($row['status'] == 'available') ? 'Available' : 'Rented'; ?></p>

            <a class="btn btn-success btn-sm mt-2" href="update_status.php?id=<?php echo $row['id']; ?>&approval_status=approved">
                Approve
            </a>

            <a class="btn btn-outline-danger btn-sm mt-2" href="update_status.php?id=<?php echo $row['id']; ?>&approval_status=rejected">
                Reject
            </a>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No properties found.</p>
<?php endif; ?>

</div>

<?php if ($total_pages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a class="btn btn-outline-secondary btn-sm" href="dashboard.php?page=<?php echo $page - 1; ?>">Previous</a>
        <?php endif; ?>

        <span class="page-info">Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>

        <?php if ($page < $total_pages): ?>
            <a class="btn btn-outline-secondary btn-sm" href="dashboard.php?page=<?php echo $page + 1; ?>">Next</a>
        <?php endif; ?>
    </div>
<?php endif; ?>

</div>

<?php include('../includes/footer.php'); ?>
