<?php
    session_start();
    include("../db.php");

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new admin into the database
        $sql = "INSERT INTO admin (name, email, password) VALUES ('$name', '$email', '$hashed_password')";

        if (mysqli_query($conn, $sql)) {
            // If insertion is successful, delete the old admin row (if needed)
            $sql_delete_old_admin = "DELETE FROM admin WHERE admin_id = 1"; // Assuming the old admin has admin_id = 1
            if (mysqli_query($conn, $sql_delete_old_admin)) {
                $_SESSION['message'] = "Admin account created successfully, and old admin row deleted!";
            } else {
                $_SESSION['message'] = "Admin account created, but failed to delete old admin.";
            }

            header("Location: login.php"); // Redirect to login page after creating the new admin
            exit();
        } else {
            $_SESSION['message'] = "Error creating admin: " . mysqli_error($conn);
        }
    }
?>

<!-- admin_registration.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

    <h2>Create New Admin Account</h2>

    <form action="admin_registration.php" method="POST">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Create Admin</button>
    </form>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="message"><?= htmlspecialchars($_SESSION['message']) ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

</body>
</html>
