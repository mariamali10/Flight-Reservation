<?php
require_once '..\backend\db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $tel = trim($_POST['tel']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $confirmPassword = password_hash($_POST['confirmPassword'], PASSWORD_DEFAULT);
    $userType = $_POST['userType']; 

    // Validate input
    if (empty($username) || empty($email) || empty($tel) || empty($password) || empty($confirmPassword) || empty($userType)) {
        echo json_encode(['success' => false, 'error' => 'All fields are required']);
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'error' => 'Invalid email format']);
        exit;
    }

    // Validate password length, complexity, or any other requirements

    // Prepare query
    if ($userType === 'customer') {
        $query = "INSERT INTO passenger (PassengerName, PassengerMail, phone, PassengerPassword)
                  VALUES (?, ?, ?, ?)";
    } elseif ($userType === 'company') {
        $query = "INSERT INTO company (companyName, companyMail, companyPhone, companyPassword)
                  VALUES (?, ?, ?, ?)";
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid user type']);
        exit;
    }

    // Execute query with prepared statement
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ssss', $username, $email, $tel, $password);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $ID = mysqli_insert_id($conn);
        if ($userType === 'customer'){
            echo '<script>window.location.href = "./passenger_data.html?passengerID=' . $ID . '";</script>';
        } elseif ($userType === 'company'){
            echo '<script>window.location.href = "./company_data.html?passengerID=' . $ID . '";</script>';
        }
        exit;
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid Request']);
}
?>
