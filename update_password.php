<?php
session_start();
include('Source/Nav/config.php');

if (isset($_POST['update_password'])) {
    $user_id = $_SESSION['id'];
    $current_password = mysqli_real_escape_string($conn, $_POST['current_password']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Fetch the current password from the database
    $query = "SELECT password FROM users WHERE id = $user_id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    
    // Verify current password
    if (password_verify($current_password, $row['password'])) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query = "UPDATE users SET password = '$hashed_password' WHERE id = $user_id";
            if (mysqli_query($conn, $update_query)) {
                $_SESSION['message'] = "Password updated successfully!";
            } else {
                $_SESSION['message'] = "Error updating password!";
            }
        } else {
            $_SESSION['message'] = "New passwords do not match!";
        }
    } else {
        $_SESSION['message'] = "Incorrect current password!";
    }
    header('Location: profile.php');
}
?>