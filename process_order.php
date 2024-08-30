<?php
// Include your database connection file
include 'db_connection.php';

// Debugging: Check POST data
error_log("POST Data: " . print_r($_POST, true));

// Get the POST data from the order form
$customerId = isset($_POST['customerType']) ? intval($_POST['customerType']) : 0;
$orderDate = isset($_POST['orderDate']) ? $_POST['orderDate'] : '';
$selectedProducts = isset($_POST['products']) ? json_decode($_POST['products'], true) : [];

// Validate POST data
if ($customerId <= 0 || empty($orderDate) || !is_array($selectedProducts)) {
    error_log("Invalid input. Customer ID: $customerId, Order Date: $orderDate, Products: " . print_r($selectedProducts, true));
    die("Invalid input. Check your data.");
}

// Start a transaction
$conn->begin_transaction();

try {
    // Insert into orders table
    $sqlInsertOrder = "INSERT INTO orders (customer_id, order_date) VALUES (?, ?)";
    $stmtOrder = $conn->prepare($sqlInsertOrder);
    $stmtOrder->bind_param("is", $customerId, $orderDate);
    $stmtOrder->execute();
    $orderId = $stmtOrder->insert_id; // Get the inserted order ID

    foreach ($selectedProducts as $product) {
        // Check if product data is present
        if (!isset($product['productName']) || !isset($product['quantity'])) {
            throw new Exception("Product data is missing for product: " . print_r($product, true));
        }

        $productName = $product['productName'];
        $quantity = intval($product['quantity']);

        // Fetch the product ID using the product name
        $sqlGetProductId = "SELECT p.product_id FROM product_table p WHERE p.Product_Name = ?";
        $stmtGetProductId = $conn->prepare($sqlGetProductId);
        $stmtGetProductId->bind_param("s", $productName);
        $stmtGetProductId->execute();
        $resultProductId = $stmtGetProductId->get_result();
        $rowProductId = $resultProductId->fetch_assoc();

        if ($rowProductId) {
            $productId = $rowProductId['product_id'];

            // Fetch the variant ID and current quantity from the product_variants table
            $sqlGetVariant = "SELECT pv.variant_id, pv.quantity FROM product_variants pv WHERE pv.product_id = ? AND pv.status = 'Available'";
            $stmtGetVariant = $conn->prepare($sqlGetVariant);
            $stmtGetVariant->bind_param("i", $productId);
            $stmtGetVariant->execute();
            $resultVariant = $stmtGetVariant->get_result();
            $rowVariant = $resultVariant->fetch_assoc();

            if ($rowVariant) {
                $variantId = $rowVariant['variant_id'];
                $currentQuantity = $rowVariant['quantity'];

                if ($currentQuantity >= $quantity) {
                    // Insert into order_details table
                    $sqlInsertOrderDetails = "INSERT INTO order_details (order_id, product_id, quantity, status, order_date) VALUES (?, ?, ?, 'Ordered', ?)";
                    $stmtOrderDetails = $conn->prepare($sqlInsertOrderDetails);
                    $stmtOrderDetails->bind_param("isii", $orderId, $productId, $quantity, $orderDate);
                    $stmtOrderDetails->execute();

                    // Update quantity in the product_variants table
                    $newQuantity = $currentQuantity - $quantity;
                    $sqlUpdateQuantity = "UPDATE product_variants SET quantity = ? WHERE variant_id = ?";
                    $stmtUpdateQuantity = $conn->prepare($sqlUpdateQuantity);
                    $stmtUpdateQuantity->bind_param("ii", $newQuantity, $variantId);
                    $stmtUpdateQuantity->execute();
                } else {
                    throw new Exception("Not enough quantity for product: $productName");
                }
            } else {
                throw new Exception("Product not found in inventory: $productName");
            }
        } else {
            throw new Exception("Product ID not found for product name: $productName");
        }
    }

    // Commit the transaction
    $conn->commit();
    echo "Order placed successfully.";
} catch (Exception $e) {
    // Rollback the transaction if something goes wrong
    $conn->rollback();
    error_log("Order placement failed: " . $e->getMessage()); // Log error
    echo "Failed to place the order: " . $e->getMessage();
}

// Close the connection
$conn->close();
?>
