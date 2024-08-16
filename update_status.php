<?php
include 'db_connection.php';

$order_id = $_POST['order_id'];
$status = $_POST['status'];

$sql = "UPDATE order_table SET Order_Status = '$status' WHERE Order_ID = '$order_id'";

if ($conn->query($sql) === TRUE) {
    echo "Order status updated successfully";
} else {
    echo "Error updating status: " . $conn->error;
}

$conn->close();
?>
