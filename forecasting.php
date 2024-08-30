<?php
// Include your database connection file
include 'db_connection.php'; // Ensure you have a file for connecting to the database

// Query to fetch orders and their dates per customer
$sql = "
    SELECT 
        c.Customer_Name, 
        o.order_date
    FROM 
        orders o
    JOIN 
        customer_table c ON o.customer_id = c.Customer_ID
    ORDER BY 
        c.Customer_Name, o.order_date
";

$result = $conn->query($sql);

$customer_data = [];
if ($result->num_rows > 0) {
    // Process each row
    while ($row = $result->fetch_assoc()) {
        $customer_name = $row['Customer_Name'];
        $order_date = $row['order_date'];
        
        // Initialize array if not already set
        if (!isset($customer_data[$customer_name])) {
            $customer_data[$customer_name] = [
                'order_dates' => [],
                'average_days_between_orders' => 'N/A'
            ];
        }
        
        // Add order date to customer's order dates array
        $customer_data[$customer_name]['order_dates'][] = $order_date;
    }
    
    // Calculate average days between orders
    foreach ($customer_data as $customer_name => $data) {
        $order_dates = $data['order_dates'];
        $total_days = 0;
        $num_intervals = count($order_dates) - 1;
        
        for ($i = 0; $i < $num_intervals; $i++) {
            $date1 = new DateTime($order_dates[$i]);
            $date2 = new DateTime($order_dates[$i + 1]);
            $interval = $date1->diff($date2);
            $total_days += $interval->days;
        }
        
        $average_days = $num_intervals > 0 ? $total_days / $num_intervals : 'N/A';
        $customer_data[$customer_name]['average_days_between_orders'] = is_numeric($average_days) ? round($average_days) : 'N/A';
    }
} else {
    echo "No data found.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Forecasting Per Customer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
        }

        .customer-report {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h1>Order Forecasting Per Customer</h1>
    <?php foreach ($customer_data as $customer_name => $data): ?>
        <div class="customer-report">
            <h2><?php echo htmlspecialchars($customer_name); ?></h2>
            <p><strong>Average Days Between Orders:</strong> <?php echo htmlspecialchars($data['average_days_between_orders']); ?> days</p>
            <!-- Displaying order dates if needed -->
            <?php if (!empty($data['order_dates'])): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Order Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['order_dates'] as $order_date): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order_date); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</body>
</html>
