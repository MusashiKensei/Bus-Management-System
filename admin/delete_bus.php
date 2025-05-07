<?php
    include('../db.php');
    session_start();
    
    // Check if admin is logged in
    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit();
    }
    
    // Validate the bus_id from GET
    if (!isset($_GET['bus_id']) || !is_numeric($_GET['bus_id'])) {
        die("Invalid bus ID.");
    }
    
    $bus_id = intval($_GET['bus_id']);
    
    // Delete the bus record
    $delete_sql = "DELETE FROM bus WHERE bus_id = $bus_id";
    
    if (mysqli_query($conn, $delete_sql)) {
        header("Location: view_bus.php");
        exit();
    } else {
        die("Error deleting bus: " . mysqli_error($conn));
    }
?>
