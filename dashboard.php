<?php 
    include("db.php");
    session_start();

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Show Welcome Message -->
    <div class="dashboard-container">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
        <p>You are now logged in.</p>

        <!-- Logout button with text -->
        <a href="logout.php" class="logout-button">Logout</a>
    </div>
</body>
</html>
