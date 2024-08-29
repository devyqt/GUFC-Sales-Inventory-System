<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT pt.product_id, pt.Product_Name, pt.Product_Price, 
                   pv.variant_id, pv.Product_Date, pv.expiration_date, pv.status,
                   ps.serial_number, 
                   1 AS quantity  -- Each serial number represents a single quantity
            FROM product_table pt
            LEFT JOIN product_variants pv ON pt.product_id = pv.product_id
            LEFT JOIN product_serials ps ON pv.variant_id = ps.variant_id
            ORDER BY pt.product_id, pv.variant_id, ps.serial_number";  // Optional: Order by for better sorting

    $result = $conn->query($sql);

    $products = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }

    echo json_encode($products);
}

// Function to get or insert a product and return its ID
function getOrInsertProduct($conn, $productName, $productPrice) {
    // Check if the product already exists
    $stmt = $conn->prepare("SELECT product_id FROM product_table WHERE Product_Name = ?");
    $stmt->bind_param('s', $productName);
    $stmt->execute();
    $stmt->bind_result($productID);
    
    if ($stmt->fetch()) {
        // Product exists, return its ID
        $stmt->close();
        return $productID;
    }
    
    // Product does not exist, insert new product
    $stmt->close();
    $stmt = $conn->prepare("INSERT INTO product_table (Product_Name, Product_Price) VALUES (?, ?)");
    $stmt->bind_param('sd', $productName, $productPrice);
    if (!$stmt->execute()) {
        die("Error inserting product: " . $stmt->error);
    }
    
    $productID = $stmt->insert_id; // Correct retrieval of new product ID
    $stmt->close();
    return $productID;
}


// Function to insert or update a product variant
function insertProductVariant($conn, $productID, $productDate, $expirationDate, $quantity) {
    $stmt = $conn->prepare("INSERT INTO product_variants (product_id, Product_Date, expiration_date, quantity, status)
                             VALUES (?, ?, ?, ?, 'Available')
                             ON DUPLICATE KEY UPDATE Product_Date = VALUES(Product_Date), expiration_date = VALUES(expiration_date), quantity = VALUES(quantity), status = VALUES(status)");
    $stmt->bind_param('ssss', $productID, $productDate, $expirationDate, $quantity);
    if (!$stmt->execute()) {
        die("Error inserting product variant: " . $stmt->error);
    }
    return $conn->insert_id;
}

// Function to insert serial numbers
function insertSerialNumbers($conn, $variantID, $serialNumbers) {
    // Delete existing serial numbers for the given variant ID
    $stmtDelete = $conn->prepare("DELETE FROM product_serials WHERE variant_id = ?");
    $stmtDelete->bind_param('i', $variantID);
    if (!$stmtDelete->execute()) {
        die("Error deleting existing serial numbers: " . $stmtDelete->error);
    }
    $stmtDelete->close();

    // Prepare statement for inserting serial numbers
    $stmt = $conn->prepare("INSERT INTO product_serials (variant_id, serial_number) VALUES (?, ?)");
    
    foreach ($serialNumbers as $serialNumber) {
        $serialNumber = trim($serialNumber);
        if (!empty($serialNumber)) {
            $stmt->bind_param('is', $variantID, $serialNumber);
            if (!$stmt->execute()) {
                die("Error inserting serial number: " . $stmt->error);
            }
        }
    }
    $stmt->close();
}

// Handle POST requests to add or update product and variants
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['productName'];
    $productPrice = $_POST['productPrice'];
    $quantity = $_POST['productQuantity'];
    $productDate = $_POST['productDate'];
    $expirationDate = $_POST['expirationDate'];
    $serialNumbers = explode("\n", trim($_POST['serialNumbers']));

    // Get or insert the product and retrieve its ID
    $productID = getOrInsertProduct($conn, $productName, $productPrice);

    if (!$productID) {
        die("Error: Product ID could not be retrieved or created.");
    }

    // Insert or update the product variant
    $variantID = insertProductVariant($conn, $productID, $productDate, $expirationDate, $quantity);

    if (!$variantID) {
        die("Error: Variant ID could not be retrieved or created.");
    }

    // Insert serial numbers
    insertSerialNumbers($conn, $variantID, $serialNumbers);

    echo "Product and variants added/updated successfully.";
}

// Handle DELETE requests to delete a product
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['product_id'])) {
        $product_id = $data['product_id'];

        // Delete serials and variants related to the product
        $stmtDeleteSerials = $conn->prepare("DELETE ps FROM product_serials ps JOIN product_variants pv ON ps.variant_id = pv.variant_id WHERE pv.product_id = ?");
        $stmtDeleteSerials->bind_param('i', $product_id);
        $stmtDeleteSerials->execute();
        $stmtDeleteSerials->close();

        $stmtDeleteVariants = $conn->prepare("DELETE FROM product_variants WHERE product_id = ?");
        $stmtDeleteVariants->bind_param('i', $product_id);
        $stmtDeleteVariants->execute();
        $stmtDeleteVariants->close();

        $stmtDeleteProduct = $conn->prepare("DELETE FROM product_table WHERE product_id = ?");
        $stmtDeleteProduct->bind_param('i', $product_id);
        $stmtDeleteProduct->execute();
        $stmtDeleteProduct->close();

        echo "Product deleted successfully.";
    } elseif (isset($data['product_ids']) && is_array($data['product_ids'])) {
        $product_ids = $data['product_ids'];
        $product_ids_placeholder = implode(',', array_fill(0, count($product_ids), '?'));

        $stmt = $conn->prepare("DELETE ps FROM product_serials ps JOIN product_variants pv ON ps.variant_id = pv.variant_id WHERE pv.product_id IN ($product_ids_placeholder)");
        $stmt->bind_param(str_repeat('i', count($product_ids)), ...$product_ids);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM product_variants WHERE product_id IN ($product_ids_placeholder)");
        $stmt->bind_param(str_repeat('i', count($product_ids)), ...$product_ids);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM product_table WHERE product_id IN ($product_ids_placeholder)");
        $stmt->bind_param(str_repeat('i', count($product_ids)), ...$product_ids);
        $stmt->execute();
        $stmt->close();

        echo "Selected products deleted successfully.";
    } else {
        echo "Invalid data for deletion.";
    }
}

$conn->close();
?>
