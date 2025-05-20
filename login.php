<?php
include("db.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Step 1: Check if the email is an admin
    $admin_sql = "SELECT * FROM admin WHERE email = '$email'";
    $admin_result = mysqli_query($conn, $admin_sql);

    if (mysqli_num_rows($admin_result) > 0) {
        $admin_data = mysqli_fetch_assoc($admin_result);
        if (password_verify($password, $admin_data['password'])) {
            // Login as admin
            $_SESSION['admin_id'] = $admin_data['admin_id'];
            $_SESSION['admin_name'] = $admin_data['name'];
            header("Location: admin/dashboard.php");
            exit();
        } else {
            $_SESSION['message'] = "Incorrect password.";
            header("Location: login.php");
            exit();
        }
    }

    // Step 2: Check if the email is a passenger
    $user_sql = "SELECT * FROM passengers WHERE email = '$email'";
    $user_result = mysqli_query($conn, $user_sql);

    if (mysqli_num_rows($user_result) > 0) {
        $user_data = mysqli_fetch_assoc($user_result);
        if (password_verify($password, $user_data['password'])) {
            $_SESSION['user_id'] = $user_data['passenger_id'];
            $_SESSION['username'] = $user_data['name'];
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['message'] = "Incorrect password.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "No account found with that email.";
        header("Location: login.php");
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <form action="login.php" method="post">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="message"><?= htmlspecialchars($_SESSION['message']) ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

</body>

</html>
