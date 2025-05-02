<?php 
    include('../db.php');
    session_start();

    if (!isset($_SESSION['admin_id'])){
        header('Location: login.php');
        exit();
    }

    // Make sue bus_id is passed through GET
    if (!isset($_GET['bus_id']) || !is_numeric($_GET['bus_id'])) {
        die("Invalid bus ID.");
    }

    $bus_id = intval($_GET['bus_id']);
    
    // for displaying current info and check if all the info related
    // to the bus_id exists in the database
    $sql = "SELECT * FROM bus WHERE bus_id = '$bus_id'"; 
    $result = mysqli_query($conn, $sql);
    $bus = mysqli_fetch_assoc($result);

    if (!$bus) {
        die('Bus not found.');
    } 

    // Form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $bus_number = mysqli_real_escape_string($conn, $_POST['bus_number']);
        $bus_type = mysqli_real_escape_string($conn, $_POST['bus_type']);
        $total_seats = intval($_POST['total_seats']);

        $update_sql = "UPDATE bus SET bus_number = '$bus_number',
        bus_type = '$bus_type', total_seats = '$total_seats'
        WHERE bus_id = '$bus_id'";

        if (mysqli_query($conn, $update_sql)) {
            header("Location: view_bus.php");
            exit();
        } else {
            $error = "Update failed: " . mysqli_error($conn);
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bus</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>

    <h2>Edit Bus</h2>

    <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="edit-form-container">
        <form method="POST" action="">
            <label>Bus Number:</label><br>
            <input type="text" name="bus_number" 
            value="<?= htmlspecialchars($bus['bus_number']) ?>" required><br><br>

            <label>Bus Type:</label><br>
            <select name="bus_type" required>
                <option value="AC" <?= $bus['bus_type'] == 'AC' ? 'selected' : '' ?>>AC</option>
                <option value="Non-AC" <?= $bus['bus_type'] == 'Non-AC' ? 'selected' : '' ?>>Non-AC</option>
            </select><br><br>

            <label>Total Seats:</label><br>
            <input type="number" name="total_seats" value="<?= $bus['total_seats'] ?>" required><br><br>

            <button type="submit">Update Bus</button>
        </form>
    </div>

    <br>
    <a href="view_bus.php">Back to Bus List</a>

</body>
</html>