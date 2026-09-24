<?php
$base_path = "";
include('database.php');
include('includes/header.php');

$location = trim($_GET['location'] ?? '');
$min_rent = trim($_GET['min_rent'] ?? '');
$max_rent = trim($_GET['max_rent'] ?? '');
$property_type = trim($_GET['property_type'] ?? '');
$sort = $_GET['sort'] ?? 'newest';
$conditions = ["properties.status = 'available'", "properties.approval_status = 'approved'"];
if ($location !== '') $conditions[] = "properties.location = '" . $conn->real_escape_string($location) . "'";
if ($min_rent !== '' && is_numeric($min_rent)) $conditions[] = "properties.rent >= " . (float) $min_rent;
if ($max_rent !== '' && is_numeric($max_rent)) $conditions[] = "properties.rent <= " . (float) $max_rent;
if ($property_type !== '') $conditions[] = "properties.property_type = '" . $conn->real_escape_string($property_type) . "'";
$order_by = $sort === 'rent_low' ? 'properties.rent ASC' : ($sort === 'rent_high' ? 'properties.rent DESC' : 'properties.id DESC');
$where = implode(' AND ', $conditions);
$per_page = 12;
$page = max(1, (int) ($_GET['page'] ?? 1));
$count_result = $conn->query("SELECT COUNT(*) AS total FROM properties JOIN users ON properties.owner_id = users.id WHERE $where");
$total_count = $count_result ? (int) $count_result->fetch_assoc()['total'] : 0;
$total_pages = max(1, (int) ceil($total_count / $per_page));
$page = min($page, $total_pages);
$offset = ($page - 1) * $per_page;
$sql = "SELECT properties.*, users.name AS owner_name, (SELECT image_path FROM property_images WHERE property_id = properties.id ORDER BY id DESC LIMIT 1) AS image_path FROM properties JOIN users ON properties.owner_id = users.id WHERE $where ORDER BY $order_by LIMIT $per_page OFFSET $offset";
$result = $conn->query($sql);
$count = $result ? $result->num_rows : 0;
$query_params = array_filter(['location' => $location, 'min_rent' => $min_rent, 'max_rent' => $max_rent, 'property_type' => $property_type, 'sort' => $sort], static fn($value) => $value !== '');
$locations = $conn->query("SELECT DISTINCT location FROM properties WHERE status = 'available' AND approval_status = 'approved' AND location IS NOT NULL AND location != '' ORDER BY location");
$types = $conn->query("SELECT DISTINCT property_type FROM properties WHERE status = 'available' AND approval_status = 'approved' AND property_type IS NOT NULL AND property_type != '' ORDER BY property_type");
?>
<div class="container listing-page" id="listings">
    <header class="listing-heading"><div><div class="eyebrow">Property listings</div><h1>Available properties</h1></div><p>Approved homes ready to explore</p></header>
    <form class="filter-bar" method="GET" action="index.php">
        <div class="filter-field location-filter"><label for="location"><i class="bi bi-geo-alt"></i> Location</label><select id="location" name="location"><option value="">Select location</option><?php if ($locations): while ($item = $locations->fetch_assoc()): ?><option value="<?php echo htmlspecialchars($item['location']); ?>" <?php echo $location === $item['location'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($item['location']); ?></option><?php endwhile; endif; ?></select></div>
        <div class="filter-field"><label for="min_rent"><i class="bi bi-cash-stack"></i> Min rent</label><input id="min_rent" name="min_rent" type="number" min="0" placeholder="BDT 5,000" value="<?php echo htmlspecialchars($min_rent); ?>"></div>
        <div class="filter-field"><label for="max_rent"><i class="bi bi-cash-stack"></i> Max rent</label><input id="max_rent" name="max_rent" type="number" min="0" placeholder="BDT 50,000" value="<?php echo htmlspecialchars($max_rent); ?>"></div>
        <div class="filter-field type-filter"><label for="property_type">Type</label><select id="property_type" name="property_type"><option value="">All types</option><?php if ($types): while ($item = $types->fetch_assoc()): ?><option value="<?php echo htmlspecialchars($item['property_type']); ?>" <?php echo $property_type === $item['property_type'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($item['property_type']); ?></option><?php endwhile; endif; ?></select></div>
        <button class="btn btn-primary filter-submit" type="submit"><i class="bi bi-search"></i> Search</button>
    </form>
    <div class="listing-meta"><span>Showing <b><?php echo $total_count; ?></b> properties<?php if ($total_count > 0): ?> <small>(<?php echo $count; ?> on this page)</small><?php endif; ?></span><form method="GET" action="index.php" class="sort-form"><?php foreach (['location', 'min_rent', 'max_rent', 'property_type'] as $field): if ($$field !== ''): ?><input type="hidden" name="<?php echo $field; ?>" value="<?php echo htmlspecialchars($$field); ?>"><?php endif; endforeach; ?><label for="sort">Sort by:</label><select id="sort" name="sort" onchange="this.form.submit()"><option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest first</option><option value="rent_low" <?php echo $sort === 'rent_low' ? 'selected' : ''; ?>>Rent: low to high</option><option value="rent_high" <?php echo $sort === 'rent_high' ? 'selected' : ''; ?>>Rent: high to low</option></select></form></div>
    <div class="property-list listing-grid">
        <?php if ($count > 0): while ($row = $result->fetch_assoc()): ?><article class="property-card listing-card"><div class="listing-image"><?php if (!empty($row['image_path'])): ?><img src="uploads/<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>"><?php else: ?><div class="image-placeholder"><i class="bi bi-house-door"></i></div><?php endif; ?><div class="card-badges"><span>Available</span><span><?php echo htmlspecialchars($row['property_type']); ?></span></div></div><div class="listing-body"><h2><?php echo htmlspecialchars($row['title']); ?></h2><p><i class="bi bi-geo-alt-fill"></i> <?php echo htmlspecialchars($row['location']); ?></p><p><i class="bi bi-person-fill"></i> <?php echo htmlspecialchars($row['owner_name']); ?></p><div class="listing-price"><div><small>Monthly rent</small><strong>BDT <?php echo number_format((float) $row['rent']); ?></strong></div><a href="property_details.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">View details</a></div></div></article><?php endwhile; else: ?><div class="empty-results"><i class="bi bi-search"></i><h2>No properties found</h2><p>Try clearing a filter or changing the rent range.</p><a href="index.php" class="btn btn-outline-success">Clear filters</a></div><?php endif; ?>
    </div>
    <?php if ($total_pages > 1): ?><nav class="listing-pagination" aria-label="Property pages"><?php if ($page > 1): $previous_params = $query_params; $previous_params['page'] = $page - 1; ?><a class="btn btn-outline-success" href="index.php?<?php echo htmlspecialchars(http_build_query($previous_params)); ?>">Previous</a><?php endif; ?><span>Page <?php echo $page; ?> of <?php echo $total_pages; ?></span><?php if ($page < $total_pages): $next_params = $query_params; $next_params['page'] = $page + 1; ?><a class="btn btn-primary" href="index.php?<?php echo htmlspecialchars(http_build_query($next_params)); ?>">Next</a><?php endif; ?></nav><?php endif; ?>
</div>
<?php include('includes/footer.php'); ?>
