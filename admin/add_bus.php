<?php 
    include('../db.php');
    session_start();

    // check admin login
    if(!$_SESSION['admin_id']){
        header('Location: login.php');
        exit();
    }
    
    //form submission
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $bus_number = mysqli_real_escape_string($conn, $_POST['bus_number']);
        $bus_type = mysqli_real_escape_string($conn, $_POST['bus_type']);
        $total_seats = mysqli_real_escape_string($conn, $_POST['total_seats']);

        $check_sql = "SELECT * FROM bus WHERE bus_number = '$bus_number'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            $message = "This Bus already exists!";
        } else {
            //Insert data into bus table
            $sql = "INSERT INTO bus (bus_number,bus_type,total_seats)
            VALUES ('$bus_number', '$bus_type', $total_seats)";

            if (mysqli_query($conn, $sql)) {
                $message = "Bus added successfully!";
            } else {
                $message = "Error: " . mysqli_error($conn);
            }
        }      
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Bus</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h2>Add New Bus</h2>

    <form action="add_bus.php" method="post">
        <label>Bus Number:</label><br>
        <input type="text" name="bus_number" required><br><br>

        <label>Bus Type:</label>
        <select name="bus_type" required>
            <option value="">Select Type</option>
            <option value="AC">AC</option>
            <option value="Non-AC">Non-AC</option>
        </select><br><br>

        <label>Total Seats:</label><br>
        <input type="number" name="total_seats" required><br><br>

        <button type="submit">Add Bus</button>
    </form>

    <?php if (isset($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <br><a href="dashboard.php">Back to Dashboard</a>

</body>
</html>