<?php
session_start();
include('database.php');

$message = "";

// REGISTER
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $sql = "INSERT INTO users (name, phone, email, password, role)
            VALUES ('$name', '$phone', '$email', '$password', '$role')";

    if ($conn->query($sql)) {
        $message = "Registration successful!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// LOGIN
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

            header("Location: index.php"); // redirect after login
            exit();
        } else {
            $message = "Invalid password!";
        }
    } else {
        $message = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>To-Let Auth</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h2>To-Let System</h2>
    <p class="message"><?php echo $message; ?></p>

    <div class="forms">

        <!-- REGISTER -->
        <div class="form-box">
            <h3>Register</h3>
            <form method="POST">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="text" name="phone" placeholder="Phone Number" required>
                <input type="email" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password" required>

                <select name="role">
                    <option value="tenant">Tenant</option>
                    <option value="owner">House Owner</option>
                </select>

                <button type="submit" name="register">Register</button>
            </form>
        </div>

        <!-- LOGIN -->
        <div class="form-box">
            <h3>Login</h3>
            <form method="POST">
                <input type="text" name="phone" placeholder="Phone Number" required>
                <input type="password" name="password" placeholder="Password" required>

                <button type="submit" name="login">Login</button>
            </form>
        </div>

    </div>

</div>

</body>
</html>