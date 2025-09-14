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

mysqli_stmt_close($companyStmt);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="CompanyProfile.css">
  <title>Company Profile</title>
</head>
<body>

  <div class="container">
    <h1 style="color: #1c7ac7; font-family: 'Comic Neue', sans-serif;">About Us</h1>

    <form action="update_company.php" method="post">
      <div id="companyProfile">
        <img src='../Registration/<?php echo $companyData[0]["companyLogo"]; ?>' alt="Company Logo" id="logo">

        <label for="companyName">Company Name:</label>
        <textarea name="companyName" id="companyName" cols="50" rows="1"><?php echo $companyData[0]["companyName"]; ?> </textarea>
        <!-- <label for="companyName"></label> -->

        <label for="companyBio">Bio:</label>
        <textarea id="companyBio" name="companyBio" rows="7" cols="50"><?php echo $companyData[0]["companyBio"]; ?></textarea>

        <label for="companyAddress">Address:</label>
        <textarea id="companyAddress" name="companyAddress" rows="1" cols="50"><?php echo $companyData[0]["companyAddress"]; ?></textarea>

        <label for="flightsList">Flights List:</label>
        <ul id="flightsList">
          <?php foreach ($flightsData as $flight): ?>
              <li><?= $flight['flightName']; ?></li>
          <?php endforeach; ?>
        </ul>
        
        <button type="submit" class="bn632-hover bn18">Edit</button>
      </div>
    </form>
  </div>

</body>
</html>
