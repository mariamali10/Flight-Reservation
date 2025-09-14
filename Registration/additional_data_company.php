<?php
require_once '..\backend\db_connection.php';

$getCompanyIDQuery = "SELECT company_ID FROM company ORDER BY company_ID DESC LIMIT 1";
$getCompanyIDResult = mysqli_query($conn, $getCompanyIDQuery);

if ($getCompanyIDResult) {
    $row = mysqli_fetch_assoc($getCompanyIDResult);
    $companyID = $row['company_ID'];
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    exit;
}

if ($companyID === null) {
    echo json_encode(['success' => false, 'error' => 'Company ID not provided']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $userType = $_POST['userType'];
    $logo = $_FILES['logo'];
    $bio = $_POST['bio'];
    $address = $_POST['address'];
    $accountBalance = $_POST['accountBalance'];

    // Check user type (assuming 'company' for companies)
    if ($userType === 'company') {
        // Handle file upload for company logo
        $targetDir = "uploads/";
        $logoName = basename($logo["name"]);
        $logoPath = $targetDir . $logoName;

        if (move_uploaded_file($logo["tmp_name"], $logoPath)) {
            // Update the company record with the logo path
            $updateLogoQuery = "UPDATE company SET companyLogo = '$logoPath' WHERE company_ID = $companyID";
            $updateLogoResult = mysqli_query($conn, $updateLogoQuery);

            // Check if the update was successful
            if (!$updateLogoResult) {
                echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Company logo upload failed.']);
            exit;
        }

        // Update the company record with bio, address, and account balance
        $updateBioQuery = "UPDATE company SET companyBio = '$bio', companyAddress = '$address', companyBalance = $accountBalance WHERE company_ID = $companyID";
        $updateBioResult = mysqli_query($conn, $updateBioQuery);

        // Check if the update was successful
        if (!$updateBioResult) {
            echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
            exit;
        }

        // Redirect to another page or display a success message
        header('Location: ../login/login.html');
        exit;
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid user type']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid Request']);
    exit;
}
?>
