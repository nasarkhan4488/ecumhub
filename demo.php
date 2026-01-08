<?php
$to = "kurtlar1215225@gmail.com"; // Change to your recipient email
$subject = "Test Email from XAMPP";
$message = "This is a test email sent using XAMPP and Gmail.";
$headers = "From: shayans1215225@gmail.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-type:text/html;charset=UTF-8\r\n";

if (mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully!";
} else {
    echo "Failed to send email.";
}
?>