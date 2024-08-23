<?php
include 'db_connection.php';

$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
$status = isset($_POST['status']) ? $_POST['status'] : '';

if ($order_id <= 0 || empty($status)) {
    die("Invalid input.");
}

$update_sql = "UPDATE order_details SET status = ? WHERE order_id = ?";
$stmt = $conn->prepare($update_sql);
$stmt->bind_param("si", $status, $order_id);
if ($stmt->execute()) {
    echo "Status updated successfully.";
} else {
    echo "Error updating status: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
