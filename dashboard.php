<?php 
include("extra/navlogin.php");
include("db.php");
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch locations
$query = "SELECT DISTINCT loc_name FROM locations";
$result_from = mysqli_query($conn, $query);
$result_to = mysqli_query($conn, $query);

//fetch bus types
$query_bus = "SELECT DISTINCT bus_type FROM bus";
$result_bus = mysqli_query($conn, $query_bus);

// Fetch available dates
$query_dates = "SELECT DISTINCT departure_date FROM schedule";
$result_date = mysqli_query($conn, $query_dates);
$dates = [];

while($row = mysqli_fetch_assoc($result_date)) {
    $dates[] = $row["departure_date"];
}
$dates_json = json_encode($dates);
?>

<!-- Flatpickr CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Styling -->
<style>
  .form-container {
    max-width: 1000px;
    margin: 40px auto;
    padding: 30px;
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow: 0 6px 30px rgba(0, 0, 0, 0.1);
    font-family: "Segoe UI", sans-serif;
  }
  .form-title {
    font-size: 22px;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 30px;
  }
  .form-row {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
  }
  .form-group {
    flex: 1;
    min-width: 250px;
  }
  .select, input[type="text"], input[type="date"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
  }
  input:focus {
    border-color: #3498db;
    outline: none;
  }
  .form-actions {
    text-align: center;
    margin-top: 25px;
  }
  button {
    padding: 10px 20px;
    font-weight: bold;
    color: white;
    background-color: #3498db;
    border: none;
    border-radius: 6px;
    font-size: 15px;
    cursor: pointer;
  }
  button:hover {
    background-color: #2980b9;
  }
</style>

<!-- Search Form -->
<div class="form-container">
  <h5 class="form-title">Search Trip</h5>
  <form method="GET">
    <div class="form-row">

      <!-- From -->
      <div class="form-group">
        <label for="fromLocation">From Location</label>
        <select name="fromLocation" id="fromLocation" required>
          <option value="" disabled selected hidden>Select departure city</option>
          <?php while ($row = mysqli_fetch_assoc($result_from)) : ?>
            <option value="<?= $row['loc_name']; ?>" <?= (isset($_GET['fromLocation']) && $_GET['fromLocation'] == $row['loc_name']) ? 'selected' : '' ?>>
              <?= $row['loc_name']; ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <!-- To -->
      <div class="form-group">
        <label for="toLocation">To Location</label>
        <select name="toLocation" id="toLocation" required>
          <option value="" disabled selected hidden>Select destination city</option>
          <?php while ($row = mysqli_fetch_assoc($result_to)) : ?>
            <option value="<?= $row['loc_name']; ?>" <?= (isset($_GET['toLocation']) && $_GET['toLocation'] == $row['loc_name']) ? 'selected' : '' ?>>
              <?= $row['loc_name']; ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>
      <!-- Bus Type -->
      <div class="form-group">
            <label for="busType">Bus Type</label>
            <select name="busType" id="busType" required>
              <option value="" disabled selected hidden>Select bus type</option>
              <?php while ($row = mysqli_fetch_assoc($result_bus)) : ?>
                <option value="<?= $row['bus_type']; ?>" <?= (isset($_GET['busType']) && $_GET['busType'] == $row['bus_type']) ? 'selected' : '' ?>>
                  <?= $row['bus_type']; ?>
                </option>
              <?php endwhile; ?>
            </select>
      </div>

      <!-- Date -->
      <div class="form-group">
        <label for="travelDate">Travel Date</label>
        <input type="text" id="travelDate" name="travelDate" placeholder="Select a date" required
               value="<?= $_GET['travelDate'] ?? '' ?>">
      </div>

      <!-- Submit -->
      <div class="form-actions" style="flex-basis: 100%;">
        <button type="submit">Search</button>
      </div>
    </div>
  </form>
</div>

<!-- Flatpickr setup -->
<script>
  const availableDates = <?= $dates_json; ?>;
  flatpickr("#travelDate", {
    dateFormat: "Y-m-d",
    enable: availableDates,
    defaultDate: "<?= $_GET['travelDate'] ?? '' ?>",
  });

  // Prevent selecting same city for both dropdowns
  const fromSelect = document.getElementById('fromLocation');
  const toSelect = document.getElementById('toLocation');

  function updateDisabledOptions() {
    const from = fromSelect.value;
    const to = toSelect.value;

    for (let option of fromSelect.options) {
      option.disabled = option.value === to && to !== "";
    }
    for (let option of toSelect.options) {
      option.disabled = option.value === from && from !== "";
    }
  }

  fromSelect.addEventListener('change', updateDisabledOptions);
  toSelect.addEventListener('change', updateDisabledOptions);
  updateDisabledOptions(); // run initially
</script>

<!-- Search Results (if any) -->
<?php
if (isset($_GET['fromLocation'], $_GET['toLocation'], $_GET['travelDate'], $_GET['busType'])):
    $from = $_GET['fromLocation'];
    $to = $_GET['toLocation'];
    $date = $_GET['travelDate'];
    $busType = $_GET['busType'];

    $query = "SELECT schedule.*, bus.bus_type 
              FROM schedule
              JOIN bus ON schedule.bus_id = bus.bus_id
              WHERE schedule.from_city = ? 
                AND schedule.to_city = ? 
                AND schedule.departure_date = ?
                AND bus.bus_type = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $from, $to, $date, $busType);
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
        <th>Action</th>
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
          <td>
            <form action="book.php" method="get">
              <input type="hidden" name="schedule_id" value="<?=$row['schedule_id']?>">
              <button type="submit">Book</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p>No buses available for the selected route and date.</p>
  <?php endif; ?>
</div>
<?php endif; ?>
<?php include 'extra/foot.php'; ?>
