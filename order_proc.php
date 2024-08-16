<?php
// Include your database connection file
include 'db_connection.php'; // Ensure this file contains the connection code

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve POST data
    $orderID = $_POST['orderID'];
    $customerName = $_POST['customerName'];
    $orderDate = $_POST['orderDate'];
    $productID = $_POST['itemName']; // Updated to match the form field name
    $productNameQuery = "SELECT Product_Name FROM product_table WHERE Product_ID = ?";
    
    // Prepare and execute the query to get the Product_Name
    $productStmt = $conn->prepare($productNameQuery);
    $productStmt->bind_param("s", $productID);
    $productStmt->execute();
    $productStmt->bind_result($productName);
    $productStmt->fetch();
    $productStmt->close();
    
    // Validate and sanitize inputs if needed

    // Prepare an SQL query to insert the data into the order_table
    $sql = "INSERT INTO order_table (Order_ID, Customer_Name, Product_ID, Product_Name, Order_Date)
            VALUES (?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $orderID, $customerName, $productID, $productName, $orderDate);

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
