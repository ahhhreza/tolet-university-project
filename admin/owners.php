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

$count_sql = "SELECT COUNT(*) AS total FROM users WHERE role = 'owner'";
$count_result = $conn->query($count_sql);
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $per_page);

if ($total_pages < 1) {
    $total_pages = 1;
}

$sql = "SELECT * FROM users
        WHERE role = 'owner'
        ORDER BY id DESC
        LIMIT $offset, $per_page";

$result = $conn->query($sql);
?>

<div class="admin-links">
    <a class="btn" href="dashboard.php">Properties</a>
    <a class="btn" href="owners.php">Owners</a>
    <a class="btn" href="tenants.php">Tenants</a>
</div>

<h2>All Owners</h2>

<div class="property-list">

<?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="property-card">
            <h3><?php echo $row['name']; ?></h3>
            <p><strong>Phone:</strong> <?php echo $row['phone']; ?></p>
            <p><strong>Email:</strong> <?php echo $row['email'] ? $row['email'] : 'N/A'; ?></p>
            <p><strong>Role:</strong> <?php echo ucfirst($row['role']); ?></p>
            <p><strong>Joined:</strong> <?php echo $row['created_at']; ?></p>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No owners found.</p>
<?php endif; ?>

</div>

<?php if ($total_pages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a class="btn" href="owners.php?page=<?php echo $page - 1; ?>">Previous</a>
        <?php endif; ?>

        <span class="page-info">Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>

        <?php if ($page < $total_pages): ?>
            <a class="btn" href="owners.php?page=<?php echo $page + 1; ?>">Next</a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php include('../includes/footer.php'); ?>
