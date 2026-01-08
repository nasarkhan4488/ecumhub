<!DOCTYPE html>
<html lang="zxx">
<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1); 
include('Source/Nav/config.php'); 

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $image = $_FILES['profile_image'];
    $status = 'NEW';
    $token = bin2hex(random_bytes(32)); // Generate a random token
    $token_expiration = date('Y-m-d H:i:s', strtotime('+5 minutes'));
    $image_name = $image['name'];
    $image_tmp_name = $image['tmp_name'];
    $image_size = $image['size'];
    $image_error = $image['error'];
    $image_type = $image['type'];

    if ($image_type === 'image/jpeg' || $image_type === 'image/png' || $image_type === 'image/gif') {
        if ($image_error === 0) {
            $Image_path = 'img/profile/' . $image_name;
            move_uploaded_file($image_tmp_name, $Image_path);
        } else {
            echo "<script>alert('Error uploading image.')</script>";
        }
    } else {
        echo "<script>alert('Invalid image type. Only JPEG, PNG, and GIF are allowed.')</script>";  
    }

    if ($password === $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password

        // Insert the user data into the database
        $sql = "INSERT INTO `users` (`first_name`, `last_name`, `username`, `email`, `password`, `profile_image`, `token`, `token_expiration`, `Status`) 
                VALUES ('$first_name','$last_name','$username','$email','$hashed_password','$image_name','$token','$token_expiration','$status')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Show token in modal
                        document.getElementById('signupForm').reset();  // Reset the form after successful submission
                        document.getElementById('tokenModal').style.display = 'block';  // Display the modal
                        document.getElementById('displayEmail').value = '$email';  // Set the email value in the modal
                    });
                </script>";
            // Send the token to the user's email
            $to = $email;
            $subject = "Verify Your Account";
            $body = '
            <html>
<head>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }
    .footer {
        background-color: #070720;
        color: #ffffff;
        text-align: center;
        padding: 10px;
    }
    .footer {
        font-size: 12px;
    }
    .content {
        padding: 20px;
    }
    .logo {
        width: 150px;
        margin: 20px 0;
    }
    .normal-breadcrumb {
        height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .set-bg {
        background-repeat: no-repeat;
        background-size: cover;
        background-position: top center;
    }
    .container {
        max-width: 1170px;
    }
    .text-center {
        text-align: center !important;
    }
    .normal__breadcrumb__text {
        font-size: 28px;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 20px;
    }
    .content {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        background-color: #1a1b33;
        padding: 20px;
        color: #ffffff;
    }
    </style>
</head>
<body>
    <section class="normal-breadcrumb set-bg" data-setbg="../img/normal-breadcrumb.jpg" style="background-image: url(../img/normal-breadcrumb.jpg);">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="normal__breadcrumb__text">
                        <h2>Message From ' . htmlspecialchars($first_name) . ' ' . htmlspecialchars($last_name) . '</h2>
                        <p>Welcome to the official ' . htmlspecialchars($contact["website_name"] ?? 'Our Website') . '</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="content">
    
                <h2>Welcome to Our Website, '.$first_name.'!</h2>
                <p>Thank you for registering. Please verify your account by clicking the link below:</p>
                <p><a href="http://yourwebsite.com/verify.php?token=$token&email=$email">Verify Account</a></p>
                <p>This link will expire in 5 minutes.</p>
                <p>If you did not register, please ignore this email.</p>
                <div class="text-center footer">
        <p>
            Copyright &copy;<script>
            document.write(new Date().getFullYear());
            </script> All rights reserved | This Website is made with by <a href="https://kardeveloper.com" target="_blank">Kurtlar Developer</a>
        </p>
    </div>
            </body>
            </html>';
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: $gmail" . "\r\n"; // Replace with your email

            if (mail($to, $subject, $body, $headers)) {
                echo "<script>alert('Verification email sent. Please check your email.')</script>";
            } else {
                echo "<script>alert('Error sending verification email. Please try again.')</script>";
            }

            $user_id = mysqli_insert_id($conn);
            // Insert an admin notification for the new user registration
            $notification_message = "A new user has registered with the username \"$username\".";
            $notification_sql = "INSERT INTO `notifications` (`user_id`, `event_type`, `entity_type`, `entity_id`, `message`, `status`, `notification_type`)
                                 VALUES (NULL, 'New User Registered', 'User', '$user_id', '$notification_message', 'unread', 'admin')";
            mysqli_query($conn, $notification_sql);
        } else {
            echo "<script>alert('Error creating user.')</script>";
        }
    } else {
        echo "<script>alert('Passwords do not match.')</script>";
    }
}
?>

