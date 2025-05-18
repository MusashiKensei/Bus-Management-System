<?php
    include("db.php");
    session_start();
 
    // Step 1: Check if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Step 2: Get submitted data from form
        $username = $_POST['username'];
        $email = $_POST['email'];
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "SELECT * FROM passengers WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $_SESSION['message'] = "Email already exists. Please use a different one.";
            header("Location: register.php");
            exit();
        } else {
            // proceed to insert
            $insert_sql = "INSERT INTO passengers (name, email, phone, password)
                            VALUES ('$username','$email',
                            '$phone','$hashed_password')";
            $insert_result = mysqli_query($conn, $insert_sql);

            if ($insert_result) {
                $_SESSION['message'] = "Registration successful!";
                header("Location: register.php");
                exit();
            } else {
                $_SESSION['message'] = "Error: " . mysqli_error($conn);
                header("Location: dashboard.php");
                exit();
            }
        }
        // Close the MySQL connection after processing the form
        mysqli_close($conn);
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <!-- Link to style.css -->
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <form action="register.php" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="phone">Phone:</label><br>
        <input type="tel" id="phone" name="phone" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Register</button>

    </form>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="message"><?= htmlspecialchars($_SESSION['message']) ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
</body>

</html>