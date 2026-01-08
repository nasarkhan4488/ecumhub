<?php
session_start();
error_reporting(0);
include('Source/Nav/config.php');

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

// Fetch the logged-in user's data
$user_id = $_SESSION['id']; 
$query = "SELECT `id`, `first_name`, `last_name`, `username`, `email`, `profile_image`, `Status`, `created_at` FROM `users` WHERE `id` = $user_id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result);
$first_name = $row['first_name'];
$last_name = $row['last_name'];
$profile_image = $row['profile_image'];
$username = $row['username'];
$email = $row['email'];

?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>Profile Settings</title>
    <?php include('Source/Nav/link.php');?>
</head>

<style>
/* Style for forms and layout */
.settings-form {
    background-color: #f7f7f7;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.settings-form h4 {
    margin-bottom: 20px;
}

.settings-form input[type="text"],
.settings-form input[type="email"],
.settings-form input[type="password"],
.settings-form input[type="file"] {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.settings-form button {
    background-color: #f39c12;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
}

.settings-form button:hover {
    background-color: #e67e22;
}
</style>

<body>
    <?php include('Source/Nav/header.php'); ?>

    <!-- Normal Breadcrumb Begin -->
    <section class="normal-breadcrumb set-bg" data-setbg="img/normal-breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="normal__breadcrumb__text">
                        <h2>Settings</h2>
                        <p>Update your profile information</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Normal Breadcrumb End -->

    <!-- Profile Section Begin -->
    <section class="profile spad">
        <div class="container">
            <div class="row justify-content-center">

                <!-- Update Profile Image Form -->
                <div class="col-lg-6">
                    <form class="settings-form" action="update_profile_image.php" method="POST"
                        enctype="multipart/form-data">
                        <h4>Update Profile Image</h4>
                        <div class="form-group">
                            <label for="profile_image">Current Profile Image:</label><br>
                            <img src="img/profile/<?php echo $profile_image; ?>" alt="Profile Image" width="100"
                                height="100">
                        </div>
                        <input type="file" name="profile_image" accept="image/*" required>
                        <button type="submit" name="update_image">Update Image</button>
                    </form>
                </div>

                <!-- Update Name and Email Form -->
                <div class="col-lg-6">
                    <form class="settings-form" action="update_profile.php" method="POST">
                        <h4>Update Name and Email</h4>
                        <input type="text" name="first_name" value="<?php echo $first_name; ?>" placeholder="First Name"
                            required>
                        <input type="text" name="last_name" value="<?php echo $last_name; ?>" placeholder="Last Name"
                            required>
                        <input type="email" name="email" value="<?php echo $email; ?>" placeholder="Email" required>
                        <button type="submit" name="update_profile">Update Info</button>
                    </form>
                </div>

                <!-- Update Password Form -->
                <div class="col-lg-6">
                    <form class="settings-form" action="update_password.php" method="POST">
                        <h4>Update Password</h4>
                        <input type="password" name="current_password" placeholder="Current Password" required>
                        <input type="password" name="new_password" placeholder="New Password" required>
                        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                        <button type="submit" name="update_password">Update Password</button>
                    </form>
                </div>

            </div>
        </div>
    </section>
    <!-- Profile Section End -->

    <?php include('Source/Nav/footer.php'); ?>

</body>

</html>