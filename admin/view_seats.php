<?php
    include('../db.php');
    session_start();

    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit();
    }

    //Fetch all buses for the dropdown
    $bus_query = "SELECT bus_id, bus_number FROM bus";
    $bus_result = mysqli_query($conn, $bus_query);

    //Handle filter selection
    $schedule_filter = "";
    if (isset($_POST['bus_id']) && $_POST['bus_id'] !== "") {
        $bus_id = intval($_POST['bus_id']);
        $schedule_filter = "WHERE s.bus_id = $bus_id";
    }

    // Fetch seats with optional filter
    $query = "SELECT st.seat_id, st.schedule_id, st.seat_number, 
              st.status, s.bus_id, b.bus_number 
              FROM seats st 
              JOIN schedule s ON st.schedule_id = s.schedule_id 
              JOIN bus b ON s.bus_id = b.bus_id 
              $schedule_filter";
    $result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Seats</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <h2>Seats Information</h2>

    <!-- Dropdown to filter by Bus -->
    <form method="post" action="" class="filter-form">
        <label for="bus_id" class="inline-label">Filter by Bus:</label>
        <select name="bus_id" id="bus_id">
            <option value="">-- Select Bus --</option>
            <?php while ($bus = mysqli_fetch_assoc($bus_result)) { ?>
                <option value="<?= $bus['bus_id'] ?>">
                    <?= htmlspecialchars($bus['bus_number']) ?>
                </option>
            <?php } ?>
        </select>
        <button type="submit">Filter</button>
    </form>
    
    <br>

    <!-- Display the seats -->
    <table border="1">
        <thead>
            <tr>
                <th>Seat ID</th>
                <th>Schedule ID</th>
                <th>Seat Number</th>
                <th>Status</th>
                <th>Bus Number</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $row['seat_id'] ?></td>
                    <td><?= $row['schedule_id'] ?></td>
                    <td><?= $row['seat_number'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td><?= $row['bus_number'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <br>
    <a href="dashboard.php">⬅️ Back to Dashboard</a>
</body>
</html>
