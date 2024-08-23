<?php
include 'db_connection.php';

// Get the order ID from the URL
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id <= 0) {
    die("Invalid Order ID");
}

// Fetch order and customer details
$order_sql = "
    SELECT 
        o.order_id, 
        c.Customer_Name, 
        p.Product_Name, 
        o.quantity, 
        p.Product_Price, 
        (o.quantity * p.Product_Price) AS Total_Price, 
        o.status, 
        o.order_date
    FROM order_details o
    JOIN product_table p ON o.product_id = p.Product_ID
    JOIN customer_table c ON o.customer_id = c.Customer_ID
    WHERE o.order_id = ?
    ORDER BY o.order_id";
$stmt = $conn->prepare($order_sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();
if (!$order_result) {
    die("Error: " . $conn->error);
}

// Fetch the order details and aggregate the total price
$order_totals = [];
$order_details = [];

while ($row = $order_result->fetch_assoc()) {
    $order_totals['total_price'] = isset($order_totals['total_price']) ? $order_totals['total_price'] + $row['Total_Price'] : $row['Total_Price'];
    $order_details[] = $row;
}

if (empty($order_details)) {
    die("No details found for this order.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - Order #<?php echo htmlspecialchars($order_id); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .invoice-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
        .invoice-header, .invoice-footer {
            text-align: center;
            margin-bottom: 20px;
        }
        .invoice-header h1 {
            margin: 0;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-table th, .invoice-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .invoice-table th {
            background-color: #f4f4f4;
        }
        .total-row td {
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <h1>Invoice</h1>
            <p>Order ID: <?php echo htmlspecialchars($order_id); ?></p>
            <p>Date: <?php echo htmlspecialchars(date('F j, Y', strtotime($order_details[0]['order_date']))); ?></p>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price Per Unit</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_details as $detail): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($detail['Product_Name']); ?></td>
                        <td><?php echo htmlspecialchars($detail['quantity']); ?></td>
                        <td>₱<?php echo number_format($detail['Product_Price'], 2); ?></td>
                        <td>₱<?php echo number_format($detail['Total_Price'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="3">Total</td>
                    <td>₱<?php echo number_format($order_totals['total_price'], 2); ?></td>
                </tr>
            </tbody>
        </table>

        <div class="invoice-footer">
            <p>Thank you for your business!</p>
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
