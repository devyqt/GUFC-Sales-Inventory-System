<?php
// Include your database connection file
include 'db_connection.php';

// Fetch the ordered products from the database
$sql = "SELECT DISTINCT Product_ID FROM order_table";
$result = $conn->query($sql);

$orderedProducts = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orderedProducts[] = $row['Product_ID'];
    }
}

// Return the ordered products as JSON
header('Content-Type: application/json');
echo json_encode($orderedProducts);

// Close the database connection
$conn->close();
?>
