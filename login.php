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
    <link rel="stylesheet" href="css/login.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/LoginModal.css">
</head>
<body>
    <div class="login-container">
        <img src="images/Background.png" alt="Background" class="background-img">
        <div class="login-box">
            <img src="images/Solane Logo.png" alt="Solane Logo" class="logo">
            <form id="loginForm" method="post" action="login.php">
                <h2>Login</h2>
                <div class="input-group">
                    <label for="user">&nbsp;User</label>
                    <input type="text" id="user" name="user" placeholder="User" value="<?php echo isset($user) ? $user : ''; ?>">
                    <div class="caution">
                        <?php if (isset($userError)) echo $userError; ?>
                    </div>
                </div>
                <div class="input-group">
                    <label for="password">&nbsp;Password</label>
                    <input type="password" id="password" name="password" placeholder="Password">
                    <div class="caution">
                        <?php if (isset($passwordError)) echo $passwordError; ?>
                    </div>
                    <div class="forgotpass">
                        <a href="#" id="forgotPasswordLink">Forgot Password?</a>
                    </div>
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>

    <!-- Forgot Password Modal -->
    <div id="forgotPasswordModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Forgot Password</h2>
            <form id="forgotPasswordForm">
                <div class="input-group">
                    <label for="authCode">Enter Authentication Code</label>
                    <input type="text" id="authCode" name="authCode" placeholder="Authentication Code" required>
                </div>
                <div class="caution" id="error-message" style="display:none;">
                    Invalid authentication code.
                </div>
                <button type="button" onclick="validateCode()">Submit</button>
            </form>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div id="changePasswordModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Change Password</h2>
            <form id="changePasswordForm">
                <div class="input-group">
                    <label for="newPassword">New Password</label>
                    <input type="password" id="newPassword" name="newPassword" placeholder="New Password" required>
                </div>
                <div class="input-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
                </div>
                <div class="caution" id="passwordError" style="display:none;">
                    Passwords do not match or are invalid.
                </div>
                <button type="button" onclick="changePassword()">Change Password</button>
            </form>
        </div>
    </div>

    <script src="js/LoginModal.js"></script>
</body>
</html>
