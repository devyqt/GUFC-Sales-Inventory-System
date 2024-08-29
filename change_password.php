<?php
// Include the database connection script
include('connect.php');

// Initialize variables
$newPassword = "";

// Check if the POST request contains a new password
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = $_POST['newPassword'];

    // Validate the new password
    if (!empty($newPassword)) {
        // Escape special characters for security
        $newPassword = $conn->real_escape_string($newPassword);

        // Update the password in the database
        session_start();
        $user = $_SESSION['user']; // Get the logged-in user from session
        $sql = "UPDATE login SET password='$newPassword' WHERE user='$user'";

        if ($conn->query($sql) === TRUE) {
            echo "Password updated successfully";
        } else {
            echo "Error updating password: " . $conn->error;
        }
    } else {
        echo "Password cannot be empty";
    }
}
?>
