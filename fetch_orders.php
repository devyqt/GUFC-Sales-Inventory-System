<?php
// Include your database connection file
include 'db_connection.php';

// SQL query to fetch data from the order_table
$sql = "SELECT Order_ID, Customer_Name, Product_ID, Order_Date FROM order_table";
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
        $output .= "<td>" . $row['Order_Date'] . "</td>";
        $output .= "<td>
                        <button class='btn-edit'>Edit</button>
                        <button class='btn-delete' onclick='deleteOrder(\"" . $row['Order_ID'] . "\")'>Delete</button>
                    </td>";
        $output .= "</tr>";
    }
} else {
    $output .= "<tr><td colspan='8'>No orders found</td></tr>";
}

echo $output;

// Close the database connection
$conn->close();
?>
