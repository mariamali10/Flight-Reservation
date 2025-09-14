<?php
// Replace these with your actual database credentials
$host = "localhost";
$user = "root";
$password_db = "";
$database = "imagine_flight";

// Create a connection to the MySQL database
$conn = new mysqli($host, $user, $password_db, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["register"])) {
        // Registration API
        $username = $_POST["PassengerName"];
        $email = $_POST["PassengerEmail"];
        $telephoneNumber = $_POST["PassengerTele"];
        $password = $_POST["PassengerPassword"];

        // Hash the password before storing it in the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute the SQL statement for registration
        $stmt = $conn->prepare("INSERT INTO passengers (PassengerName, PassengerEmail, PassengerPassword, PassengerTele) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $telephoneNumber, $hashedPassword);

        if ($stmt->execute()) {
            echo "Registration successful! <br>";
            echo "Username: " . $username . "<br>";
            echo "Email: " . $email . "<br>";
            echo "Telephone Number: " . $telephoneNumber . "<br>";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement for registration
        $stmt->close();
    } elseif (isset($_POST["login"])) {
        // Login API
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Prepare and execute the SQL statement to check the login credentials
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $userRow = $result->fetch_assoc();
            if (password_verify($password, $userRow["password"])) {
                // Valid credentials, redirect or perform additional actions
                echo "Login successful! <br>";
                echo "Welcome, " . $userRow["username"] . "! <br>";
            } else {
                // Invalid password
                echo "Invalid password! <br>";
            }
        } else {
            // User not found
            echo "User not found! <br>";
        }

        // Close the statement for login
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>
