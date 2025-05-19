<?php
    include('../db.php');
    session_start();
    
    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit();
    }
    
    if (!isset($_GET['schedule_id']) || !is_numeric($_GET['schedule_id'])) {
        die("Invalid schedule ID.");
    }
    
    $schedule_id = intval($_GET['schedule_id']);
    
    // Get the associated bus_id from the schedule
    $bus_query = "SELECT bus_id FROM schedule WHERE schedule_id = $schedule_id";
    $bus_result = mysqli_query($conn, $bus_query);

    if (mysqli_num_rows($bus_result) > 0) {
        $bus_row = mysqli_fetch_assoc($bus_result);
        $bus_id = $bus_row['bus_id'];
    } else {
        die("Bus not found for the given schedule.");
    }
    
    // Check if seats already exist for this schedule
    $check_query = "SELECT COUNT(*) AS count FROM seats WHERE schedule_id = $schedule_id";
    $check_result = mysqli_query($conn, $check_query);
    $row = mysqli_fetch_assoc($check_result);
    
    if ($row['count'] > 0) {
        echo "Seats already generated for this schedule.";
        echo '<br><a href="view_schedule.php">Back to Schedule</a>';
        exit();
    }
    
    // Generate 4 seats: 1A, 1B, 2A, 2B
    $seats = ['1A', '1B', '2A', '2B'];
    $insert_query = "INSERT INTO seats (schedule_id, bus_id, seat_number, status) VALUES ";
    
    $values = [];
    foreach ($seats as $seat) {
        $values[] = "($schedule_id, $bus_id, '$seat', 'Available')";
    }
    
    $insert_query .= implode(", ", $values);

    if (mysqli_query($conn, $insert_query)) {
        header("Location: view_schedule.php?msg=seats_generated");
        exit();
    } else {
        die("Error generating seats: " . mysqli_error($conn));
    }
?>
