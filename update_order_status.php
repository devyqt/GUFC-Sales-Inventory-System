<?php
// Include your database connection file
include 'db_connection.php'; // Ensure this file contains the connection code

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve POST data
    $data = json_decode(file_get_contents('php://input'), true);
    $orderID = $data['orderId'];
    $status = $data['status'];

    // Prepare an SQL query to update the order status
    $sql = "UPDATE order_table SET Order_Status = ? WHERE Order_ID = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $status, $orderID);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
