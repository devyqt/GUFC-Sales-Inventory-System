<?php
// Include your database connection file
include 'db_connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and decode the JSON data from the request
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if 'orders' key exists in the received data
    if (isset($data['orders']) && is_array($data['orders'])) {
        $orders = $data['orders'];

        // Prepare a SQL query to delete the orders
        $orderIds = implode(',', array_map('intval', $orders)); // Convert array to a comma-separated string of integers
        $sql = "DELETE FROM order_table WHERE Order_ID IN ($orderIds)";

        // Execute the query
        if ($conn->query($sql)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'No orders specified']);
    }

    // Close the database connection
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
