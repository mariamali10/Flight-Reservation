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

// Fetch passenger details from the database using the session passenger_id
$passengerId = $_SESSION['user_id'];

$sql = "SELECT * FROM passenger WHERE passenger_ID = $passengerId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $passengerDetails = $result->fetch_assoc();
} else {
    // Handle the case where the passenger is not found
    echo "Passenger not found!";
    exit();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="profile.css">
    <title>Passenger Profile</title>
</head>
<body>

<div class="container">
    <div class="user-icon">
        <img src='../Registration/<?php echo $passengerDetails["passengerImage"]; ?>' alt="User Icon">
    </div>
    <h2>Welcome, <?php echo $passengerDetails['passengerName']; ?></h2>

    <div class="passportdiv">
        <label for="passportimage">Passport Image: </label>

        <img src='../Registration/<?php echo $passengerDetails['pasportImage']; ?>' alt="Passport Image" style="height: 90px; width: 90px;">
    </div>

    

    <form method="POST" action="update_passenger_profile.php">
        <label for="newUsername">Username:</label>
        <input type="text" id="newUsername" name="newUsername" value="<?php echo $passengerDetails['passengerName']; ?>" required>

        <label for="newEmail">Email:</label>
        <input type="email" id="newEmail" name="newEmail" value="<?php echo $passengerDetails['passengerMail']; ?>" required>

        <label for="newTelephoneNumber">Telephone Number:</label>
        <input type="tel" id="newTelephoneNumber" name="newTelephoneNumber" value="<?php echo $passengerDetails['phone']; ?>" required>


        <label for="accountbalance">Account Balance:</label>
        <input type="number" id="accountbalance" name="accountbalance" value="<?php echo $passengerDetails['passengerBalance']; ?>" required placeholder="Account Balance">


        <label for="newPassword">New Password:</label>
        <input type="password" id="newPassword" name="newPassword" required>

        <button type="submit" class="bn632-hover bn18">Update Profile</button>
    </form>
</div>

</body>
</html>
