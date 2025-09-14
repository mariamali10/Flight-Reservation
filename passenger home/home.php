<?php
// Assuming you have started a session, and the passenger information is stored in the session upon login
session_start();

// Check if the passenger is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page or handle accordingly
    header("Location: login.php");
    exit();
}

// Replace this with your actual database connection code
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "imagine_flight";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch passenger ID from the session
$passengerId = $_SESSION['user_id'];
// Use the provided SQL query to get the details of the reserved flight
$flightDetails = [];

$sql = "SELECT flights.* FROM flights JOIN passenger_flights ON flights.flight_ID = passenger_flights.flight_ID WHERE passenger_flights.passenger_id = $passengerId";

$result = $conn->query($sql);

$currentFlights = [];
$completedFlights = [];

if ($result->num_rows > 0) {
  $flightDetails = $result->fetch_assoc();

  // while ($row = $result->fetch_assoc()) {
  //   if ($flightDetails['complited'] == 0) {
  //     $currentFlights[] = $flightDetails;
  //   }   else {
  //       $completedFlights[] = $flightDetails;
  //   }
  // }
  $currentFlights = [];
  $completedFlights = [];

  // Reset the result pointer to the beginning
  $result->data_seek(0);

  while ($row = $result->fetch_assoc()) {
      if ($row['complited'] == 0) {
          $currentFlights[] = $row;
      } else {
          $completedFlights[] = $row;
      }
  }
}

// echo(count($currentFlights));


$passengerDetails = [];

$sqlPassenger = "SELECT * FROM passenger WHERE passenger_ID = $passengerId";
$resultPassenger = $conn->query($sqlPassenger);

if ($resultPassenger->num_rows > 0) {
    $passengerDetails = $resultPassenger->fetch_assoc();
} else {
    // Handle the case where the passenger is not found
    echo "Passenger not found!";
    exit();
}



// Close the database connection
$conn->close();
?>

<!-- SELECT flights.* FROM flights JOIN passenger_flights ON flights.flight_ID = passenger_flights.flight_ID WHERE passenger_flights.passenger_id = 33; -->

<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="home.css">
  <title>Passenger Home</title>
</head>
<body>

  <div class="container">
    <header>
      <img src='../Registration/<?php echo $passengerDetails["passengerImage"]; ?>' alt="Passenger Avatar" style="border-radius: 50%;">
      
      <h1>Welcome, <?php echo $passengerDetails['passengerName']; ?></h1>
    </header>

    <nav>
      <ul>
        <li><a href="#">Current Flights</a></li>
        <li><a href="#">Completed Flights</a></li>
        <li><a href="../search/search.php">Search Flights</a></li>
        <li><a href="passenger_profile.php">Profile</a></li>
      </ul>
    </nav>

    <section id="flightsList">
      <!-- Display flights as a list -->
      <h2>Current Flights</h2>
      <table>
          <thead>
              <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Itinerary</th>
              </tr>
          </thead>
          <tbody>
              <?php foreach ($currentFlights as $flight): ?>
                  <tr class="flightRow" data-flight-id="<?php echo $flight['flight_ID']; ?>">
                      <td><?php echo $flight['flight_ID']; ?></td>
                      <!-- '<a href="../flight info/flightinfo.php?flight_id=' . $flight['flight_ID'] . '"><p>' . $flight['flightName'] . '</p></a>' -->
                      <!-- <td>
                        <?php 
                        // echo $flight['flightName']; ?>
                      </td> -->
                      <td><?php echo '<a href="../flight info/reserved_flight_info.php?flight_id=' . $flight['flight_ID'] . '"><p>' . $flight['flightName'] . '</p></a>';?></td>

                      <td><?php echo $flight['Itinerary']; ?></td>
                  </tr>
              <?php endforeach; ?>
          </tbody>
      </table>

      <h2>Completed Flights</h2>
      <table>
          <thead>
              <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Itinerary</th>
              </tr>
          </thead>
          <tbody>
              <?php foreach ($completedFlights as $flight): ?>
                  <tr class="flightRow" data-flight-id="<?php echo $flight['flight_ID']; ?>">
                      <td><?php echo $flight['flight_ID']; ?></td>
                      <!-- <td><?php
                      //  echo $flight['flightName']; ?></td> -->
                      <td><?php echo '<a href="../flight info/reserved_flight_info.php?flight_id=' . $flight['flight_ID'] . '"><p>' . $flight['flightName'] . '</p></a>';?></td>
                      <td><?php echo $flight['Itinerary']; ?></td>
                  </tr>
              <?php endforeach; ?>
          </tbody>
      </table>
    </section>
  </div>

  <!-- Include jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <!-- Include your script.js file -->
  <script src="script.js"></script>
</body>
</html>



