<?php
// Include your database connection file
include 'db_connection.php';

// SQL query to get the total count of products
$sql = "SELECT COUNT(DISTINCT Product_ID) AS total_products FROM product_table";
$result = $conn->query($sql);

$totalProducts = 0;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalProducts = $row['total_products'];
}

// Return the total products as JSON
echo json_encode(['total_products' => $totalProducts]);

// Close the database connection
$conn->close();
?>
