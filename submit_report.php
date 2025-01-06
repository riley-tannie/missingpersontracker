<?php
session_start(); // Start the session

include 'db_config.php'; // Include your database connection

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $username = $_POST['username']; // Username is submitted as part of the form, it should be retrieved from the session
    $phone_number = $_POST['phone_number'];
    $case_details = $_POST['case_details'];
    $location = $_POST['location'];
    $report_date = date('Y-m-d H:i:s');
    $status = 'Submitted'; // Default status when the report is submitted

    // Insert the report data into the database
    $sql = "INSERT INTO reports (reporter_id, username, phone_number, case_details, location, report_date, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssss", $user_id, $username, $phone_number, $case_details, $location, $report_date, $status);

    // Execute the statement and check if it was successful
    if ($stmt->execute()) {
        $response = [
            'success' => true,
            'message' => 'Report submitted successfully!'
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Failed to submit the report. Please try again later.'
        ];
    }

    $stmt->close();
} else {
    $response = [
        'success' => false,
        'message' => 'User not logged in'
    ];
}

$conn->close(); // Close the database connection

echo json_encode($response); // Return the response as JSON
?>
