<?php
session_start();

include 'db_config.php'; // Include your database configuration file
header('Content-Type: application/json'); // Set the content type to JSON

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

$user_id = $_SESSION['user_id'];

// Prepare the SQL statement to fetch reports for the logged-in user
$sql = "SELECT report_id, case_details, location, report_date, status 
        FROM reports 
        WHERE reporter_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    error_log("Query preparation failed: " . $conn->error);
    echo json_encode(["error" => "Failed to prepare query."]);
    exit();
}

// Bind the user_id and execute the query
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$reports = [];

// Fetch the results
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }
}

// Return the reports as JSON
echo json_encode($reports);

$stmt->close();
$conn->close();
?>


