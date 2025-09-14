<?php

session_start();
require_once '..\backend\db_connection.php';

// Check if flight_id is set in the query string
if (isset($_GET['flight_id'])) {
    // Get the flight_id from the query string
    $flightId = mysqli_real_escape_string($conn, $_GET['flight_id']);
    echo('hello');
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
        $companyId = $row['company_id'];
        echo("iddd => ".$companyId . " ");
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
// hna bn check lw el user d5l abl kda ya hamada 
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    echo($userId);
    $Query = "SELECT * FROM passenger_flights WHERE passenger_id = '$userId' AND flight_id = '$flightId'";
    $Result = mysqli_query($conn, $Query);

    if ($Result && mysqli_num_rows($Result) > 0) {
        
        echo "You are already registered ";
        exit;
    }
}

// Check if the payment form is submitted

echo("HELLOOO");
// Assuming user_id is stored in the session
$userId = $_SESSION['user_id'];

// Retrieve user's balance from the database
$getUserBalanceQuery = "SELECT passengerBalance FROM passenger WHERE passenger_ID  = '$userId'";
$getUserBalanceResult = mysqli_query($conn, $getUserBalanceQuery);


$companyQuery = "SELECT companyBalance FROM company WHERE company_ID = '$companyId'";
$companyResult = mysqli_query($conn, $companyQuery);

if ($companyResult && mysqli_num_rows($companyResult) > 0) {
    $companyRow = mysqli_fetch_assoc($companyResult);
    $companyBalance = $companyRow['companyBalance'];
    echo("balance".$companyBalance." ");
} else {
    echo "Company not found.";
    exit;
}



if ($getUserBalanceResult && mysqli_num_rows($getUserBalanceResult) > 0) {
    $userRow = mysqli_fetch_assoc($getUserBalanceResult);
    $userBalance = $userRow['passengerBalance'];
    echo("balance".$userBalance." ");
    // Check if the user has enough balance
    if ($userBalance >= $fees) 
    {
        // Calculate new balances
        $newUserBalance = $userBalance - $fees;
        $newCompanyBalance = $companyRow['companyBalance'] + $fees;

        // Update user's balance
        $updateUserBalanceQuery = "UPDATE passenger SET passengerBalance = '$newUserBalance' WHERE passenger_ID = '$userId'";
        mysqli_query($conn, $updateUserBalanceQuery);

        // Update company's balance
        $updateCompanyBalanceQuery = "UPDATE company SET companyBalance = '$newCompanyBalance' WHERE company_id = '{$row['company_id']}'";
        mysqli_query($conn, $updateCompanyBalanceQuery);

        // passengers++
        $newPassengerCount = $passengerCount + 1;
        $updatePassengerCountQuery = "UPDATE flights SET registerdPassengers = '$newPassengerCount' WHERE flight_ID = '$flightId'";
        mysqli_query($conn, $updatePassengerCountQuery);

        $insertFlightPassengerQuery = "INSERT INTO passenger_flights (passenger_id, flight_id) VALUES ('$userId', '$flightId')";
        mysqli_query($conn, $insertFlightPassengerQuery);

        // Payment successful
        echo "<script>alert('Payment successful!'); window.location.href = '../passenger home/home.php';</script>";
    } else {
        // Insufficient balance
        echo "Insufficient balance.";
    }
} else {
    // Error in retrieving user's balance
    echo "Error in retrieving user's balance.";
}


?>