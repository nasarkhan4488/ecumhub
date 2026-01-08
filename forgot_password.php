<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('Source/Nav/config.php'); 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Forgot Password</title>
    <?php include 'Source/Nav/link.php'; ?>
    <style>
    /* Your existing CSS styles */
    /* Modal Styles */
    #tokenModal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 1;
        /* Sit on top */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.4);
        /* Black w/ opacity */
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        /* Could be more or less, depending on screen size */
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <!-- Header Section -->
    <?php include('Source/Nav/header.php'); ?>

    <!-- Breadcrumb Section Begin -->
    <section class="normal-breadcrumb set-bg" data-setbg="img/normal-breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="normal__breadcrumb__text">
                        <h2>Forgot Password</h2>
                        <p>Welcome to the official <?php echo $contact['website_name']?>.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Login Section Begin -->
    <section class="login spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="login__form">

                        <div id="forgotPasswordForm">
                            <h3>Enter Your Gmail</h3>
                            <form id="forgot-password-form">
                                <div class="input__item">
                                    <input type="email" name="email" placeholder="Enter your Gmail address" required>
                                    <span class="icon_mail"></span>
                                </div>
                                <button type="submit" class="site-btn">Send Verification Code</button>
                            </form>
                            <div class="message" id="response-message"></div>
                        </div>

                        <!-- Reset Password Form -->
                        <div id="resetPasswordForm" style="display:none;">
                            <h3>Reset Your Password</h3>
                            <form id="reset-password-form">
                                <div class="input__item">
                                    <input type="password" name="new_password" placeholder="Enter your new password"
                                        required>
                                    <span class="icon_lock"></span>
                                </div>
                                <div class="input__item">
                                    <input type="password" name="confirm_password"
                                        placeholder="Confirm your new password" required>
                                    <span class="icon_lock"></span>
                                </div>
                                <button type="submit" class="site-btn">Reset Password</button>
                            </form>
                            <div class="message" id="resetMessage"></div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="login__register">
                        <h3>Don’t Have An Account?</h3>
                        <a href="login.php" class="primary-btn">Login Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Login Section End -->

    <!-- Token Verification Modal -->
    <div id="tokenModal" style="display:none;">
        <div class="modal-content">
            <span class="close" id="closeModal">&times;</span>
            <h3>Verify Your Token</h3>
            <p>Please enter the verification code sent to your email:</p>
            <input type="text" id="verificationCode" placeholder="Enter your verification code" required>
            <button id="verifyToken" class="site-btn">Verify</button>
            <div class="message" id="verificationMessage"></div>
        </div>
    </div>

    <?php include('Source/Nav/footer.php'); ?>

    <script>
    $(document).ready(function() {
        $('#forgot-password-form').on('submit', function(event) {
            event.preventDefault(); // Prevent form submission

            $.ajax({
                url: 'ajax/resend_token.php', // The PHP file to handle the request
                type: 'POST',
                data: $(this).serialize(), // Serialize form data
                success: function(response) {
                    $('#response-message').html(response);

                    $('#tokenModal').show(); // Show the token verification modal
                },
                error: function() {
                    $('#response-message').html(
                        '<span class="error">An error occurred. Please try again.</span>'
                    );
                }
            });
        });

        // Close the modal when the user clicks on <span> (x)
        $('#closeModal').on('click', function() {
            $('#tokenModal').hide();
        });

        // Handle token verification
        $('#verifyToken').on('click', function() {
            var token = $('#verificationCode').val();
            var email = $("input[name='email']").val(); // Get the email from the input

            $.ajax({
                url: 'ajax/verify_token.php', // The PHP file to handle the token verification
                type: 'POST',
                data: {
                    token: token,
                    email: email
                },
                success: function(response) {
                    if (response === '1') {
                        // Hide the original form and show the reset password form
                        $('#forgot-password-form').hide();
                        $('#tokenModal').hide(); // Optionally hide the modal
                        $('#resetPasswordForm').show(); // Show the reset password form
                    } else {
                        $('#verificationMessage').html(
                            '<span class="error">Invalid token. Please try again.</span>'
                        );
                    }
                },
                error: function() {
                    $('#verificationMessage').html(
                        '<span class="error">An error occurred. Please try again.</span>'
                    );
                }
            });
        });
        $('#reset-password-form').on('submit', function(event) {
            event.preventDefault(); // Prevent form submission

            var newPassword = $("input[name='new_password']").val();
            var confirmPassword = $("input[name='confirm_password']").val();
            var email = $("input[name='email']").val(); // Retrieve the email

            if (newPassword !== confirmPassword) {
                $('#resetMessage').html('<span class="error">Passwords do not match.</span>');
                return;
            }

            $.ajax({
                url: 'ajax/reset_password.php', // PHP file to handle password reset
                type: 'POST',
                data: {
                    email: email,
                    new_password: newPassword
                },
                success: function(response) {
                    if (response === '1') {
                        $('#resetMessage').html(
                            '<span class="message">Password reset successfully.</span>');
                        // Optionally redirect to login page or any other page
                        window.location.href = 'login.php';
                    } else {
                        $('#resetMessage').html(
                            '<span class="error">Error resetting password. Please try again.</span>'
                        );
                    }
                },
                error: function() {
                    $('#resetMessage').html(
                        '<span class="error">An error occurred. Please try again.</span>'
                    );
                }
            });
        });

    });
    </script>

</body>

</html>