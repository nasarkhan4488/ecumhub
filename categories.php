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
    <?php include('Source/Nav/link.php'); ?>
</head>
<style>
    .set-bg {
    background-size: cover;
    background-position: center;
    height: 300px; /* Or any appropriate height */
    width: 100%;
}

</style>
<body>
    <?php include('Source/Nav/header.php'); ?>
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="./index.html"><i class="fa fa-home"></i> Home</a>
                        <a href="./categories.html">Categories</a>
                        <span style="text-transform: capitalize;"><?php echo $_GET['categories']?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->
    <!-- Product Section Begin -->
    <section class="product-page spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="product__page__content">
                        <div class="product__page__title">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-6">
                                    <div class="section-title">
                                        <h4 id="categoryTitle"><?php echo $_GET['categories'] ?></h4>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6">
                                    <div class="product__page__filter">
                                        <p>Order by:</p>
                                        <select id="orderBy">
                                            <option value="name ASC">A-Z</option>
                                            <option value="name DESC">Z-A</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="gameList">
                            <!-- Games will be loaded here via AJAX -->
                        </div>

                        <div class="product__pagination" id="paginationLinks">
                            <!-- Pagination will be loaded here -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-8">
                    <div class="product__sidebar">
                        <div class="product__sidebar__view">
                            <div class="section-title">
                                <h5>Top download</h5>
                            </div>
                            <div class="filter__gallery">
                                <?php 
                            $select_trending=mysqli_query($conn, "SELECT * FROM `game_anime_details` WHERE `status` = 'active' ORDER BY `downloads` DESC LIMIT 5");
                            while ($row = mysqli_fetch_assoc($select_trending)) {
                                $views = $row['views'];
                                $formattedViews = format_views($views);
                            ?>
                                <div class="product__sidebar__view__item set-bg mix day years"
                                    data-setbg="img/game/<?php echo $row['image']; ?>">
                                    <div class="ep">Rs <?php echo $row['price'] ?></div>

                                    <div class="view"><i class="fa fa-eye"></i> <?php echo $formattedViews ?></div>
                                    <h5><a
                                            href="anime-details.php?id=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a>
                                    </h5>
                                </div>
                                <?php }?>
                            </div>
                        </div>
                        <div class="product__sidebar__comment">
                            <div class="section-title">
                                <h5>New Comment</h5>
                            </div>
                            <?php 
                            $select_comment = mysqli_query($conn,"SELECT g.id, g.image, g.name, g.status, 
                                 g.views,  r.rating, r.created_at AS review_date
                            FROM game_anime_details AS g
                            INNER JOIN reviews AS r ON g.id = r.product_id
                            WHERE r.rating > 3
                            ORDER BY r.created_at DESC
                            LIMIT 4;
                            ");
                            while ($row = mysqli_fetch_assoc($select_comment)) {
                                $views = $row['views'];
                                $formattedViews = format_views($views);
                            ?>
                            <div class="product__sidebar__comment__item">
                                <div class="product__sidebar__comment__item__pic">
                                    <img style="width: 70px;" src="img/game/<?php echo $row['image']; ?>" alt="">
                                </div>
                                <div class="product__sidebar__comment__item__text">
                                    <ul>
                                        <li class="list-inline-item">
                                            <button class="btn btn-sm text-white save-product"
                                                data-id="<?php echo $row['id']; ?>">
                                                <i class="bi bi-bookmark-heart fs-6"></i> Save
                                            </button>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="anime-details.php?id=<?php echo $row['id']; ?>"
                                                class="btn btn-sm text-white"><i class="bi bi-cart3 fs-6"></i> Buy
                                                Now</a>
                                        </li>
                                    </ul>
                                    <h5><a
                                            href="anime-details.php?id=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a>
                                    </h5>
                                    <span><i class="fa fa-eye"></i> <?php echo $formattedViews ?> Viewes</span>
                                </div>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Section End -->
    <?php include 'Source/Nav/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {

        // Function to fetch and display games
        function fetchGames(page = 1, orderBy = 'name ASC') {
            var category = "<?php echo $_GET['categories']; ?>"; // Category from PHP
            $.ajax({
                url: "Source/ajax/fetch_games.php", // PHP file to fetch games
                method: "POST",
                dataType: "json", // Expect JSON response from PHP
                data: {
                    category: category,
                    page: page,
                    orderBy: orderBy
                },
                success: function(data) {
                    if (data && data.games && data.pagination) {
                        $("#gameList").html(data.games); // Display games
                        $("#paginationLinks").html(data.pagination); // Display pagination

                        // Set background images after data is loaded
                        $('.set-bg').each(function() {
                            var bg = $(this).data('setbg');
                            $(this).css('background-image', 'url(' + bg + ')');
                        });
                    } else {
                        alert("Error: Unable to load data.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: " + status + error);
                    alert("Failed to load data.");
                }
            });
        }

        // Initially load games
        fetchGames();

        // Handle pagination click
        $(document).on('click', '.pagination-link', function(e) {
            e.preventDefault();
            var page = $(this).data('page'); // Get the page number
            var orderBy = $('#orderBy').val(); // Get the selected order
            fetchGames(page, orderBy);
        });

        // Handle "Order by" change
        $('#orderBy').change(function() {
            var orderBy = $(this).val(); // Get the selected order
            fetchGames(1, orderBy); // Reload from page 1 with new order
        });
    });
    </script>
</body>

</html>