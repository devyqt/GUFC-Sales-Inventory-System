<?php
include 'db_connection.php';

try {
    $customer_id = $_POST['customer_id'];
    $order_date = $_POST['orderDate'];
    $products = $_POST['products']; // Array of selected product IDs
    $quantities = $_POST['quantity']; // Array of quantities keyed by product ID

    // Start a transaction
    $conn->begin_transaction();

    // Insert into orders table
    $order_sql = "INSERT INTO orders (customer_id, order_date) VALUES (?, ?)";
    $stmt = $conn->prepare($order_sql);
    if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);

    $stmt->bind_param("is", $customer_id, $order_date); 
    if (!$stmt->execute()) throw new Exception("Execute failed: " . $stmt->error);

    // Get the generated order_id
    $order_id = $stmt->insert_id;
    $stmt->close();

    // Prepare the SQL query for inserting order details
    $order_details_sql = "INSERT INTO order_details (order_id, product_id, customer_id, quantity, status, order_date) VALUES (?, ?, ?, ?, 'Pending', ?)";
    $stmt = $conn->prepare($order_details_sql);
    if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);

    // Loop through selected products and quantities
    foreach ($products as $product_ids_str) {
        $product_ids = explode(',', $product_ids_str);
        foreach ($product_ids as $product_id) {
            $quantity = $quantities[$product_id];

            // Bind parameters and execute the insert query
            $stmt->bind_param("isiss", $order_id, $product_id, $customer_id, $quantity, $order_date);
            if (!$stmt->execute()) throw new Exception("Execute failed: " . $stmt->error);

            // Update the product status to 'ordered'
            $update_product_sql = "UPDATE product_table SET status = 'ordered' WHERE product_id = ?";
            $update_stmt = $conn->prepare($update_product_sql);
            if (!$update_stmt) throw new Exception("Prepare failed: " . $conn->error);

            $update_stmt->bind_param("s", $product_id);
            if (!$update_stmt->execute()) throw new Exception("Execute failed: " . $update_stmt->error);
            $update_stmt->close();
        }
    }

    // Update last_order_date for the customer
    $update_last_order_sql = "UPDATE customer_table SET last_order_date = NOW() WHERE Customer_ID = ?";
    $update_last_order_stmt = $conn->prepare($update_last_order_sql);
    if (!$update_last_order_stmt) throw new Exception("Prepare failed: " . $conn->error);

    $update_last_order_stmt->bind_param("i", $customer_id);
    if (!$update_last_order_stmt->execute()) throw new Exception("Execute failed: " . $update_last_order_stmt->error);
    $update_last_order_stmt->close();

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
    if (isset($update_last_order_stmt)) $update_last_order_stmt->close();

    // Show an error message
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$conn->close();
?>
