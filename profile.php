<?php
$base_path = "";
include('database.php');
include('includes/header.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";
$message_class = "success-message";

if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $check_sql = "SELECT id FROM users WHERE phone = '$phone' AND id != '$user_id'";
    $check_result = $conn->query($check_sql);

    if ($check_result && $check_result->num_rows > 0) {
        $message = "This phone number is already being used by another account.";
        $message_class = "message";
    } else {
        $sql = "UPDATE users
                SET name = '$name', phone = '$phone', email = '$email'
                WHERE id = '$user_id'";

        if ($conn->query($sql)) {
            $_SESSION['name'] = $name;
            $message = "Profile updated successfully!";
        } else {
            $message = "Failed to update profile.";
            $message_class = "message";
        }
    }
}

$sql = "SELECT * FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows != 1) {
    echo "User not found!";
    include('includes/footer.php');
    exit();
}

$user = $result->fetch_assoc();
?>

<h2>My Profile</h2>

<?php if ($message): ?>
    <p class="<?php echo $message_class; ?>"><?php echo $message; ?></p>
<?php endif; ?>

<div class="form-box profile-card" style="max-width:500px; margin:auto;">
    <form method="POST">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>" required>

        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" value="<?php echo $user['phone']; ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>">

        <p><strong>Role:</strong> <?php echo ucfirst($user['role']); ?></p>
        <p><strong>Joined:</strong> <?php echo $user['created_at']; ?></p>

        <button type="submit" name="update_profile">Update Profile</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
