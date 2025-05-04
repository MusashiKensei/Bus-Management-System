<?php 
    include("../db.php");
    session_start();

    // Redirect if not logged in
    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit();
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_style.css">
</head>

<body>

    <div class="dashboard">
        <a href="add_bus.php">➕ Add New Bus</a>
        <a href="view_bus.php">🚌 View All Buses</a>
        <a href="add_schedule.php">🗓️ Add Schedule</a>
        <a href="view_schedule.php">📅 View Schedule</a>
        <a href="view_bookings.php">📄 View Bookings</a>
        <a href="logout.php" class="logout-button">🚪 Logout</a>
    </div>


</body>

</html>