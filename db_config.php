<?php
// database satt tk nay yrrr
$servername = "localhost";
$username = "riley";
$password = "daltaminiac9";
$dbname = "MissingPersonTracker1";

$conn = new mysqli("localhost", "root", "", "MissingPersonTracker1");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
