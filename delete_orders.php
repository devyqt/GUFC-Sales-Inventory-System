<?php
include 'db_connection.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$orderId = $input['orderId'];

$response = ['success' => false, 'error' => ''];

if ($orderId) {
    $stmt = $conn->prepare("DELETE FROM order_table WHERE Order_ID = ?");
    $stmt->bind_param("i", $orderId);
    
    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        $response['error'] = 'Failed to delete the order';
    }

    $stmt->close();
} else {
    $response['error'] = 'Invalid order ID';
}

$conn->close();

echo json_encode($response);
?>
