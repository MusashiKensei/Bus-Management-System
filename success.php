<?php
include 'db.php'; // Database connection

session_start(); // Start session to get passenger_id
$passenger_id = $_SESSION['user_id']; // Ensure user_id is set during login

// Get values from URL parameters
$session_id = $_GET['session_id'];
$schedule_id = intval($_GET['schedule_id']);
$selected_seats = explode(',', $_GET['selected_seats']); // e.g., "289,290"
$total_price_bdt = floatval($_GET['total_price_bdt']);
$passenger_name = htmlspecialchars(trim($_GET['name']));
$passenger_mobile = preg_replace('/[^0-9]/', '', $_GET['mobile']); // digits only

// Get current time for booking
$booking_time = date('Y-m-d H:i:s');

// Step 1: Insert booking info
$insert_booking_sql = "INSERT INTO booking (passenger_id, schedule_id, total_price, booking_time, name, mobile)
                       VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insert_booking_sql);
$stmt->bind_param("iidsss", $passenger_id, $schedule_id, $total_price_bdt, $booking_time, $passenger_name, $passenger_mobile);

if ($stmt->execute()) {
    $booking_id = $stmt->insert_id;
    echo "✅ Booking created! Booking ID: $booking_id<br><br>";

    // Step 2: Process each selected seat (seat_id)
    foreach ($selected_seats as $seat_id_raw) {
        $seat_id = intval(trim($seat_id_raw));

        // Check seat exists and matches schedule
        $seat_query = "SELECT seat_id, seat_number, status, schedule_id FROM seats WHERE seat_id = ? AND schedule_id = ?";
        $seat_stmt = $conn->prepare($seat_query);
        $seat_stmt->bind_param("ii", $seat_id, $schedule_id);
        $seat_stmt->execute();
        $seat_result = $seat_stmt->get_result();

        if ($seat_row = $seat_result->fetch_assoc()) {
            $seat_number = $seat_row['seat_number'];
            echo "✅ Found Seat ID: {$seat_row['seat_id']}, Seat Number: $seat_number, Schedule ID: {$seat_row['schedule_id']}<br>";

            // Step 3: Insert into booking_seats table
            $insert_seat_sql = "INSERT INTO booking_seats (booking_id, seat_id, passenger_id)
                                VALUES (?, ?, ?)";
            $insert_seat_stmt = $conn->prepare($insert_seat_sql);
            $insert_seat_stmt->bind_param("iii", $booking_id, $seat_id, $passenger_id);
            if ($insert_seat_stmt->execute()) {
                echo "✅ Seat $seat_number booked successfully.<br>";
            } else {
                echo "❌ Failed to insert booking_seat for Seat ID $seat_id: " . $insert_seat_stmt->error . "<br>";
            }

            // Step 4: Update seat status to 'Booked'
            $update_seat_sql = "UPDATE seats SET status = 'Booked' WHERE seat_id = ?";
            $update_stmt = $conn->prepare($update_seat_sql);
            $update_stmt->bind_param("i", $seat_id);
            $update_stmt->execute();
        } else {
            echo "❌ Seat $seat_id not found for schedule $schedule_id.<br>";
        }
    }

    echo "<br>🎉 Booking process completed!";
} else {
    echo "❌ Error inserting booking: " . $stmt->error;
}

$conn->close();
?>
<!-- Dashboard Button -->
<br><br>
<a href="dashboard.php">
    <button style="padding: 10px 20px; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius: 4px;">
        Go to Dashboard
    </button>
</a>s