<?php
// Include your database connection file
include 'db_connection.php';

// SQL query to fetch data from the order_table
$sql = "SELECT Order_ID, Customer_Name, Product_ID, Order_Date, Order_Status FROM order_table";
$result = $conn->query($sql);

$output = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Fetch product details from product_table
        $product_query = "SELECT Product_Name, Product_Price FROM product_table WHERE Product_ID = '" . $row['Product_ID'] . "'";
        $product_result_inner = $conn->query($product_query);
        $product_row = $product_result_inner->fetch_assoc();

        $output .= "<tr>";
        $output .= "<td><input type='checkbox' name='selectOrder'></td>";
        $output .= "<td>" . $row['Order_ID'] . "</td>";
        $output .= "<td>" . $row['Customer_Name'] . "</td>";
        $output .= "<td>" . $row['Product_ID'] . "</td>";
        $output .= "<td>" . ($product_row ? $product_row['Product_Name'] : 'Unknown') . "</td>";
        $output .= "<td>" . ($product_row ? $product_row['Product_Price'] : 'N/A') . "</td>";
        
        // Dropdown for status
        $statusOptions = [
            'Pending' => 'Pending',
            'Processing' => 'Processing',
            'Completed' => 'Completed',
            'Cancelled' => 'Cancelled'
        ];

        $output .= "<td>
                        <select class='status-dropdown' data-order-id='" . $row['Order_ID'] . "'>";
        foreach ($statusOptions as $value => $label) {
            $selected = ($row['Order_Status'] == $value) ? 'selected' : '';
            $output .= "<option value='$value' $selected>$label</option>";
        }
        $output .= "</select>
                    </td>";

        $output .= "<td>" . $row['Order_Date'] . "</td>";
        
        $output .= "<td>
                        <button class='btn-delete' onclick='deleteOrder(\"" . $row['Order_ID'] . "\")'>Delete</button>
                        <button class='btn-print' onclick='printInvoice(\"" . $row['Order_ID'] . "\")'>Print Invoice</button>
                    </td>";
        $output .= "</tr>";
    }
} else {
    $output .= "<tr><td colspan='9'>No orders found</td></tr>";
}

echo $output;

// Close the database connection
$conn->close();
?>
