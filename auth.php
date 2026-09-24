<?php
session_start();
$base_path = "";
include('database.php');

$message = "";
$message_class = "message";

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $check_sql = "SELECT id FROM users WHERE phone = '$phone'";
    $check_result = $conn->query($check_sql);

    if ($check_result && $check_result->num_rows > 0) {
        $message = "This phone number is already registered.";
    } else {
        $sql = "INSERT INTO users (name, phone, email, password, role)
                VALUES ('$name', '$phone', '$email', '$password', '$role')";

        if ($conn->query($sql)) {
            $message = "Registration successful!";
            $message_class = "success-message";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}

if (isset($_POST['login'])) {
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE phone='$phone'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];

            header("Location: index.php");
            exit();
        } else {
            $message = "Invalid password!";
        }
    } elseif ($result->num_rows > 1) {
        $message = "Multiple accounts found with this phone number. Please update the data.";
    } else {
        $message = "User not found!";
    }
}
?>

<?php include('includes/header.php'); ?>
<div class="auth-page"><div class="container"><div class="row g-0 auth-panel mx-auto">
    <div class="col-lg-5 auth-copy"><span class="brand text-white"><span class="brand-mark">T</span> To-Let</span><h1 class="mt-4">Find your next place.</h1><p>Browse approved homes, list your property, and connect with people directly.</p><a href="index.php" class="btn btn-light btn-sm mt-2">Browse properties</a></div>
    <div class="col-lg-7 auth-forms"><div class="row g-4">
        <div class="col-md-6"><div class="eyebrow">New account</div><h2 class="mb-3">Register</h2><?php if ($message): ?><p class="<?php echo $message_class; ?>"><?php echo htmlspecialchars($message); ?></p><?php endif; ?><form method="POST"><label>Full name</label><input type="text" name="name" placeholder="Your name" required><label>Phone number</label><input type="text" name="phone" placeholder="01XXXXXXXXX" required><label>Email</label><input type="email" name="email" placeholder="Optional"><label>Password</label><input type="password" name="password" required><label>Account type</label><select name="role"><option value="tenant">Tenant</option><option value="owner">Property owner</option></select><button type="submit" name="register" class="w-100 mt-3">Create account</button></form></div>
        <div class="col-md-6 border-md-start"><div class="eyebrow">Existing account</div><h2 class="mb-3">Sign in</h2><form method="POST"><label>Phone number</label><input type="text" name="phone" placeholder="01XXXXXXXXX" required><label>Password</label><input type="password" name="password" required><button type="submit" name="login" class="w-100 mt-3">Sign in</button></form></div>
    </div></div>
</div></div></div>
<?php include('includes/footer.php'); ?>
