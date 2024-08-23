<?php
include 'db_connection.php';

// Fetch data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM product_table";
    $result = $conn->query($sql);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
}

// Add new product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productID = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    $productDate = $_POST['product_date'];

    // Check if the product ID already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM product_table WHERE product_id=?");
    $stmt->bind_param("s", $productID);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "Error: Product ID already exists.";
    } else {
        // Insert new product
        $stmt = $conn->prepare("INSERT INTO product_table (product_id, Product_Name, Product_Price, Product_Date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $productID, $productName, $productPrice, $productDate);

        if ($stmt->execute()) {
            echo "New product added successfully";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Handle DELETE request (Delete product or products)
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['product_id']) && !empty($data['product_id'])) {
        // Single deletion
        $productID = $data['product_id'];

        $stmt = $conn->prepare("DELETE FROM product_table WHERE product_id=?");
        $stmt->bind_param("s", $productID);

        if ($stmt->execute()) {
            echo "Product deleted successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } elseif (isset($data['product_ids']) && is_array($data['product_ids'])) {
        // Multiple deletions
        $productIds = $data['product_ids'];

        $placeholders = implode(',', array_fill(0, count($productIds), '?'));
        $stmt = $conn->prepare("DELETE FROM product_table WHERE product_id IN ($placeholders)");

        if ($stmt) {
            $types = str_repeat('s', count($productIds));
            $stmt->bind_param($types, ...$productIds);

            if ($stmt->execute()) {
                echo "Products deleted successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        echo "product_id or product_ids are required for deletion.";
    }
}

$conn->close();
?>
