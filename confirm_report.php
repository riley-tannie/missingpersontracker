<?php
// confirm_report.php
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $report_id = $_POST['report_id'];
    $officer_id = $_POST['officer_id'];

    $sql = "UPDATE reports SET status = 'Confirmed' WHERE report_id = '$report_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Report confirmed successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
