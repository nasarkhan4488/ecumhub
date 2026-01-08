<?php
session_start(); // Start the session

// Destroy all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the login page or home page
header("Location: index.php"); // Change this to your login page URL
exit();
?>
