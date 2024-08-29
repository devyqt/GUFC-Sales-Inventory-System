// LoginModal.js

document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('forgotPasswordModal');
    const changePasswordModal = document.getElementById('changePasswordModal');
    const forgotPasswordLink = document.getElementById('forgotPasswordLink');
    const closeModal = document.getElementsByClassName('close');

    // Open the forgot password modal
    forgotPasswordLink.onclick = function() {
        modal.style.display = 'block';
    }

    // Close the modals
    Array.from(closeModal).forEach(closeButton => {
        closeButton.onclick = function() {
            modal.style.display = 'none';
            changePasswordModal.style.display = 'none';
        }
    });

    // Click outside of the modal to close it
    window.onclick = function(event) {
        if (event.target == modal || event.target == changePasswordModal) {
            modal.style.display = 'none';
            changePasswordModal.style.display = 'none';
        }
    }
});

// Function to validate the authentication code
function validateCode() {
    const fixedAuthCode = "190108"; // Set your fixed authentication code here
    const enteredAuthCode = document.getElementById("authCode").value;

    if (enteredAuthCode === fixedAuthCode) {
        document.getElementById('forgotPasswordModal').style.display = 'none';
        document.getElementById('changePasswordModal').style.display = 'block';
    } else {
        document.getElementById("error-message").style.display = "block";
    }
}

// Function to change the password
function changePassword() {
    const newPassword = document.getElementById("newPassword").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    if (newPassword === confirmPassword && newPassword.length > 0) {
        // Send new password to the server
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'change_password.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                alert("Password changed successfully.");
                document.getElementById('changePasswordModal').style.display = 'none';
            } else {
                alert("An error occurred while changing the password.");
            }
        };
        xhr.send('newPassword=' + encodeURIComponent(newPassword));
    } else {
        document.getElementById("passwordError").style.display = "block";
    }
}
