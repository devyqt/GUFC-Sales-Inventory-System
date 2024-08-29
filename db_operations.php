<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetching products and variants
    $sql = "SELECT pt.product_id, pt.Product_Name, pt.Product_Price, 
               pv.variant_id, pv.Product_Date, pv.expiration_date, pv.status,
               pv.hose_length,  
               ps.serial_number, 
               1 AS quantity  
        FROM product_table pt
        LEFT JOIN product_variants pv ON pt.product_id = pv.product_id
        LEFT JOIN product_serials ps ON pv.variant_id = ps.variant_id
        ORDER BY pt.product_id, pv.variant_id, ps.serial_number";

    $result = $conn->query($sql);

    $products = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['Product_Name'] === 'Hose with Clamps' && !empty($row['hose_length'])) {
                $row['Product_Name'] = "Hose with Clamps ({$row['hose_length']} meters)";
            }
            $products[] = $row;
        }
    }

    echo json_encode($products);
}

function getOrInsertProduct($conn, $productName, $productPrice) {
    $stmt = $conn->prepare("SELECT product_id FROM product_table WHERE Product_Name = ?");
    $stmt->bind_param('s', $productName);
    $stmt->execute();
    $stmt->bind_result($productID);
    
    if ($stmt->fetch()) {
        $stmt->close();
        return $productID;
    }
    
    $stmt->close();
    $stmt = $conn->prepare("INSERT INTO product_table (Product_Name, Product_Price) VALUES (?, ?)");
    $stmt->bind_param('sd', $productName, $productPrice);
    if (!$stmt->execute()) {
        die("Error inserting product: " . $stmt->error);
    }
    
    $productID = $stmt->insert_id;
    $stmt->close();
    return $productID;
}

function insertProductVariant($conn, $productID, $productDate, $expirationDate, $quantity, $hoseLength = NULL) {
    $stmt = $conn->prepare("INSERT INTO product_variants (product_id, Product_Date, expiration_date, quantity, status, hose_length)
                             VALUES (?, ?, ?, ?, 'Available', ?)
                             ON DUPLICATE KEY UPDATE Product_Date = VALUES(Product_Date), expiration_date = VALUES(expiration_date), quantity = VALUES(quantity), status = VALUES(status), hose_length = VALUES(hose_length)");
    $stmt->bind_param('sssds', $productID, $productDate, $expirationDate, $quantity, $hoseLength);
    if (!$stmt->execute()) {
        die("Error inserting product variant: " . $stmt->error);
    }
    return $conn->insert_id;
}

function insertSerialNumbers($conn, $variantID, $serialNumbers) {
    $stmtDelete = $conn->prepare("DELETE FROM product_serials WHERE variant_id = ?");
    $stmtDelete->bind_param('i', $variantID);
    if (!$stmtDelete->execute()) {
        die("Error deleting existing serial numbers: " . $stmtDelete->error);
    }
    $stmtDelete->close();

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['productName'];
    $productPrice = $_POST['productPrice'];
    $quantity = $_POST['productQuantity'];
    $productDate = $_POST['productDate'];
    $expirationDate = $_POST['expirationDate'];
    $hoseLength = isset($_POST['hoseLength']) ? $_POST['hoseLength'] : NULL;
    $serialNumbers = explode("\n", trim($_POST['serialNumbers']));

    $productID = getOrInsertProduct($conn, $productName, $productPrice);

    if (!$productID) {
        die("Error: Product ID could not be retrieved or created.");
    }

    $variantID = insertProductVariant($conn, $productID, $productDate, $expirationDate, $quantity, $hoseLength);

    if (!$variantID) {
        die("Error: Variant ID could not be retrieved or created.");
    }

    insertSerialNumbers($conn, $variantID, $serialNumbers);

    echo "Product and variants added/updated successfully.";
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['serial_number'])) {
        $serial_number = $data['serial_number'];

        // Find the variant ID associated with the serial number
        $stmt = $conn->prepare("SELECT variant_id FROM product_serials WHERE serial_number = ?");
        $stmt->bind_param('s', $serial_number);
        $stmt->execute();
        $stmt->bind_result($variant_id);
        if (!$stmt->fetch()) {
            echo "Serial number not found.";
            $stmt->close();
            exit;
        }
        $stmt->close();

        // Delete the serial number
        $stmtDeleteSerial = $conn->prepare("DELETE FROM product_serials WHERE serial_number = ?");
        $stmtDeleteSerial->bind_param('s', $serial_number);
        if (!$stmtDeleteSerial->execute()) {
            die("Error deleting serial number: " . $stmtDeleteSerial->error);
        }
        $stmtDeleteSerial->close();

        // Check if there are any serial numbers left for the variant
        $stmtCheckVariants = $conn->prepare("SELECT COUNT(*) FROM product_serials WHERE variant_id = ?");
        $stmtCheckVariants->bind_param('i', $variant_id);
        $stmtCheckVariants->execute();
        $stmtCheckVariants->bind_result($serialCount);
        $stmtCheckVariants->fetch();
        $stmtCheckVariants->close();

        if ($serialCount == 0) {
            // No more serial numbers for this variant, delete the variant
            $stmtDeleteVariants = $conn->prepare("DELETE FROM product_variants WHERE variant_id = ?");
            $stmtDeleteVariants->bind_param('i', $variant_id);
            if (!$stmtDeleteVariants->execute()) {
                die("Error deleting product variant: " . $stmtDeleteVariants->error);
            }
            $stmtDeleteVariants->close();

            // Check if there are any variants left for the product
            $stmtCheckProducts = $conn->prepare("SELECT COUNT(*) FROM product_variants WHERE product_id = (SELECT product_id FROM product_variants WHERE variant_id = ?)");
            $stmtCheckProducts->bind_param('i', $variant_id);
            $stmtCheckProducts->execute();
            $stmtCheckProducts->bind_result($variantCount);
            $stmtCheckProducts->fetch();
            $stmtCheckProducts->close();

            if ($variantCount == 0) {
                // No more variants for this product, delete the product
                $stmtDeleteProduct = $conn->prepare("DELETE FROM product_table WHERE product_id NOT IN (SELECT product_id FROM product_variants)");
                if (!$stmtDeleteProduct->execute()) {
                    die("Error deleting product: " . $stmtDeleteProduct->error);
                }
                $stmtDeleteProduct->close();
            }
        }

        echo "Serial number deleted successfully.";
    } else {
        echo "Invalid data for deletion.";
    }
}


$conn->close();
?>
