<?php 
session_start();
include("db.php");
include("extra/navlogin.php");

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Validate schedule_id
if (!isset($_GET['schedule_id'])) {
    echo "No schedule ID provided.";
    exit();
}

$schedule_id = $_GET['schedule_id'];

// Fetch schedule info
$schedule_query = "
SELECT 
    seats.*, 
    schedule.from_city, 
    schedule.to_city, 
    schedule.departure_time,
    schedule.departure_date,
    schedule.price_per_seat, 
    bus.bus_number, 
    bus.bus_type
FROM seats 
JOIN schedule ON seats.schedule_id = schedule.schedule_id
JOIN bus ON schedule.bus_id = bus.bus_id
WHERE seats.schedule_id = '$schedule_id'
";

$result = mysqli_query($conn, $schedule_query);

if (!$result){
    die("Query error: " . mysqli_error($conn));
}

$seats = [];
while($row = mysqli_fetch_assoc($result)) {
    $seats[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bus Seat Layout</title>
    <style>
        .bus {
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 500px;
            margin: auto;
        }
        .row {
            display: flex;
            margin: 5px;
            justify-content: space-between;
            width: 100%;
        }
        .seat {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            background-color: #28a745;
            text-align: center;
            line-height: 60px;
            font-weight: bold;
            color: white;
            cursor: pointer;
        }
        .seat.booked {
            background-color: #dc3545;
            cursor: not-allowed;
        }
        .seat.selected {
            background-color:rgb(22, 100, 216);
        }
        .finalize-btn {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            padding: 10px 20px;
            font-size: 16px;
        }
        .finalize-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">Bus Seat Layout</h2>
<h3 style="text-align:center;">From <?= $seats[0]['from_city'] ?> to <?= $seats[0]['to_city'] ?></h3>
<h4 style="text-align: center;">Departure Time: <?= $seats[0]['departure_time']?> | Departure Date: <?= $seats[0]['departure_date']?></h4>
<h3 style="text-align: center;"> Price Per Seat: ৳<?= $seats[0]['price_per_seat']?></h3>

<!-- Bus Seat Layout -->
<div class="bus">
    <?php for ($i = 0; $i < count($seats); $i += 4): ?>
        <div class="row">
            <?php for ($j = 0; $j < 4; $j++): ?>
                <?php
                if (!isset($seats[$i + $j])) continue;
                $seat = $seats[$i + $j];
                $class = $seat['status'] == "Booked" ? 'seat booked' : 'seat';
                ?>
                <div class="<?= $class ?>" id="seat-<?= $seat['seat_id'] ?>"
                    <?php if ($seat['status'] != "Booked"): ?>
                        onclick="selectSeat(<?= $seat['seat_id'] ?>, '<?= $seat['seat_number'] ?>')"
                    <?php endif; ?>>
                    <?= $seat['seat_number'] ?>
                </div>
            <?php endfor; ?>
        </div>
    <?php endfor; ?>
</div>

<!-- Bottom Section for Selected Seats and Total -->
<div id="selected-info" style="text-align: center; margin-top: 30px;">
    <h4>Selected Seats: <span id="selected-seats-text">None</span></h4>
    <h4>Total Price: ৳<span id="total-price">0</span></h4>

    <form id="finalize-form" action="book_seat.php" method="POST" style="display: none; margin-top: 20px;">
        <input type="hidden" name="schedule_id" value="<?= $schedule_id ?>">
        <input type="hidden" name="selected_seats" id="selected-seats-input">
        <input type="hidden" name="total_price" id="total-price-input">
        <button type="submit" class="finalize-btn">Finalize Booking</button>
    </form>
</div>

<script>
    let selectedSeats = [];
    const pricePerSeat = <?= $seats[0]['price_per_seat'] ?>;

    function selectSeat(seatId, seatNumber) {
        const seatElement = document.getElementById('seat-' + seatId);
        
        if (seatElement.classList.contains('selected')) {
            seatElement.classList.remove('selected');
            selectedSeats = selectedSeats.filter(seat => seat.id !== seatId);
        } else {
            seatElement.classList.add('selected');
            selectedSeats.push({ id: seatId, number: seatNumber });
        }

        updateSelectedInfo();
    }

    function updateSelectedInfo() {
        document.getElementById('total-price-input').value = selectedSeats.length * pricePerSeat;

        const selectedSeatsText = document.getElementById('selected-seats-text');
        const totalPrice = document.getElementById('total-price');
        const selectedSeatsInput = document.getElementById('selected-seats-input');
        const totalPriceInput = document.getElementById('total-price-input');
        const finalizeForm = document.getElementById('finalize-form');

        if (selectedSeats.length > 0) {
            selectedSeatsText.textContent = selectedSeats.map(seat => seat.number).join(', ');
            totalPrice.textContent = selectedSeats.length * pricePerSeat;
            selectedSeatsInput.value = selectedSeats.map(seat => seat.id).join(',');
            finalizeForm.style.display = 'block';
            totalPriceInput.value = total;
        } else {
            selectedSeatsText.textContent = 'None';
            totalPrice.textContent = '0';
            totalPriceInput.value = '';
            finalizeForm.style.display = 'none';
        }
    }
</script>

</body>
</html>

<?php include("extra/foot.php"); ?>
