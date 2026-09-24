<?php
$base_path = "../";
include('../database.php');
include('../includes/header.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'owner') {
    header("Location: ../auth.php");
    exit();
}

$message = "";
$message_class = "message";

if (isset($_POST['submit'])) {
    $owner_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $rent = $_POST['rent'];
    $location = $_POST['location'];
    $type = $_POST['property_type'];

    $sql = "INSERT INTO properties (owner_id, title, description, rent, location, property_type, status, approval_status)
            VALUES ('$owner_id', '$title', '$description', '$rent', '$location', '$type', 'available', 'pending')";

    if ($conn->query($sql)) {
        $property_id = $conn->insert_id;
        $message = "Property added successfully and is now waiting for admin approval.";
        $message_class = "success-message";

        if (!empty($_FILES['image']['name'])) {
            $image_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp');
            $image_info = getimagesize($_FILES['image']['tmp_name']);

            if ($image_info && in_array($image_extension, $allowed_extensions)) {
                $image_name = time() . "_" . basename($_FILES['image']['name']);
                $target = "../uploads/" . $image_name;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                    $conn->query("INSERT INTO property_images (property_id, image_path)
                                  VALUES ('$property_id', '$image_name')");
                } else {
                    $message = "Property added, but the image upload failed.";
                    $message_class = "message";
                }
            } else {
                $message = "Property added, but only image files are allowed.";
                $message_class = "message";
            }
        }
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<div class="container"><div class="page-intro"><div class="eyebrow">Owner workspace</div><h1 class="page-title">Add a property</h1><p class="page-subtitle">Share the details of your space. New listings go to an administrator for approval.</p></div>

<p class="<?php echo $message_class; ?>"><?php echo $message; ?></p>

<div class="form-box" style="max-width:680px;">
    <form method="POST" enctype="multipart/form-data">
        <label>Property title</label><input type="text" name="title" placeholder="e.g. Bright 2-bedroom apartment" required>

        <label>Description</label><textarea name="description" placeholder="Tell people what makes this space special" rows="4"></textarea>

        <label>Monthly rent</label><input type="number" name="rent" placeholder="Rent amount" required>

        <label>Location</label><input type="text" name="location" placeholder="e.g. Dhaka, Gazipur" required>

        <label>Property type</label><select name="property_type">
            <option value="Flat">Flat</option>
            <option value="Room">Room</option>
            <option value="Sublet">Sublet</option>
        </select>

        <label>Cover image</label><input type="file" name="image" accept="image/*">

        <button type="submit" name="submit">Add Property</button>
    </form>
</div>
</div>

<?php include('../includes/footer.php'); ?>
