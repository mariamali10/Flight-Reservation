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

// Fetch passenger details from the session
$passengerId = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize form input (not implemented in this example for brevity)
    
    // Update passenger details in the database
    $newUsername = $_POST['newUsername'];
    $newEmail = $_POST['newEmail'];
    $newTelephoneNumber = $_POST['newTelephoneNumber'];
    $newAccountBalance = $_POST['accountbalance'];
    $newPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);

    $updateSql = "UPDATE passenger SET 
                  passengerName = '$newUsername',
                  passengerMail = '$newEmail',
                  phone = '$newTelephoneNumber',
                  passengerBalance = '$newAccountBalance',
                  passengerPassword = '$newPassword'
                  WHERE passenger_ID = $passengerId";

    if ($conn->query($updateSql) === TRUE) {
        // echo("<script>alert('Data updated successfully');</script>");
        // sleep (5);
        // header("Location: passenger_profile.php");
        echo("<script>
            alert('Data updated successfully');
            setTimeout(function() {
                window.location.href = 'passenger_profile.php';
            }, 100); 
        </script>");

    } else {
        echo("<script>alert('Error updating data: " . $conn->error . "');</script>");
    }
}

// Close the database connection
$conn->close();
?>
