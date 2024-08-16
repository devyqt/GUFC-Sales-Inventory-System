<?php
// Include your database connection file
include 'db_connection.php';

// SQL query to get sales data
$sql = "
    SELECT 
        p.Product_Name, 
        SUM(o.Quantity) AS total_sales 
    FROM order_table o
    INNER JOIN product_table p ON o.Product_ID = p.Product_ID
    GROUP BY p.Product_Name
";
$result = $conn->query($sql);

$salesData = [];
while ($row = $result->fetch_assoc()) {
    $salesData[] = [
        'product' => $row['Product_Name'],
        'sales' => $row['total_sales']
    ];
}

// Output JSON data
header('Content-Type: application/json');
echo json_encode($salesData);

// Close the database connection
$conn->close();
?>
