<?php
session_start();
include 'db_config.php'; 

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Investigator') {
    echo "<script>alert('You are not authorized to view this page.'); window.location.href = 'login.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['report_id'])) {
    $report_id = $_GET['report_id'];

    $sql = "SELECT report_id, case_details, location, report_date, status FROM reports WHERE report_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $report_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $report = $result->fetch_assoc();
        echo json_encode($report); 
    } else {
        echo json_encode(null); 
    }
    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['report_id'], $_POST['status'])) {
        $report_id = $_POST['report_id'];
        $status = $_POST['status'];

        $sql = "UPDATE reports SET status = ? WHERE report_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $report_id);

        if ($stmt->execute()) {
            echo "<script>alert('Status updated successfully.'); window.location.href = '../index_investigator.html';</script>";
        } else {
            echo "<script>alert('Failed to update status. Please try again.');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Invalid report data.');</script>";
    }
}

$conn->close();
?>
