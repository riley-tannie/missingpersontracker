<?php
session_start(); // Start the session

include 'db_config.php'; // Include your database connection

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch the username from the database based on the logged-in user_id
    $sql = "SELECT username FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($row = $result->fetch_assoc()) {
        // Return the username as JSON
        echo json_encode(['username' => $row['username']]);
    } else {
        // User not found in the database
        echo json_encode(['error' => 'User not found']);
    }

    $stmt->close();
} else {
    // If no user is logged in
    echo json_encode(['error' => 'User not logged in']);
}

$conn->close(); // Close the database connection
?>


