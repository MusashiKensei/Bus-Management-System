<?php 
    include('../db.php');
    session_start();

    if(!isset($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit();
    }

    // schedule table with bus table to get bus number
    $sql = "SELECT s.*, b.bus_number
            FROM schedule s
            JOIN bus b
            ON s.bus_id = b.bus_id
            ORDER BY s.departure_date, s.departure_time";
    
    $result = mysqli_query($conn, $sql);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Schedules</title>
    <link rel="stylesheet" href="admin_style.css">
</head>

<body>
    <div class="table-container">
        <h2>Bus Schedules</h2>
        <table>
            <thead>
                <tr>
                    <th>Bus Number</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Departure Date</th>
                    <th>Departure Time</th>
                    <th>Price per Seat</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['bus_number']) ?></td>
                        <td><?= htmlspecialchars($row['from_city']) ?></td>
                        <td><?= htmlspecialchars($row['to_city']) ?></td>
                        <td><?= htmlspecialchars($row['departure_date']) ?></td>
                        <td><?= htmlspecialchars($row['departure_time']) ?></td>
                        <td><?= htmlspecialchars($row['price_per_seat']) ?></td>
                        <td>
                            <div class="schedule-actions">
                                <a href="edit_schedule.php?schedule_id=<?= 
                                    $row['schedule_id'] ?>" class="action-link edit-link">Edit</a>
                                <a href="delete_schedule.php?schedule_id=<?= 
                                    $row['schedule_id'] ?>" class="action-link delete-link" 
                                    onclick="return confirm('Are you sure you want to delete this schedule?');">Delete</a>
                            </div>
                        </td>


                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <br>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>

</html>