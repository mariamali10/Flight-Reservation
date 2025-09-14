<?php
session_start();
require_once '../backend/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data and sanitize inputs
    $flightName = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $itinerary = filter_input(INPUT_POST, 'itinerary', FILTER_SANITIZE_STRING);
    $date = filter_input(INPUT_POST, 'departureDate', FILTER_SANITIZE_STRING);
    $fees = filter_input(INPUT_POST, 'fees', FILTER_VALIDATE_FLOAT);
    $numPassengers = filter_input(INPUT_POST, 'passengers', FILTER_VALIDATE_INT);
    $takeoffTime = filter_input(INPUT_POST, 'takeoff', FILTER_SANITIZE_STRING);
    $landingTime = filter_input(INPUT_POST, 'landing', FILTER_SANITIZE_STRING);

    // Check if all inputs are valid
    if ($flightName && $itinerary && $date && $fees !== false && $numPassengers !== false && $takeoffTime && $landingTime) {
        // Assuming the user ID is stored in the session
        $companyID = $_SESSION['user_id'];

        // Insert data into the flights table using prepared statements
        $insertFlightQuery = "INSERT INTO flights (flightName, Itinerary, fees, maxPassegers, startTime, endTime, company_id, date)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $insertFlightStmt = mysqli_prepare($conn, $insertFlightQuery);

        // Bind parameters and execute statement
        mysqli_stmt_bind_param($insertFlightStmt, 'ssdissis', $flightName, $itinerary, $fees, $numPassengers, $takeoffTime, $landingTime, $companyID, $date);

        if (mysqli_stmt_execute($insertFlightStmt)) {
            // Flight added successfully
            // Redirect to company home page
            header("Location: ../company home/company_home.php");
            exit(); // Ensure that no other code is executed after the redirect
        } else {
            // Error in adding the flight
            echo json_encode(['success' => false, 'error' => 'Failed to add flight.']);
        }

        // Close the statement
        mysqli_stmt_close($insertFlightStmt);
    } else {
        // Invalid input data
        echo json_encode(['success' => false, 'error' => 'Invalid input data.']);
    }
} else {
    // Invalid request method
    echo json_encode(['success' => false, 'error' => 'Invalid Request']);
}
?>
