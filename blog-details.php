<?php
session_start();
error_reporting(0);
include('Source/Nav/config.php');
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <?php include 'Source/Nav/link.php'; ?>
</head>

<body>
   <?php include 'Source/Nav/header.php'; ?>

    <!-- Blog Details Section Begin -->
    <section class="blog-details spad">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <?php 
                $select = mysqli_query($conn, "SELECT * FROM `blog_table` WHERE `status` = 'active' AND `id` = '$_GET[id]'");
                while($row = mysqli_fetch_assoc($select)){
                ?>
                <div class="col-lg-8">
                    <div class="blog__details__title">
                        <h6><?php echo $row['created_at']?></h6>
                        <h2><?php echo $row['name'] ?></h2>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="blog__details__pic">
                        <img src="img/blog/<?php echo $row['image']?>" style="height: 600px;" alt="">
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="blog__details__content">
                        <div class="blog__details__text text-white">
                           <?php echo $row['description']?>
                        </div>
                    </div>
                    </div>
                 <?php } ?>
            </div>
            </div>
        </section>
        <!-- Blog Details Section End -->
         <?php include('Source/Nav/footer.php'); ?>
         <script>document.addEventListener('DOMContentLoaded', function () {
    // Get all reply buttons
    const replyButtons = document.querySelectorAll('.reply-btn');

    replyButtons.forEach(button => {
        button.addEventListener('click', function () {
            const commentId = this.getAttribute('data-comment-id'); // Get comment ID from the data attribute

            // Set the comment ID in the hidden input field
            document.getElementById('commentid').value = commentId;

            // Display the reply form
            const replyFormContainer = document.getElementById('reply-form-container');
            replyFormContainer.style.display = 'block';

            // Optionally scroll to the reply form
            replyFormContainer.scrollIntoView({ behavior: 'smooth' });
        });
    });
});
</script>
    </body>

    </html>