<?php
session_start();
include 'db_config.php'; 

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Investigator') {
    echo json_encode([]);
    exit();
}


$sql = "SELECT report_id, case_details, location, report_date, status FROM reports ORDER BY report_date DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$reports = [];
while ($row = $result->fetch_assoc()) {
    $reports[] = $row;
}

echo json_encode($reports);

$stmt->close();
$conn->close();
?>

