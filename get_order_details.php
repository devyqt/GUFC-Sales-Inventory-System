<?php
// Include your database connection file
include 'db_connection.php';

// Check if the orderID is provided
if (isset($_GET['orderID'])) {
    $orderID = $_GET['orderID'];

    // Prepare and execute the query
    $sql = "SELECT o.Order_ID, o.Customer_Name, o.Product_ID, o.Order_Date, o.Order_Status,
                   p.Product_Name, p.Product_Price
            FROM order_table o
            JOIN product_table p ON o.Product_ID = p.Product_ID
            WHERE o.Order_ID = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $orderID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode(['success' => true, 'order' => $row]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Order not found']);
    }

    $stmt->close();
}
$conn->close();
?>
