<?php
    include('../db.php');
    session_start();
    
    // Check if admin is logged in
    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit();
    }
    
    // Validate the schedule_id from GET
    if (!isset($_GET['schedule_id']) || !is_numeric($_GET['schedule_id'])) {
        die("Invalid schedule ID.");
    }
    
    $schedule_id = intval($_GET['schedule_id']);
    
    // Delete the schedule record
    $delete_sql = "DELETE FROM schedule WHERE schedule_id = $schedule_id";
    
    if (mysqli_query($conn, $delete_sql)) {
        header("Location: view_schedule.php");
        exit();
    } else {
        die("Error deleting schedule: " . mysqli_error($conn));
    }
?>