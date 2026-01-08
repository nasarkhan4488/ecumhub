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

// Function to get the order tracking status
function get_order_status($status) {
    $statuses = ['Processing', 'Packed', 'Shipped', 'Delivered'];
    return array_search($status, $statuses);
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

.track-order {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

#order-status-bar {
    display: flex;
    list-style-type: none;
    width: 100%;
    justify-content: space-between;
    padding: 0;
}

.status-item {
    tentisplay: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}

.status-item .status-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: red;
    /* Default */
}

.status-item.active .status-icon {
    background-color: green;
    /* Active status */
}

.status-item p {
    margin-top: 10px;
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
                        <span><i class="bi bi-bag-check-fill"></i>Recent Orders</span>
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
                                    <h4>Recent Orders</h4>
                                </div>
                            </div>
                        </div>

                        <!-- ordered Products will be dynamically loaded here -->
                        <div class="row" id="ordered-products">
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
        // Fetch ordered products for page 1 by default
        fetchorderedProducts(1);

        // Handle pagination link click
        $(document).on('click', '.pagination-link', function(e) {
            e.preventDefault();
            var page = $(this).data('page'); // Get the clicked page
            fetchorderedProducts(page);
        });

        // Function to fetch ordered products using AJAX
        function fetchorderedProducts(page) {
            var user_id = <?php echo $_SESSION['id'] ?>;
            $.ajax({
                url: 'Source/ajax/ajax_get_ordered_products.php',
                type: 'POST',
                data: {
                    user_id: user_id,
                    page: page
                },
                dataType: 'json', // Expect JSON response from PHP
                success: function(response) {
                    $('#ordered-products').html(response.games); // Display the products
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
                    alert('Failed to fetch ordered products.');
                }
            });
        }

        // Unlist product functionality
        $(document).on('click', '.unlist-product', function() {
            var productId = $(this).data('id'); // Get product ID from data attribute
            var user_id = '<?php echo $_SESSION['id']; ?>';
            $.ajax({
                url: 'Source/ajax/cancel_product.php', // Backend script to handle unlisting the product
                method: 'POST',
                data: {
                    product_id: productId,
                    user_id: user_id
                },
                success: function(response) {
                    alert(response);
                    console.log("Unlist response:", response); // Log the response
                    if (response == 1) {
                        fetchorderedProducts(1);
                        fetchOrderCount()
                    } else if (response == 0) {
                        alert('Product was not found in ordered items.');
                    } else {
                        alert('Something went wrong, product not unlisted.');
                    }
                },
                error: function() {
                    alert('Failed to Cancel the product. Please try again.');
                }
            });

        });

    });
    </script>

</body>

</html>