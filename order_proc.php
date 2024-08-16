<?php
// Include your database connection file
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve POST data
    $orderID = $_POST['orderID'] ?? '';
    $customerName = $_POST['customerName'] ?? '';
    $orderDate = $_POST['orderDate'] ?? '';
    $productID = $_POST['itemName'] ?? '';
    $orderStatus = $_POST['orderStatus'] ?? 'Pending'; // Default to Pending if not set

    // Sanitize inputs
    $orderID = $conn->real_escape_string($orderID);
    $customerName = $conn->real_escape_string($customerName);
    $orderDate = $conn->real_escape_string($orderDate);
    $productID = $conn->real_escape_string($productID);
    $orderStatus = $conn->real_escape_string($orderStatus);

    // Fetch the product name from the product_table
    $product_query = "SELECT Product_Name FROM product_table WHERE Product_ID = '$productID'";
    $product_result = $conn->query($product_query);
    $product_row = $product_result->fetch_assoc();
    $productName = $product_row ? $product_row['Product_Name'] : 'Unknown';

    // Prepare SQL query to insert the data into the order_table
    $sql = "INSERT INTO order_table (Order_ID, Customer_Name, Product_ID, Product_Name, Order_Status, Order_Date)
            VALUES (?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $orderID, $customerName, $productID, $productName, $orderStatus, $orderDate);

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
