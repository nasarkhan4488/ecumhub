<?php
session_start();
error_reporting(0);
include('Source/Nav/config.php');

// Function to format the views count
function format_views($views) {
    if ($views >= 1000000) {
        return round($views / 1000000, 1) . 'm+'; // Millions
    } elseif ($views >= 1000) {
        return round($views / 1000, 1) . 'k+'; // Thousands
    } else {
        return $views; // Less than 1000
    }
}
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>Cart</title>
    <?php include('Source/Nav/link.php') ?>
    <!-- Include jQuery for AJAX functionality -->
</head>

<style>
.set-bg {
    background-size: cover;
    background-position: center;
    height: 300px;
    /* Adjust height as necessary */
    width: 100%;
}
</style>

<body>
    <?php include('Source/Nav/header.php')?>

    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="./index.php"><i class="fa fa-home"></i> Home</a>
                        <a href="./cart.php">Cart</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Anime Section Begin -->
    <section class="anime-details spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="list_product">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="section-title">
                                    <h4>Save Product</h4>
                                </div>
                            </div>
                        </div>

                        <!-- Saved Products will be dynamically loaded here -->
                        <div class="row" id="saved-products">
                            <!-- AJAX will populate this section -->
                        </div>

                        <!-- Pagination links will be dynamically loaded here -->
                        <div id="pagination" class="pagination-container">
                            <!-- Pagination will be loaded here -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Anime Section End -->

    <?php include('Source/Nav/footer.php'); ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        // Fetch saved products for page 1 by default
        fetchSavedProducts(1);

        // Handle pagination link click
        $(document).on('click', '.pagination-link', function(e) {
            e.preventDefault();
            var page = $(this).data('page'); // Get the clicked page
            fetchSavedProducts(page);
        });

        // Function to fetch saved products using AJAX
        function fetchSavedProducts(page) {
            var user_id = <?php echo isset($_SESSION['id']) ? $_SESSION['id'] : 0; ?>; // Safely pass session id

            // Check if the user is logged in
            if (user_id == 0) {
                window.location.href = 'login.php'; // Redirect to login if not logged in
                return;
            }

            $.ajax({
                url: 'Source/ajax/ajax_get_saved_products.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    user_id: user_id,
                    page: page
                },
                success: function(response) {
                    console.log("Fetch Response:", response); // Log the entire response
                    $('#saved-products').html(response.games); // Display the products
                    $('#pagination').html(response.pagination); // Display pagination links

                    // Set background images after data is loaded
                    $('.set-bg').each(function() {
                        var bg = $(this).data('setbg'); // Get the data-setbg attribute
                        console.log("Setting background image to:",
                            bg); // Log the URL for debugging
                        $(this).css('background-image', 'url(' + bg +
                            ')'); // Set the background image
                    });
                },
                error: function() {
                    alert('Failed to fetch saved products.');
                }
            });
        }

        // Unlist product functionality
        $(document).on('click', '.unlist-product', function() {
            var productId = $(this).data('id'); // Get product ID from data attribute
            var user_id = '<?php echo $_SESSION['id']; ?>';
            if (user_id != '') {
                $.ajax({
                    url: 'Source/ajax/unlist_product.php', // Backend script to handle unlisting the product
                    method: 'POST',
                    data: {
                        product_id: productId,
                        user_id: user_id
                    },
                    success: function(response) {
                        console.log("Unlist response:", response); // Log the response
                        if (response == 1) {
                            fetchSavedProducts(1);
                            updateSavedProductCount()
                        } else if (response == 0) {
                            alert('Product was not found in saved items.');
                        } else {
                            alert('Something went wrong, product not unlisted.');
                        }
                    },
                    error: function() {
                        alert('Failed to unlist the product. Please try again.');
                    }
                });
            } else {
                alert('Please login to unlist the product.');
                window.location.replace('login.php');
            }
        });
    });
    </script>

</body>

</html>