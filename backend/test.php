<?php
// Include the database connection file
require_once 'db_connection.php';

// Function to sanitize input data
function sanitizeInput($input) {
    return htmlspecialchars(trim($input));
}

// Dummy data for testing
$dummyData = [
    ['Mohamed', 'mhmd@example.com', 'password123', '123456789', 'photo1.jpg', 'passport1.jpg', 100],
    // Add more dummy data as needed
];

// Insert dummy data into the Passengers table
foreach ($dummyData as $data) {
    // Sanitize data
    $passengerName = sanitizeInput($data[0]);
    $passengerEmail = sanitizeInput($data[1]);
    $passengerPassword = sanitizeInput($data[2]);
    $passengerTele = sanitizeInput($data[3]);
    $passengerPhoto = sanitizeInput($data[4]);
    $passportImage = sanitizeInput($data[5]);
    $passengerBalance = sanitizeInput($data[6]);

    // Insert data into the Passengers table
    $query = "INSERT INTO passenger 
              (passengerName, passengerMail, PassengerPassword, phone, passengerImage, pasportImage, passengerBalance)
              VALUES 
              ('$passengerName', '$passengerEmail', '$passengerPassword', '$passengerTele', '$passengerPhoto', '$passportImage', '$passengerBalance')";

    // Execute the query
    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    if (!$result) {
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
        exit; // Stop further execution if an error occurs
    }
}

// If all dummy data is inserted successfully
echo json_encode(['success' => true, 'message' => 'Dummy data inserted successfully']);
?>
