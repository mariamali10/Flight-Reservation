<?php
require_once '../backend/db_connection.php';

session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Escape user input to prevent XSS
    $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');

    // Query to check if the user exists in the passenger table
    $passengerQuery = "SELECT * FROM passenger WHERE passengerMail = ?";
    $passengerStmt = mysqli_prepare($conn, $passengerQuery);
    mysqli_stmt_bind_param($passengerStmt, 's', $email);
    mysqli_stmt_execute($passengerStmt);

    $passengerResult = mysqli_stmt_get_result($passengerStmt);

    if ($passengerResult && mysqli_num_rows($passengerResult) > 0) {
        // User found in the passenger table, verify password
        $passengerUser = mysqli_fetch_assoc($passengerResult);
        if (password_verify($password, $passengerUser['passengerPassword'])) {
            // Store Passenger_ID in a session variable
            $_SESSION['user_id'] = $passengerUser['passenger_ID'];

            // Redirect to passenger home
            header('Location: ../passenger home/home.php');
            exit;
        } else {
            // Incorrect password
            echo json_encode(['success' => false, 'error' => 'Incorrect password']);
        }
    } else {
        // User not found in the passenger table, check the company table
        $companyQuery = "SELECT * FROM company WHERE companyMail = ?";
        $companyStmt = mysqli_prepare($conn, $companyQuery);
        mysqli_stmt_bind_param($companyStmt, 's', $email);
        mysqli_stmt_execute($companyStmt);

        $companyResult = mysqli_stmt_get_result($companyStmt);

        if ($companyResult && mysqli_num_rows($companyResult) > 0) {
            // User found in the company table, verify password
            $companyUser = mysqli_fetch_assoc($companyResult);
            if (password_verify($password, $companyUser['companyPassword'])) {
                // Store Company_ID in a session variable
                $_SESSION['user_id'] = $companyUser['company_ID'];

                // Redirect to company home
                header('Location: ../company home/company_home.php');
                exit;
            } else {
                // Incorrect password
                echo json_encode(['success' => false, 'error' => 'Incorrect password']);
            }
        } else {
            // User not found in both tables
            echo json_encode(['success' => false, 'error' => 'User not found']);
        }

        // Close the company statement if it's not null
        if ($companyStmt !== null) {
            mysqli_stmt_close($companyStmt);
        }
    }

    // Close the passenger statement if it's not null
    if ($passengerStmt !== null) {
        mysqli_stmt_close($passengerStmt);
    }
} else {
    // Invalid request method
    echo json_encode(['success' => false, 'error' => 'Invalid Request']);
}
?>