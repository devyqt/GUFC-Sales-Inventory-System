<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT product_id, Product_Name, Product_Date, expiration_date, status FROM product_table";
    if ($result = $conn->query($sql)) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo "Error: " . $conn->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productIDs = $_POST['productIDs'] ?? [];
    $productName = $_POST['productName'];
    $productDate = $_POST['productDate'];
    $expirationDate = $_POST['expirationDate'] ?? null;
    $productPrice = $_POST['productPrice'] ?? null;
    $productQuantity = 1;  // Set default quantity to 1 for new products

    foreach ($productIDs as $productID) {
        // Check if the product_name already exists
        $stmt = $conn->prepare("SELECT product_id, quantity FROM product_table WHERE Product_Name = ?");
        $stmt->bind_param("s", $productName);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            // Product name exists in the database
            $stmt->bind_result($existingProductID, $existingQuantity);
            $stmt->fetch();
            $stmt->close();
            
            if ($existingProductID === $productID) {
                // Product ID matches the existing record, update the quantity
                $stmt = $conn->prepare("UPDATE product_table SET quantity = ?, Product_Date = ?, expiration_date = ?, Product_Price = ? WHERE product_id = ?");
                $stmt->bind_param("issds", $productQuantity, $productDate, $expirationDate, $productPrice, $productID);
            } else {
                // Product ID does not match, insert a new record
                $stmt = $conn->prepare("INSERT INTO product_table (product_id, Product_Name, Product_Date, expiration_date, Product_Price, quantity) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssd", $productID, $productName, $productDate, $expirationDate, $productPrice, $productQuantity);
            }
        } else {
            // Product name does not exist, insert a new record
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO product_table (product_id, Product_Name, Product_Date, expiration_date, Product_Price, quantity) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssd", $productID, $productName, $productDate, $expirationDate, $productPrice, $productQuantity);
        }
        
        if ($stmt) {
            $stmt->execute();
            if ($stmt->error) {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    }
    echo "Products successfully added!";
}



// Handle DELETE request (Delete product or products)
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['product_id']) && !empty($data['product_id'])) {
        // Single deletion
        $productID = $data['product_id'];

        $stmt = $conn->prepare("DELETE FROM product_table WHERE product_id=?");
        if ($stmt) {
            $stmt->bind_param("s", $productID);
            if ($stmt->execute()) {
                echo "Product deleted successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
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
