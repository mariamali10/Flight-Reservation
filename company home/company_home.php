<?php
session_start();

require_once '../backend/db_connection.php';

$companyID = $_SESSION['user_id']; // Assuming the company ID is stored in user_id
// echo($companyID);
if (!isset($_SESSION['user_id'])) {
    // User not authenticated, redirect to login page
    header('Location: ../login/login.html');
    exit;
}

$flightid = 1;

// Query to retrieve flights for the company
$flightsQuery = "SELECT * FROM flights WHERE company_id = ?";
$flightsStmt = mysqli_prepare($conn, $flightsQuery);
mysqli_stmt_bind_param($flightsStmt, 'i', $companyID);
mysqli_stmt_execute($flightsStmt);

$flightsResult = mysqli_stmt_get_result($flightsStmt);


$companyQuery = "SELECT * FROM company WHERE company_ID = ?";
$companyStmt = mysqli_prepare($conn, $companyQuery);
mysqli_stmt_bind_param($companyStmt, 'i', $companyID);
mysqli_stmt_execute($companyStmt);

$companyResult = mysqli_stmt_get_result($companyStmt);

// Check if there are any flights
if ($flightsResult && mysqli_num_rows($flightsResult) > 0) {
    $flightsData = mysqli_fetch_all($flightsResult, MYSQLI_ASSOC);
} else {
    $flightsData = []; // No flights found
}

if ($companyResult && mysqli_num_rows($companyResult) > 0) {
    $companyData = mysqli_fetch_all($companyResult, MYSQLI_ASSOC);
} else {
    $companyData = []; // No flights found
}
// Close the statement
mysqli_stmt_close($flightsStmt);
mysqli_stmt_close($companyStmt);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="company_home.css">
  <title>Company Home</title>
</head>
<body>

  <div class="container">
    <header>
      <!-- <img src="../company_logo.png" alt="Company Logo"> -->
      <img src='../Registration/<?php echo $companyData[0]["companyLogo"]; ?>' alt="Company Logo">

      <h1 style="color: #1c7ac7; font-family: 'Comic Neue', sans-serif;"><?php echo $companyData[0]["companyName"]; ?> </h1>
    </header>

    <nav>
      <ul>
        <li><a href="../Add flight/addflight.html">Add Flight</a></li>
        <li><a href="#flightsList">Flights List</a></li>
        <li><a href="../company profile/company_profile.php">Profile</a></li>
        <li><a href="#">Messages</a></li>
      </ul>
    </nav>

    <section id="flightsList">
      <!-- Display flights as a list -->
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Itinerary</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($flightsData as $flight): ?>
            <tr class="flightRow" data-flight-id="<?= $flight['flight_ID']; ?>">
              <td><?= $flightid++; ?></td>
              <td><?= $flight['flightName']; ?></td>
              <td><?= $flight['Itinerary']; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>
      <div class="ff">
        <p>Imagine Flights © 2023</p>
      </div>
  </div>

  <!-- Include jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <!-- Include your script.js file -->
  <script src="script.js"></script>
</body>
</html>

