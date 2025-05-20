<?php 
    include('../db.php');
    session_start();

    if(!isset($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit();
    }
    
    //Selects buses for dropdown options
    $bus_query = "SELECT bus_id, bus_number FROM bus";
    $bus_result = mysqli_query($conn, $bus_query);
    
    // Submission form logic
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $bus_id = intval($_POST['bus_id']);
        $from_city = mysqli_real_escape_string($conn, $_POST['from_city']);
        $to_city = mysqli_real_escape_string($conn, $_POST['to_city']);
        $departure_date = $_POST['departure_date'];
        $departure_time = $_POST['departure_time'];
        $price = floatval($_POST['price_per_seat']);

        $insert_sql = "INSERT INTO schedule 
            (bus_id, from_city, to_city, departure_date, departure_time, price_per_seat)
            VALUES ('$bus_id', '$from_city', '$to_city', 
            '$departure_date', '$departure_time', '$price')";

        if(mysqli_query($conn, $insert_sql)) {
            header('Location: view_schedule.php');
            exit();
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Schedule</title>
    <link rel="stylesheet" href="admin_style.css">
</head>

<body>
    <div class="form-container">
        <h2>Add New Schedule</h2>

        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="post" action="">
            <label>Bus:</label><br>
            <select name="bus_id" required>
                <option value="">Select Bus</option>
                <?php while ($bus = mysqli_fetch_assoc($bus_result)): ?>
                    <option value="<?= $bus['bus_id'] ?>"><?= htmlspecialchars($bus['bus_number']) ?></option>
                <?php endwhile; ?>
            </select><br><br>

            <label>From City:</label><br>
            <input type="text" name="from_city" required><br><br>

            <label>To City:</label><br>
            <input type="text" name="to_city" required><br><br>

            <label>Departure Date:</label><br>
            <input type="date" name="departure_date" required><br><br>

            <label>Departure Time:</label><br>
            <input type="time" name="departure_time" required><br><br>

            <label>Price per Seat:</label><br>
            <input type="number" name="price_per_seat" step="0.01" required><br><br>

            <button type="submit">Add Schedule</button>
        </form>

        <br>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>

</body>

</html>