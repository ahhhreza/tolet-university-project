<?php
$base_path = "../";
include('../database.php');
include('../includes/header.php');

// Restrict access (only owner)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'owner') {
    header("Location: ../auth.php");
    exit();
}

$message = "";

if (isset($_POST['submit'])) {
    $owner_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $rent = $_POST['rent'];
    $location = $_POST['location'];
    $type = $_POST['property_type'];

    // Insert property
    $sql = "INSERT INTO properties (owner_id, title, description, rent, location, property_type)
            VALUES ('$owner_id', '$title', '$description', '$rent', '$location', '$type')";

    if ($conn->query($sql)) {
        $property_id = $conn->insert_id;

        // Handle image upload (optional)
        if (!empty($_FILES['image']['name'])) {
            $image_name = time() . "_" . $_FILES['image']['name'];
            $target = "../uploads/" . $image_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $conn->query("INSERT INTO property_images (property_id, image_path)
                              VALUES ('$property_id', '$image_name')");
            }
        }

        $message = "Property added successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<h2>Add Property</h2>

<p class="message"><?php echo $message; ?></p>

<div class="form-box" style="max-width:500px; margin:auto;">
    <form method="POST" enctype="multipart/form-data">

        <input type="text" name="title" placeholder="Property Title" required>

        <textarea name="description" placeholder="Description" rows="4"></textarea>

        <input type="number" name="rent" placeholder="Rent Amount" required>

        <input type="text" name="location" placeholder="Location (e.g. Dhaka, Gazipur)" required>

        <select name="property_type">
            <option value="Flat">Flat</option>
            <option value="Room">Room</option>
            <option value="Sublet">Sublet</option>
        </select>

        <input type="file" name="image">

        <button type="submit" name="submit">Add Property</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>