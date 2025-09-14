<?php
$servername = "localhost"; // usually "localhost" if the database is on the same server
$username = "root";
$password = "";
$dbname = "imagine_flight";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set UTF-8 character set (optional, but recommended)
$conn->set_charset("utf8");
?>
