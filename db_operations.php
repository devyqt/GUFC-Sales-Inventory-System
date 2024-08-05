<?php
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "gufc";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM test_table";
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
    $productQty = $_POST['productQty'];
    $productDate = $_POST['productDate'];

    $stmt = $conn->prepare("INSERT INTO test_table (Product_ID, Product_Name, Product_Qty, date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $productID, $productName, $productQty, $productDate);

    if ($stmt->execute()) {
        echo "New product added successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle DELETE request (Delete product)
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $productID = $data['Product_ID'] ?? '';

    if ($productID) {
        $stmt = $conn->prepare("DELETE FROM test_table WHERE Product_ID=?");
        $stmt->bind_param("s", $productID); // Assuming Product_ID is a string

        if ($stmt->execute()) {
            echo "Product deleted successfully";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Product ID is required for deletion.";
    }
}

$conn->close();
?>
