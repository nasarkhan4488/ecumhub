<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); 
include('Source/Nav/config.php'); 

// Fetch contact information from the database
$query = "SELECT `id`, `email`, `phone`, `address`, `description`, `created_at`, `updated_at`, `website_name`, `logo` FROM `contact_info` WHERE 1";
$result = $conn->query($query);

// Initialize variables to hold the contact information
$contact = null;
if ($result->num_rows > 0) {
    // Fetch the first row (assuming there is only one row of contact info)
    $contact = $result->fetch_assoc();
}

// Close the database connection
$conn->close();

// Initialize variables for the contact form
$formError = '';
$formSuccess = '';

//<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Initialize error and success variables
    $formError = '';
    $formSuccess = '';

    // Validate form fields
    if (empty($name) || empty($email) || empty($message)) {
        $formError = 'Please fill in all the fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formError = 'Please provide a valid email address.';
    } else {
        // Set up the email parameters
        $to = 'yourdefaultemail@example.com'; // Replace with your actual email address
        $subject = "New Contact Us Form Submission";
        $body = '<html>
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
    <section class="normal-breadcrumb set-bg" data-setbg="img/normal-breadcrumb.jpg" style="background-image: url(img/normal-breadcrumb.jpg);">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="normal__breadcrumb__text">
                        <h2>Message From ' . htmlspecialchars($name) . '</h2>
                        <p>Welcome to the official ' . htmlspecialchars($contact["website_name"] ?? 'Our Website') . '</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="content">
        <p>Name of Messenger: ' . htmlspecialchars($name) . ',</p>
        <p>Email of Messenger: <strong>' . htmlspecialchars($email) . '</strong></p>
        <p>Message: <strong>' . nl2br(htmlspecialchars($message)) . '</strong></p>
    </div>
    <div class="text-center footer">
        <p>
            Copyright &copy;<script>
            document.write(new Date().getFullYear());
            </script> All rights reserved | This Website is made with by <a href="https://kardeveloper.com" target="_blank">Kurtlar Developer</a>
        </p>
    </div>
</body>
</html>';

        // Send email
        if (mail($to, $subject, $body, "From: " . $email . "\r\nContent-Type: text/html; charset=UTF-8")) {
            $formSuccess = 'Thank you for reaching out. We will get back to you shortly.';
        } else {
            $formError = 'Sorry, your message could not be sent. Please try again later.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Contact Us</title>
    <?php include 'Source/Nav/link.php'; ?>
</head>
<style>
/* General Styling */
body {
    font-family: 'Poppins', sans-serif;
    color: #fff;
    background-color: #0a0b1e;
}

/* Styles for the Contact Section */
.contact__info__text {
    margin-bottom: 20px;
    background-color: #1a1b33;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
}

.contact__info__text h4 {
    font-size: 24px;
    margin-bottom: 15px;
    color: #e53637;
    border-bottom: 2px solid #e53637;
    padding-bottom: 5px;
}

.contact__info__text ul {
    list-style-type: none;
    padding-left: 0;
    font-size: 16px;
}

.contact__info__text li {
    margin-bottom: 15px;
    color: #ffffffd9;
}

.contact__info__text a {
    text-decoration: none;
    color: #e53637;
}

.contact__info__text a:hover {
    color: #ffaa00;
}

.contact__info__text img {
    max-width: 200px;
    height: auto;
    margin-top: 15px;
    border: 2px solid #e53637;
    border-radius: 10px;
}

.contact__info__text p {
    color: #e53637;
    font-style: italic;
}

.contact-form {
    background-color: #1a1b33;
    padding: 20px;
    border-radius: 10px;
    margin-top: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
}

.contact-form h4 {
    color: #e53637;
}

.contact-form input,
.contact-form textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #e53637;
    border-radius: 5px;
    background-color: #2c2d4d;
    color: #fff;
}

.contact-form input[type="submit"] {
    background-color: #e53637;
    color: #fff;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}

.contact-form input[type="submit"]:hover {
    background-color: #ffaa00;
}

.error {
    color: #ff4f4f;
    margin-bottom: 15px;
}

.success {
    color: #4caf50;
    margin-bottom: 15px;
}
</style>

<body>

    <!-- Header Section -->
    <?php include('Source/Nav/header.php'); ?>
    <!-- Header End -->

    <!-- Breadcrumb Section Begin -->
    <section class="normal-breadcrumb set-bg" data-setbg="img/normal-breadcrumb.jpg"
        style="background-image: url(&quot;img/normal-breadcrumb.jpg&quot;);">

        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="normal__breadcrumb__text">
                        <h2>Contact Us</h2>
                        <p>Welcome to the official
                            <?php echo htmlspecialchars($contact['website_name'] ?? 'Our Website'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb End -->

    <!-- Contact Section Begin -->
    <section class="contact spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="contact__info__text">
                        <h4>Contact Information</h4>
                        <ul>
                            <?php if ($contact): ?>
                            <li><strong>Website Name:</strong> <?php echo htmlspecialchars($contact['website_name']); ?>
                            </li>
                            <li><strong>Email:</strong> <a
                                    href="mailto:<?php echo htmlspecialchars($contact['email']); ?>"><?php echo htmlspecialchars($contact['email']); ?></a>
                            </li>
                            <li><strong>Phone:</strong> <?php echo htmlspecialchars($contact['phone']); ?></li>
                            <li><strong>Address:</strong> <?php echo htmlspecialchars($contact['address']); ?></li>
                            <li><strong>Description:</strong>
                                <?php echo nl2br(htmlspecialchars($contact['description'])); ?></li>
                            <li><strong>Updated At:</strong>
                                <?php echo date('F j, Y', strtotime($contact['updated_at'])); ?></li>
                            <?php else: ?>
                            <p>No contact information available.</p>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <!-- Contact Form -->
                    <div class="contact-form">
                        <h4>Send Us a Message</h4>
                        <?php if ($formError): ?>
                        <div class="error"><?php echo $formError; ?></div>
                        <?php elseif ($formSuccess): ?>
                        <div class="success"><?php echo $formSuccess; ?></div>
                        <?php endif; ?>
                        <form action="" method="POST">
                            <input type="text" name="name" placeholder="Your Name" required>
                            <input type="email" name="email" placeholder="Your Email" required>
                            <textarea name="message" placeholder="Your Message" rows="6" required></textarea>
                            <input type="submit" value="Send Message">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact Section End -->

    <!-- Footer Section -->
    <?php include('Source/Nav/footer.php'); ?>
</body>

</html>