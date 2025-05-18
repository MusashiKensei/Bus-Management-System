<?php
    include("db.php");
    session_start();

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];

        $sql_check_email = "SELECT * FROM passengers WHERE email = '$email'";
        $result = mysqli_query($conn,$sql_check_email);

        if (mysqli_num_rows($result) > 0) {
            $data = mysqli_fetch_assoc($result); // contains passenger row
            $hashed_password = $data['password']; // store hashed pass from registration

            if(password_verify($password, $hashed_password)){
                // password matched
                $_SESSION['user_id'] = $data['passenger_id']; 
                $_SESSION['username'] = $data['name'];
                header("Location: dashboard.php"); // Redirect passenger dashboard
                exit();
            } else {
                // password is wrong
                $_SESSION['message'] = "Incorrect Password";
                header("Location: login.php");
                exit();
            }
        } else {
            // wrong email
            $_SESSION['message'] = "No account found with that email.";
            header("Location:dashboard.php");
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
