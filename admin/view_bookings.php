<?php 
    include("../db.php");

    // Optional: enable session check
     session_start();
     if (!isset($_SESSION['admin_id'])) {
         header("Location: login.php");
         exit();
     }

    // SQL query to fetch booking info with seat numbers
    $sql = "SELECT 
                b.booking_id,
                b.name,
                b.mobile,
                b.total_price,
                b.booking_time,
                bu.bus_number,
                s.departure_time,
                GROUP_CONCAT(bs.seat_id ORDER BY bs.seat_id ASC SEPARATOR ', ') AS seats
            FROM booking b
            JOIN booking_seats bs ON bs.booking_id = b.booking_id
            JOIN schedule s ON b.schedule_id = s.schedule_id
            JOIN bus bu ON s.bus_id = bu.bus_id
            GROUP BY b.booking_id
            ORDER BY b.booking_time DESC";

    // Run the query and store result
    $result = mysqli_query($conn, $sql);

    // Check for query errors (optional but useful)
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Bookings</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <h2>All Bookings</h2>

    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Bus Number</th>
                <th>Departure Time</th>
                <th>Seats</th>
                <th>Total Price</th>
                <th>Booking Time</th>
            </tr>
</thead>

        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $row['booking_id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['mobile']) ?></td>
                    <td><?= $row['bus_number'] ?></td>
                    <td><?= $row['departure_time'] ?></td>
                    <td><?= $row['seats'] ?></td>
                    <td><?= $row['total_price'] ?></td>
                    <td><?= $row['booking_time'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <br>
    <a href="dashboard.php">⬅️ Back to Dashboard</a>
</body>
</html>
