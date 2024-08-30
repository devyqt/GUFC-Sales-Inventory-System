<?php
include 'db_connection.php';

header('Content-Type: application/json');

$sql = "SELECT Product_Name, SUM(Quantity) AS Total_Quantity FROM product_table WHERE Product_Name IN (
    '11kg Auto-Shutoff Cylinder',
    '11kg POL Cylinder',
    '1.4kg Solane Sakto',
    '22kg POL Cylinder',
    '50kg Cylinder',
    'POL Regulator',
    'AS Regulator',
    'Hose with Clamps'
) GROUP BY Product_Name";

if ($result = $conn->query($sql)) {
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[$row['Product_Name']] = $row['Total_Quantity'];
    }
    echo json_encode($data);
} else {
    // Output error message as JSON
    echo json_encode(['error' => $conn->error]);
}

$conn->close();
?>
