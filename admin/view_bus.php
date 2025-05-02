<?php
    include("../db.php");
    session_start();
    
    if (!isset($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit();
    }

    //Fetching buses from the bus table 
    $sql = "SELECT * FROM bus ORDER BY bus_number ASC";
    $result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Buses</title>
    <link rel="stylesheet" href="admin_style.css">
</head>

<body>
    <h2>Bus List</h2>

    <div class="table-container">

        <table border="1" cellpadding="10">
            <tr>
                <th>Bus Number</th>
                <th>Bus Type</th>
                <th>Total Seats</th>
                <th>Actions</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row["bus_number"]) ?></td>
                    <td><?= htmlspecialchars($row["bus_type"]) ?></td>
                    <td><?= htmlspecialchars($row["total_seats"]) ?></td>
                    <td>
                        <a href="edit_bus.php?bus_id=<?= $row['bus_id'] ?>" 
                            class="action-link edit-link">Edit</a> 
                            <a href="delete_bus.php?bus_id=<?= $row['bus_id'] ?>" 
                            class="action-link delete-link" 
                            onclick="return confirm('Are you sure you want to delete this bus?')">
                            Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>

</html>