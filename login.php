<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); 
include('Source/Nav/config.php'); 

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists in the database
    $stmt = "SELECT * FROM users WHERE `email` = '$email'";
    $result = mysqli_query($conn, $stmt);
    $user = mysqli_fetch_array($result);

    if ($user) {
        echo $user['email'];
        // Verify password
        if (password_verify($password, $user['password'])) {
            if ($user['Status'] == 'ADMIN') {
                // User verified, log them in
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['Status'] = $user['Status'];
                $_SESSION['id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['username'] = $user['username'];
                echo "<script>
                    window.location.href = 'Dashboard/index.php';
                </script>";

                exit;
            }
            else if ($user['Status'] === 'NEW') {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var email = '" . $user['email'] . "';
                        resendToken(email); // Call resendToken function with email
                    });
                </script>";
            }
            else if ($user['Status'] === 'VERIFIED') {
                // User verified, log them in
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['id'] = $user['id'];
                $_SESSION['email'] = $user['email'];

                header('Location: index.php'); // Redirect to the homepage
                exit;
            }
            
        } else {
            // Invalid credentials
            echo "<p style='color: red;'>Invalid password.</p>";
        }
    } else {
        // User not found
        echo "<p style='color: red;'>User not found. Please register first.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<style>
/* Modal Styles */
#tokenPopUp {
    display: none;
    /* Hidden by default */
    position: fixed;
    /* Stay in place */
    z-index: 1000;
    /* Sit on top */
    left: 0;
    top: 0;
    width: 100%;
    /* Full width */
    height: 100%;
    /* Full height */
    overflow: auto;
    /* Enable scroll if needed */
    background-color: rgba(0, 0, 0, 0.5);
    /* Black w/ opacity */
}

#tokenPopUp div {
    background-color: #fff;
    margin: 15% auto;
    /* 15% from the top and centered */
    padding: 20px;
    border: 1px solid #888;
    width: 300px;
    /* Could be more or less, depending on screen size */
    border-radius: 5px;
    /* Rounded corners */
    text-align: center;
    /* Centered text */
}

.site-btn {
    background-color: #007bff;
    /* Customize button color */
    color: white;
    /* Button text color */
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
}

.site-btn:hover {
    background-color: #0056b3;
    /* Darker shade on hover */
}
</style>

<head>
    <title>Login</title>
    <?php include 'Source/Nav/link.php'; ?>
</head>

<body>

    <!-- Header Section -->
    <?php include('Source/Nav/header.php'); ?>
    <!-- Header End -->

    <!-- Breadcrumb Section Begin -->
    <section class="normal-breadcrumb set-bg" data-setbg="img/normal-breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="normal__breadcrumb__text">
                        <h2>Login</h2>
                        <p>Welcome to the official <?php echo $contact['website_name']?>.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb End -->

    <!-- Login Section Begin -->
    <section class="login spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="login__form">
                        <h3>Login</h3>
                        <form action="" method="POST">
                            <div class="input__item">
                                <input type="email" name="email" placeholder="Email address" required>
                                <span class="icon_mail"></span>
                            </div>
                            <div class="input__item">
                                <input type="password" id="password" name="password" placeholder="Password" required>
                                <span class="icon_lock"></span>
                                <i class="toggle-password" onclick="togglePassword()">👁️</i>
                            </div>
                            <button type="submit" name="submit" class="site-btn">Login Now</button>
                        </form>
                        <a href="forgot_password.php" class="forget_pass">Forgot Your Password?</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="login__register">
                        <h3>Don’t Have An Account?</h3>
                        <a href="signup.php" class="primary-btn">Register Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Login Section End -->

    <!-- JavaScript for Show/Hide Password -->
    <script>
    function togglePassword() {
        var passwordField = document.getElementById("password");
        var passwordIcon = document.querySelector(".toggle-password");
        if (passwordField.type === "password") {
            passwordField.type = "text";
            passwordIcon.innerHTML = "🙈"; // Change icon to hide password
        } else {
            passwordField.type = "password";
            passwordIcon.innerHTML = "👁️"; // Change icon to show password
        }
    }
    </script>

    <!-- Optional CSS for the toggle password icon -->
    <style>
    .input__item {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
    }
    </style>


    <!-- Token Verification Pop-Up -->
    <div id="tokenPopUp">
        <div>
            <h3>Token Verification</h3>
            <form id="verifyTokenForm" method="POST">
                <input type="email" readonly name="email" id="email">
                <input type="text" id="token" name="token" placeholder="Enter Token" required>
                <button type="button" id="verify" class="site-btn">Verify Token</button>
                <button type="button" class="site-btn" onclick="hideVerificationPopUp()">Cancel</button>
            </form>
            <input type="email" readonly name="email" id="resendEmail">
            <button type="submit" id="resend" class="site-btn">Resend Token</button>
            <div id="tokenMessage" style="margin-top: 10px;"></div> <!-- To display success/error messages -->
        </div>
    </div>

    <?php include('Source/Nav/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    function resendToken(email) {
        $.ajax({
            url: "ajax/resend_token.php", // The backend URL
            type: "POST", // Method type
            data: {
                email: email
            }, // Data sent to the backend (email)
            success: function(response) { // On successful response
                // Show the pop-up and populate the email fields
                document.getElementById("tokenPopUp").style.display = "block";
                document.getElementById("email").value = email; // Set email in the form
                document.getElementById("resendEmail").value = email; // Set email in the resend form
                document.getElementById("tokenMessage").innerText = "Token has been sent to " +
                    email; // Optional success message
            },
            error: function(xhr, status, error) { // On error
                Swal.fire('Error', 'Failed to resend the token.', 'error');
            }
        });
    }

    function hideVerificationPopUp() {
        document.getElementById("tokenPopUp").style.display = "none"; // Hide the pop-up
        document.getElementById("email").value = ""; // Reset email field
        document.getElementById("resendEmail").value = ""; // Reset resend email field
        document.getElementById("tokenMessage").innerText = ""; // Reset token message
    }


    $(document).ready(function() {
        $('#resend').on('click', function() {
            // Get the value of the email input
            var email = $('#resendEmail').val();

            // Hide the verification pop-up
            hideVerificationPopUp();

            // Call the resendToken function with the email
            resendToken(email);
        });
    });

    $(document).ready(function() {
        $('#verify').on('click', function(event) {
            event.preventDefault(); // Prevent the default form submission

            var token = $('#token').val(); // Get the token value
            var email = $('#email').val(); // Get the email value
            // Check if the token is not empty
            if (token) {
                $.ajax({
                    url: "ajax/verify_token.php", // The backend URL
                    type: "POST", // Method type
                    data: {
                        token: token,
                        email: email
                    }, // Data sent to the backend (token and email)
                    success: function(data) {
                        if (data == 1) {
                            alert(
                                "Token verified successfully! Your account status has been updated to VERIFIED."
                            );
                            hideVerificationPopUp();
                        } else if (data == 9) {
                            alert("Invalid or expired token.");
                        } else {
                            alert(data);
                        }
                    }
                });
            } else {
                document.getElementById("tokenMessage").innerText =
                    "Please enter the token."; // Error message for empty token
            }
        });
    });
    </script>

</body>

</html>