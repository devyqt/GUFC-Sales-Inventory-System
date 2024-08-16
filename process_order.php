<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gufc";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute SQL query
$sql = "SELECT o.Order_ID, o.Customer_Name, o.Order_Date, p.Product_Name, p.Product_Price, 'Pending' as Status 
        FROM order_table o
        JOIN product_table p ON o.Product_ID = p.Product_ID";

$result = $conn->query($sql);

$orders = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

$conn->close();

// Return response as JSON
header('Content-Type: application/json');
echo json_encode($orders);
?>
