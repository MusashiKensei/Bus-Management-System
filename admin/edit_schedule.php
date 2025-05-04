<?php
    include('../db.php');
    session_start();

    if (!isset($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit();
    }

    // Check if schedule_id is set in URL
    if (!isset($_GET['schedule_id'])) {
        echo "Schedule ID not provided.";
        exit();
    }

    $schedule_id = $_GET['schedule_id'];

    // Fetch bus number and bus id
    $bus_query = "SELECT bus_id, bus_number FROM bus";
    $bus_result = mysqli_query($conn, $bus_query);

    // Fetch the schedule data
    $sql = "SELECT * FROM schedule WHERE schedule_id = $schedule_id";
    if (isset($_POST['update'])) {
        $from_city = mysqli_real_escape_string($conn, $_POST['from_city']);
        $to_city = mysqli_real_escape_string($conn, $_POST['to_city']);
        $departure_date = mysqli_real_escape_string($conn, $_POST['departure_date']);
        $departure_time = mysqli_real_escape_string($conn, $_POST['departure_time']);
        $price_per_seat = mysqli_real_escape_string($conn, $_POST['price_per_seat']);
    
        $update_sql = "UPDATE schedule 
                       SET from_city='$from_city', to_city='$to_city', 
                           departure_date='$departure_date', departure_time='$departure_time', 
                           price_per_seat='$price_per_seat'
                       WHERE schedule_id = $schedule_id";
    
        if (mysqli_query($conn, $update_sql)) {
            header("Location: view_schedule.php");
            exit();
        } else {
            echo "Error updating schedule: " . mysqli_error($conn);
        }
    }    
    $result = mysqli_query($conn, $sql);

    if (!$result || mysqli_num_rows($result) == 0) {
        echo "Schedule not found.";
        exit(); 
    }

    $schedule = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Schedule</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="form-container">
        <h2>Edit Schedule</h2>
        <form action="" method="post">
            <label>Bus Number</label><br><br>
            <select name="bus_id" required>
                <option value="">Select Bus</option>
                <?php while ($bus = mysqli_fetch_assoc($bus_result)) { ?>
                    <option value="<?= $bus['bus_id'] ?>"
                      <?= $bus['bus_id'] == $schedule['bus_id'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($bus['bus_number']) ?>
                    </option>
                <?php } ?>
            </select><br><br>
        
            <label>From:</label><br>
            <input type="text" name="from_city"
                value="<?= htmlspecialchars($schedule['from_city']) ?>" required><br><br>

            <label>To:</label><br>
            <input type="text" name="to_city"
                value="<?= htmlspecialchars($schedule['to_city']) ?>" required><br><br>

            <label>Departure Date:</label><br>
            <input type="date" name="departure_date"
                value="<?= htmlspecialchars($schedule['departure_date']) ?>" required><br><br>

            <label>Departure Time:</label><br>
            <input type="time" name="departure_time"
                value="<?= htmlspecialchars($schedule['departure_time']) ?>" required><br><br>

            <label>Price per Seat:</label><br>
            <input type="number" name="price_per_seat"
                value="<?= htmlspecialchars($schedule['price_per_seat']) ?>" required><br><br>

            <button type="submit" name="update">Update Schedule</button>
        </form>
    </div>
    <br>
    <a href="view_schedule.php">Back to Bus List</a>
</body>
</html>
