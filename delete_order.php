<?php
// Include your database connection file
include 'db_connection.php';

// Function to redirect with a message
function redirectWithMessage($message) {
    header("Location: order.php?message=" . urlencode($message));
    exit(); // Ensure no further code is executed after redirection
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['order_id'])) {
    // Delete a single order
    $order_id = intval($_GET['order_id']); // Sanitize the input
    $delete_sql = "DELETE FROM order_details WHERE order_id = ?";
    
    if ($stmt = $conn->prepare($delete_sql)) {
        $stmt->bind_param('i', $order_id);
        if ($stmt->execute()) {
            redirectWithMessage('Order deleted successfully');
        } else {
            echo "Error executing query: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} elseif (isset($_GET['order_ids'])) {
    // Delete multiple orders
    $order_ids = explode(',', $_GET['order_ids']);
    $placeholders = implode(',', array_fill(0, count($order_ids), '?'));
    $delete_sql = "DELETE FROM order_details WHERE order_id IN ($placeholders)";
    
    if ($stmt = $conn->prepare($delete_sql)) {
        $types = str_repeat('i', count($order_ids));
        $stmt->bind_param($types, ...$order_ids);
        if ($stmt->execute()) {
            redirectWithMessage('Orders deleted successfully');
        } else {
            echo "Error executing query: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "No order ID specified.";
}

$conn->close();
?>