<!-- Your existing HTML code -->

<head>
    <title>Anime | Sign Up</title>
    <?php include 'Source/Nav/link.php'; ?>
</head>

<body>

    <!-- Header Section Begin -->
    <?php include('Source/Nav/header.php'); ?>

    <!-- Normal Breadcrumb Begin -->
    <section class="normal-breadcrumb set-bg" data-setbg="img/normal-breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="normal__breadcrumb__text">
                        <h2>Sign Up</h2>
                        <p>Welcome to the official Anime blog.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Normal Breadcrumb End -->

    <!-- Signup Section Begin -->
    <section class="signup spad">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="login__form" style="border: none;">
                        <h3>Sign Up</h3>
                        <form action="signup.php" id="signupForm" method="POST" enctype="multipart/form-data">
                            <div class="input__item">
                                <input type="text" name="first_name" placeholder="First Name" required>
                                <span class="icon_profile"></span>
                            </div>
                            <div class="input__item">
                                <input type="text" name="last_name" placeholder="Last Name" required>
                                <span class="icon_profile"></span>
                            </div>
                            <div class="input__item">
                                <input type="email" name="email" placeholder="Email address" required>
                                <span class="icon_mail"></span>
                            </div>
                            <div class="input__item">
                                <input type="text" id="username" name="username" placeholder="Username" required>
                                <span class="icon_profile"></span>
                                <small id="usernameStatus"></small>
                            </div>
                            <div class="input__item">
                                <input type="password" name="password" id="password" placeholder="Password" required>
                                <span class="icon_lock"></span>
                            </div>
                            <div class="input__item">
                                <input type="password" name="confirm_password" id="confirm_password"
                                    placeholder="Confirm Password" required>
                                <span class="icon_lock"></span>
                            </div>
                            <div class="input__item">
                                <input type="file" name="profile_image" id="profile_image" required>
                                <span class="icon_image"></span>
                            </div>
                            <div class="input__item">
                                <label class="text-white">
                                    <input type="checkbox" name="terms" required>
                                    I agree to the Terms and Conditions
                                </label>
                            </div>
                            <button type="submit" name="submit" class="site-btn">Sign Up</button>
                        </form>
                        <h5>Already have an account? <a href="login.php">Log In!</a></h5>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Signup Section End -->
    <?php 
include 'Source/Nav/config.php'; 

// Check if the token submission form is submitted
if (isset($_POST['submit_token'])) {
    $email_Display = $_POST['displayEmail'];
    $token_Display = $_POST['token'];

    // Debugging
    // echo "Email: " . $email_Display . " Token: " . $token_Display;

    // Check if the provided token is valid and not expired
    $sql = "SELECT * FROM `users` WHERE `email` = '$email_Display' AND `token` = '$token_Display' AND `token_expiration` > NOW()";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // If token is valid, update the user's status to VERIFIED
        $sql = "UPDATE `users` SET `Status` = 'VERIFIED' WHERE `email` = '$email_Display'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script>alert('User activated successfully.');
            window.location.href = 'login.php';
        </script>";
            exit;
        } else {
            echo "<script>alert('Error activating user.')</script>";
        }
    } else {
        echo "<script>alert('Invalid or expired token.')</script>";
    }
}
?>

    <!-- Token Submission Modal -->
    <div id="tokenModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Submit Token</h2>
            <form id="tokenForm" method="POST">
                <div class="input__item">
                    <input type="hidden" name="displayEmail" id="displayEmail">
                    <input type="text" name="token" placeholder="Enter Token" required>
                </div>
                <button type="submit" name="submit_token" class="site-btn">Submit</button>
            </form>
        </div>
    </div>

    <script>
    // Show token modal after user registration
    function showTokenModal(email) {
        document.getElementById('tokenModal').style.display = 'block';
        document.getElementById('displayEmail').value = email; // Set the email in the hidden field
    }

    // Hide modal when the close button is clicked
    document.querySelector('.close').addEventListener('click', function() {
        document.getElementById('tokenModal').style.display = 'none';
    });
    </script>


    <!-- Footer Section Begin -->
    <?php include('Source/Nav/footer.php'); ?>

    <script>
    // Check username availability
    $('#username').on('keyup', function() {
        var username = $(this).val();
        if (username !== '') {
            $.ajax({
                url: 'ajax/check_username.php',
                method: 'POST',
                data: {
                    username: username
                },
                success: function(response) {
                    $('#usernameStatus').text(response);
                }
            });
        } else {
            $('#usernameStatus').text('');
        }

        function closeModal() {
            document.getElementById('tokenModal').style.display = 'none';
        }
    });
    </script>

</body>

</html>