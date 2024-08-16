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
    $productID = $_POST['productID'];
    $productName = $_POST['productName'];
    $productPrice = $_POST['productPrice']; // Added product price
    $productDate = $_POST['productDate'];

    // Check if the Product_ID already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM product_table WHERE Product_ID=?");
    $stmt->bind_param("s", $productID);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "Error: Product ID already exists.";
    } else {
        // Insert new product
        $stmt = $conn->prepare("INSERT INTO product_table (Product_ID, Product_Name, Product_Price, Product_Date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $productID, $productName, $productPrice, $productDate);

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

    if (isset($data['Product_ID']) && !empty($data['Product_ID'])) {
        // Single deletion
        $productID = $data['Product_ID'];

        $stmt = $conn->prepare("DELETE FROM product_table WHERE Product_ID=?");
        $stmt->bind_param("s", $productID);

        if ($stmt->execute()) {
            echo "Product deleted successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } elseif (isset($data['Product_IDs']) && is_array($data['Product_IDs'])) {
        // Multiple deletions
        $productIds = $data['Product_IDs'];

        // Create a placeholder string for the query
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));
        $stmt = $conn->prepare("DELETE FROM product_table WHERE Product_ID IN ($placeholders)");

        if ($stmt) {
            // Bind parameters
            $types = str_repeat('s', count($productIds)); // Assuming IDs are strings
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
        echo "Product_ID or Product_IDs are required for deletion.";
    }
}

$conn->close();
?>
