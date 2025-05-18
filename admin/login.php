<?php
    include("../db.php"); 
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];

        // Check if email exists in the admin table
        $sql_check_email = "SELECT * FROM admin WHERE email = '$email'";
        $result = mysqli_query($conn, $sql_check_email);

        if (mysqli_num_rows($result) > 0) {
            $data = mysqli_fetch_assoc($result); // contains admin data
            $hashed_password = $data['password']; // store hashed pass from admin table
            echo password_verify("yourAdminPassword", $hashed_password) ? 
            'Password matches' : 'Password does not match';

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // If the password is correct, start session and redirect to admin dashboard
                $_SESSION['admin_id'] = $data['admin_id'];
                $_SESSION['admin_name'] = $data['name'];
                header("Location: dashboard.php"); // Redirect to admin dashboard
                exit();
            } else {
                // Incorrect password
                $_SESSION['message'] = "Incorrect password.";
                header("Location: login.php");
                exit();
            }
        } else {
            // Email not found
            $_SESSION['message'] = "No account found with that email.";
            header("Location: login.php");
            exit();
        }
    }
?>


<!-- admin/login.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>

    <h2>Admin Login</h2>

    <form action="login.php" method="POST">
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
<?php include 'extra/foot.html'; ?>