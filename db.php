<?php
$servername = "localhost:3305";
$username = "root";
$password = "";
$dbname = "meat_shop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
