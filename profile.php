<?php
session_start();
error_reporting(0);
include('Source/Nav/config.php');

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    // If not logged in, redirect to the login page or show an error
    header('Location: login.php');
    exit();
}

// Fetch the logged-in user's data
$user_id = $_SESSION['id']; // Assuming 'id' is stored in session
$query = "SELECT `id`, `first_name`, `last_name`, `username`, `email`, `profile_image`, `Status`, `created_at` FROM `users` WHERE `id` = $user_id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result);
$first_name = $row['first_name'];
$last_name = $row['last_name'];
$profile_image = $row['profile_image'];
$username=$row['username'];
$email=$row['email'];

$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>Profile</title>
    <?php include('Source/Nav/link.php');?>
</head>
<style>
.blog-card {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
    background-color: #000;
    color: white;
    margin-bottom: 30px;
}

.blog-card img {
    width: 100%;
    height: auto;
    object-fit: cover;
}

.blog-card-content {
    position: absolute;
    bottom: 20px;
    left: 20px;
    z-index: 2;
    background-color: rgba(0, 0, 0, 0.5);
    padding: 10px;
    border-radius: 5px;
}

.blog-card h5,
.blog-card p {
    margin: 0;
}

.blog-card-date {
    position: absolute;
    top: 20px;
    left: 20px;
    font-size: 12px;
    color: #f39c12;
}

/* Adjust height for different screen sizes */
.col-lg-4,
.col-md-6 {
    height: 380px;
}

@media (max-width: 768px) {
    .blog-card {
        height: 250px;
    }
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
                        <h2>Your Profile</h2>
                        <p>Welcome to the official <?php echo $contact['website_name']?></p>
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
                <div class="col-lg-4 col-md-6">
                    <div class="blog-card">
                        <img src="img/profile/<?php echo $profile_image; ?>" alt="Profile Image">
                        <div class="blog-card-content text-white">
                            <h5><?php echo $first_name . " " . $last_name; ?></h5>
                            <p>Username: <?php echo $username; ?></p>
                            <p>Email: <?php echo $email; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Profile Section End -->

    <?php include('Source/Nav/footer.php'); ?>

</body>

</html>