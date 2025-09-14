<?php

// Include your database connection file
require_once '..\backend\db_connection.php';

// Check if flight_id is set in the query string
if (isset($_GET['flight_id'])) {
    // Get the flight_id from the query string
    $flightId = mysqli_real_escape_string($conn, $_GET['flight_id']);

    // Retrieve flight information based on flight_id
    $query = "SELECT * FROM flights WHERE Flight_ID = '$flightId'";
    $result = mysqli_query($conn, $query);

    // Check if the flight is found
    if ($result && mysqli_num_rows($result) > 0) {
        // Fetch and display flight information
        $row = mysqli_fetch_assoc($result);
        $flightName = $row['flightName'];
        $itinerary = $row['Itinerary'];
        $fees = $row['fees'];
        $passengerCount = $row['registerdPassengers'];
        $takeoffTime = $row['startTime'];
        $landingTime = $row['endTime'];
    } else {
        // Flight not found
        echo "Flight not found.";
        exit;
    }
} else {
    // Invalid request, flight_id not provided
    echo "Invalid Request";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Flight Info</title>
</head>
<body>

  <div class="container">
    <h1>Flight Info</h1>

    <div id="flightDetails">
      <p>Flight Name: <?php echo $flightName; ?></p>
      <p>Flight ID: <?php echo $flightId; ?></p>
      <p>Itinerary: <?php echo $itinerary; ?></p>
      <p>Fees: $<?php echo $fees; ?></p>
      <p>Number of Passengers: <?php echo $passengerCount; ?></p>
      <p>Takeoff Time: <?php echo $takeoffTime; ?></p>
      <p>Landing Time: <?php echo $landingTime; ?></p>
    </div>

</body>
</html>
