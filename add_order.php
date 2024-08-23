<?php
// Include your database connection file
include 'db_connection.php';

// Get form data
$customer_id = $_POST['customer_id'];
$order_date = $_POST['orderDate'];
$products = $_POST['products'];
$quantities = $_POST['quantity'];

// Start a transaction
$conn->begin_transaction();

try {
    // Insert into orders table
    $order_sql = "INSERT INTO orders (customer_id, order_date) VALUES (?, ?)";
    $stmt = $conn->prepare($order_sql);
    $stmt->bind_param("is", $customer_id, $order_date); // Correct types: integer, string
    $stmt->execute();
    
    // Get the generated order_id
    $order_id = $stmt->insert_id;
    $stmt->close();

    // Prepare the SQL query for inserting order details
    $order_details_sql = "INSERT INTO order_details (order_id, product_id, customer_id, quantity, status, order_date) VALUES (?, ?, ?, ?, 'Pending', ?)";

    // Prepare the statement
    $stmt = $conn->prepare($order_details_sql);

    // Loop through selected products and quantities
    foreach ($products as $product_name) {
        // Get the product ID
        $product_sql = "SELECT product_id FROM product_table WHERE Product_Name = ?";
        $product_stmt = $conn->prepare($product_sql);
        $product_stmt->bind_param("s", $product_name);
        $product_stmt->execute();
        $product_result = $product_stmt->get_result();
        $product_row = $product_result->fetch_assoc();
        $product_id = $product_row['product_id'];
        $product_stmt->close();

        // Get the quantity for this product
        $quantity = $quantities[$product_name];

        // Bind parameters and execute the insert query
        $stmt->bind_param("isiss", $order_id, $product_id, $customer_id, $quantity, $order_date);
        $stmt->execute();
        
        // Update the product status to 'ordered'
        $update_product_sql = "UPDATE product_table SET status = 'ordered' WHERE product_id = ?";
        $update_stmt = $conn->prepare($update_product_sql);
        $update_stmt->bind_param("i", $product_id);
        $update_stmt->execute();
        $update_stmt->close();
    }

    // Commit the transaction
    $conn->commit();
    
    // Close the statement
    $stmt->close();

    // Redirect to the same page or another page
    header("Location: order.php?message=success");
    exit();
} catch (Exception $e) {
    // Rollback the transaction if something goes wrong
    $conn->rollback();
    
    // Close the statement
    if (isset($stmt)) $stmt->close();
    if (isset($update_stmt)) $update_stmt->close();

    // Show an error message
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$conn->close();
?>
