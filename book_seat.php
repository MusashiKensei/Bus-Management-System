<?php
session_start();
include("db.php");


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


if (!isset($_POST['schedule_id']) && $_POST['selected_seats'] && $_POST['total_price']) {
    echo "No schedule ID provided.";
    exit();
}

$user_id = $_SESSION['user_id'];
$schedule_id = $_POST['schedule_id'];
$selected_seats = explode(',', $_POST['selected_seats']); // Array of seat IDs
$total_price = $_POST['total_price'];



?>

<!DOCTYPE html>
<html>
<head>
    <title>Passenger Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        form {
            display: inline-block;
            margin-top: 30px;
            text-align: left;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"] {
            width: 300px;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            margin-top: 20px;
            padding: 10px 25px;
            background-color: #28a745;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <h2>Passenger Details</h2>
    <p>You are booking <?= count($selected_seats) ?> seat(s) for a total of ৳<?= $total_price ?></p>

    <form action="checkout_booking.php" method="POST">
        <input type="hidden" name="schedule_id" value="<?= htmlspecialchars($schedule_id) ?>">
        <input type="hidden" name="selected_seats" value="<?= htmlspecialchars($_POST['selected_seats']) ?>">
        <input type="hidden" name="total_price" value="<?= htmlspecialchars($total_price) ?>">

        <label for="passenger_name">Full Name:</label>
        <input type="text" id="passenger_name" name="passenger_name" required>

        <label for="passenger_mobile">Mobile Number:</label>
        <input type="tel" id="passenger_mobile" name="passenger_mobile" required pattern="[0-9]{10,15}">

        <label for="passenger_email">Email Address:</label>
        <input type="email" id="passenger_email" name="passenger_email" required>

        <input type="submit" value="Confirm Booking">
    </form>

</body>
</html>



