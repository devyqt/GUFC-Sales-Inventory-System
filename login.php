<?php
// Include the database connection script
include('connect.php');

// Initialize error variables
$userError = $passwordError = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['user'];
    $password = $_POST['password'];

    // Validate input fields
    if (empty($user)) {
        $userError = "Please enter your username.";
    }
    if (empty($password)) {
        $passwordError = "Please enter your password.";
    }

    // If no validation errors, proceed with the login process
    if (empty($userError) && empty($passwordError)) {
        // Escape special characters for security
        $user = $conn->real_escape_string($user);
        $password = $conn->real_escape_string($password);

        // Create a query to check the user's credentials
        $sql = "SELECT * FROM login WHERE user = '$user' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            session_start(); // Start a session
            $_SESSION['loggedin'] = true;
            $_SESSION['user'] = $user;
            // Redirect to a different page
            header("Location: index.php");
            exit; // Ensure no further code is executed after the redirection
        } else {
            // Invalid credentials
            $passwordError = "Invalid username or password";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solane Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <img src="Background.png" alt="Background" class="background-img">
        <div class="login-box">
            <img src="Solane Logo.png" alt="Solane Logo" class="logo">
            <form id="loginForm" method="post" action="login.php">
                <h2>Login</h2>
                <div class="input-group">
                    <label for="user">User</label>
                    <input type="text" id="user" name="user" placeholder="User" value="<?php echo isset($user) ? $user : ''; ?>">
                    <div class="caution">
                        <?php if (isset($userError)) echo $userError; ?>
                    </div>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password">
                    <div class="caution">
                        <?php if (isset($passwordError)) echo $passwordError; ?>
                    </div>
                </div>
                    <button type="submit">Login</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

