<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "imagine_flight";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $from = $_POST['from'];
    $to = $_POST['to'];

    $query = "SELECT * FROM flights WHERE Itinerary LIKE '%$from%' AND Itinerary LIKE '%$to%'";
    $result = $conn->query($query);

    $flights = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $flights[] = $row;
        }
    } else {
        echo "No flights found.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Search a Flight</title>
</head>
<body>

  <div class="container">
    <h1>Search a Flight</h1>

    <form id="searchForm" action="search.php" method="POST">
      <label for="from">From:</label>
      <input type="text" id="from" name="from" required placeholder="Departure City">

      <label for="to">To:</label>
      <input type="text" id="to" name="to" required placeholder="Destination City">

      <button type="submit" class="bn632-hover bn18">Search</button>
    </form>

    <div id="flightsList">
     
      <?php
        if (!empty($flights)) {
          foreach ($flights as $flight) {
            $itineraryCities = array_map('trim', explode(',', $flight['Itinerary']));
            $firstCity = reset($itineraryCities);
            $lastCity = end($itineraryCities);

            // Check if From is the first element and To is the last element
            if (strcasecmp($from, $firstCity) === 0 && strcasecmp($to, $lastCity) === 0) {
              echo '<a href="../flight info/flightinfo.php?flight_id=' . $flight['flight_ID'] . '"><p>' . $flight['flightName'] . '</p></a>';
            }
          }
        }
      ?>
    </div>
  </div>

</body>
</html>
