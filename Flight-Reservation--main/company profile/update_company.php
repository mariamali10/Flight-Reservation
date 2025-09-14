<?php
session_start();

require_once '../backend/db_connection.php';

$companyID = $_SESSION['user_id']; // Assuming the company ID is stored in user_id

if (!isset($_SESSION['user_id'])) {
    // User not authenticated, redirect to login page
    header('Location: ../login/login.html');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve updated information from the form
    $newName = $_POST['companyName'];
    $newBio = $_POST['companyBio'];
    $newAddress = $_POST['companyAddress'];

    // Update the company information in the database
    $updateCompanyQuery = "UPDATE company SET companyName = ?, companyBio = ?, companyAddress = ? WHERE company_ID = ?";
    $updateCompanyStmt = mysqli_prepare($conn, $updateCompanyQuery);
    mysqli_stmt_bind_param($updateCompanyStmt, 'sssi', $newName, $newBio, $newAddress, $companyID);

    if (mysqli_stmt_execute($updateCompanyStmt)) {
        // Update successful
        header('Location: company_profile.php');
        exit;
    } else {
        // Error in updating
        echo "Error: " . mysqli_error($conn);
    }

    // Close the statement
    mysqli_stmt_close($updateCompanyStmt);
}
?>
