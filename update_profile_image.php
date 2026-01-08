<?php
session_start();
include('Source/Nav/config.php');

if (isset($_POST['update_image'])) {
    $user_id = $_SESSION['id'];
    $profile_image = $_FILES['profile_image']['name'];
    $target_dir = "img/profile/";
    $target_file = $target_dir . basename($profile_image);
    
    // Move the uploaded file to the server directory
    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
        $query = "UPDATE users SET profile_image = '$profile_image' WHERE id = $user_id";
        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = "Profile image updated successfully!";
        } else {
            $_SESSION['message'] = "Error updating profile image!";
        }
    } else {
        $_SESSION['message'] = "Failed to upload image!";
    }
    header('Location: profile.php');
}
?>