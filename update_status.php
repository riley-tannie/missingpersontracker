<?php
include 'db_config.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $report_id = $_POST['report_id'];
    $user_id = $_POST['user_id'];
    $update_text = $_POST['update_text'];
    $new_status = $_POST['status'];

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("INSERT INTO investigation_updates (report_id, user_id, update_text) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $report_id, $user_id, $update_text);

        if ($stmt->execute()) {
            if ($new_status === "Resolved") {
                $stmt2 = $conn->prepare("DELETE FROM reports WHERE report_id = ?");
                $stmt2->bind_param("i", $report_id);

                if ($stmt2->execute()) {
                    $conn->commit();
                    echo json_encode(['success' => true, 'message' => 'Report marked as resolved and deleted successfully!']);
                } else {
                    $conn->rollback();
                    echo json_encode(['success' => false, 'message' => 'Error deleting the report: ' . $conn->error]);
                }
                $stmt2->close();
            } else {
                $stmt2 = $conn->prepare("UPDATE reports SET status = ? WHERE report_id = ?");
                $stmt2->bind_param("si", $new_status, $report_id);

                if ($stmt2->execute()) {
                    $conn->commit();
                    echo json_encode(['success' => true, 'message' => 'Investigation status updated successfully!']);
                } else {
                    $conn->rollback();
                    echo json_encode(['success' => false, 'message' => 'Error updating report status: ' . $conn->error]);
                }

                $stmt2->close();
            }
        } else {
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => 'Error updating investigation: ' . $conn->error]);
        }

        $stmt->close();
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Transaction failed: ' . $e->getMessage()]);
    }
}

$conn->close();
?>
