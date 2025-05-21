<?php
include "db.php";

$from = $_GET['fromLocation'];
$to = $_GET['toLocation'];
$date = $_GET['travelDate'];

$query = "SELECT schedule.*, bus.bus_type 
          FROM schedule
          JOIN bus ON schedule.bus_id = bus.bus_id
          WHERE schedule.from_city = ? 
            AND schedule.to_city = ? 
            AND schedule.departure_date = ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "sss", $from, $to, $date);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<div class="form-container">
  <h3 style="margin-bottom: 20px;">Available Buses</h3>

  <?php if (mysqli_num_rows($result) > 0): ?>
    <table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse;">
      <tr style="background-color: #f0f0f0;">
        <th>Bus ID</th>
        <th>From</th>
        <th>To</th>
        <th>Date</th>
        <th>Time</th>
        <th>Type</th>
        <th>Fare</th>
      </tr>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?= $row['bus_id'] ?></td>
          <td><?= $row['from_city'] ?></td>
          <td><?= $row['to_city'] ?></td>
          <td><?= $row['departure_date'] ?></td>
          <td><?= $row['departure_time'] ?></td>
          <td><?= $row['bus_type'] ?></td>
          <td><?= $row['price_per_seat'] ?></td>
        </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p>No buses available for the selected route and date.</p>
  <?php endif; ?>
</div>
