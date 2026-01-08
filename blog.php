<?php
session_start();
error_reporting(0);
include('Source/Nav/config.php');
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
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
    /* Ensures images fill the space while maintaining aspect ratio */
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
.col-lg-4 {
    height: 380px;
}

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
                        <h2>Our Blog</h2>
                        <p>Welcome to the official <?php echo $contact['website_name']?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Normal Breadcrumb End -->

    <!-- Blog Section Begin -->
    <section class="blog spad">
        <div class="container">
            <div class="row g-4">
                <?php 
        
$query = "SELECT `id`, `name`, `description`, `image`, `status`, `created_at` FROM `blog_table` WHERE `status` = 'active'";
$result = mysqli_query($conn, $query);
        while($row = mysqli_fetch_assoc($result)){ ?>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="blog-card">
                        <img src="img/blog/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                        <div class="blog-card-date">
                            <i class="fa fa-calendar"></i> <?php echo date("d M Y", strtotime($row['created_at'])); ?>
                        </div>
                        <a href="blog-details.php?id=<?php echo $row['id']; ?>">
                            <div class="blog-card-content">
                                <h5 class="text-white"><?php echo $row['name']; ?></h5>
                                <p><?php echo substr($row['description'], 0, 60); ?>...</p>
                            </div>
                        </a>

                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>
    <!-- Blog Section End -->

    <?php include('Source/Nav/footer.php'); ?>

</body>

</html>