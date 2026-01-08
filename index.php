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
    <title>Anime | Template</title>
    <?php include ('Source/Nav/link.php')?>
</head>

<body>

    <?php include('Source/Nav/header.php')?>

    <!-- Hero Section Begin -->
    <section class="hero">
        <div class="container">
            <div class="hero__slider owl-carousel">
                <?php 
                $query = "SELECT s.game_id, s.image, s.status, g.categories, g.name, g.title FROM `sliders` AS s JOIN `game_anime_details` AS g ON s.game_id = g.id";
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="hero__items set-bg" data-setbg="img/hero/<?php echo $row['image']; ?>">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="hero__text">
                                <div class="label"><?php echo $row['categories']; ?></div>
                                <h2><?php echo $row['name']; ?></h2>
                                <p><?php echo $row['title']; ?></p>
                                <a href="anime-details.php?id=<?php echo $row['game_id']; ?>"><span>Explore More</span>
                                    <i class="fa fa-angle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->
    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="trending__product">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="section-title">
                                    <h4>Trending Now</h4>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="btn__all">
                                    <a href="categories.php?categories=trending" class="primary-btn">View All <span
                                            class="arrow_right"></span></a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php 
                            $select_trending=mysqli_query($conn, "SELECT * FROM `game_anime_details` WHERE `status` = 'active' ORDER BY `downloads` DESC LIMIT 9");
                            while ($row = mysqli_fetch_assoc($select_trending)) {
                                $views = $row['views'];
                                $formattedViews = format_views($views);
                            ?>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="product__item">
                                    <div class="product__item__pic set-bg"
                                        data-setbg="img/game/<?php echo $row['image']; ?>">
                                        <div class="ep">Rs <?php echo $row['price'] ?></div>
                                        <?php
                                        $game_id=$row['id'];
                                        $query = "SELECT COUNT(*) AS comment_count FROM reviews WHERE `product_id` = '$game_id'";
                                        $result = mysqli_query($conn, $query);
                                        $data = mysqli_fetch_assoc($result);
                                        $comments=$data['comment_count'];
                                        $comendts=format_views($comments);
                                        ?>
                                        <div class="comment"><i class="fa fa-comments"></i> <?php echo $comendts?></div>
                                        <div class="view"><i class="fa fa-eye"></i> <?php echo $formattedViews ?></div>
                                    </div>
                                    <div class="product__item__text">
                                        <ul class="list-inline">
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
                                    </div>
                                </div>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                    <div class="popular__product">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="section-title">
                                    <h4>Popular Game</h4>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="btn__all">
                                    <a href="categories.php?categories=popular" class="primary-btn">View All <span
                                            class="arrow_right"></span></a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php 
                            $select_trending=mysqli_query($conn, "SELECT * FROM `game_anime_details` WHERE `status` = 'active' AND `Popular_status`='active' ORDER BY `views` DESC LIMIT 9");
                            while ($row = mysqli_fetch_assoc($select_trending)) {
                                $views = $row['views'];
                                $formattedViews = format_views($views);
                            ?>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="product__item">
                                    <div class="product__item__pic set-bg"
                                        data-setbg="img/game/<?php echo $row['image']; ?>">
                                        <div class="ep">Rs <?php echo $row['price'] ?></div>
                                        <?php
                                        $game_id=$row['id'];
                                        $query = "SELECT COUNT(*) AS comment_count FROM reviews WHERE `product_id` = '$game_id'";
                                        $result = mysqli_query($conn, $query);
                                        $data = mysqli_fetch_assoc($result);
                                        $comments=$data['comment_count'];
                                        $comendts=format_views($comments);
                                        ?>
                                        <div class="comment"><i class="fa fa-comments"></i> <?php echo $comendts?></div>
                                        <div class="view"><i class="fa fa-eye"></i> <?php echo $formattedViews ?></div>
                                    </div>
                                    <div class="product__item__text">
                                        <ul class="list-inline">
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
                                    </div>
                                </div>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                    <div class=" recent__product">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="section-title">
                                    <h4>Recently Added Games</h4>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="btn__all">
                                    <a href="categories.php?categories=recent" class="primary-btn">View All <span
                                            class="arrow_right"></span></a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php 
                            $select_trending=mysqli_query($conn, "SELECT * FROM `game_anime_details` WHERE `status` = 'active' AND created_at >= DATE_SUB(NOW(), INTERVAL 4 MONTH)  LIMIT 9");
                            while ($row = mysqli_fetch_assoc($select_trending)) {
                                $views = $row['views'];
                                $formattedViews = format_views($views);
                            ?>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="product__item">
                                    <div class="product__item__pic set-bg"
                                        data-setbg="img/game/<?php echo $row['image']; ?>">
                                        <div class="ep">Rs <?php echo $row['price'] ?></div>
                                        <?php
                                        $game_id=$row['id'];
                                        $query = "SELECT COUNT(*) AS comment_count FROM reviews WHERE `product_id` = '$game_id'";
                                        $result = mysqli_query($conn, $query);
                                        $data = mysqli_fetch_assoc($result);
                                        $comments=$data['comment_count'];
                                        $comendts=format_views($comments);
                                        ?>
                                        <div class="comment"><i class="fa fa-comments"></i>
                                            <?php echo $comendts?></div>
                                        <div class="view"><i class="fa fa-eye"></i>
                                            <?php echo $formattedViews ?></div>
                                    </div>
                                    <div class="product__item__text">
                                        <ul class="list-inline">
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
                                    </div>
                                </div>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                    <?php
                        $select_category = mysqli_query($conn, "SELECT DISTINCT categories FROM game_anime_details WHERE `status` = 'active' LIMIT 3");
                        while ($row = mysqli_fetch_assoc($select_category)) {
                    ?>
                    <div class="live__product">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="section-title">
                                    <h4><?php echo $row['categories'];?></h4>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="btn__all">
                                    <a href="categories.php?categories=<?php echo $row['categories'];?>"
                                        class="primary-btn">View All <span class="arrow_right"></span></a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php 
                            $select_trending=mysqli_query($conn, "SELECT * FROM `game_anime_details` WHERE `status` = 'active' AND categories = '$row[categories]' ORDER BY `downloads` DESC LIMIT 9");
                            while ($row = mysqli_fetch_assoc($select_trending)) {
                                $views = $row['views'];
                                $formattedViews = format_views($views);
                            ?>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="product__item">
                                    <div class="product__item__pic set-bg"
                                        data-setbg="img/game/<?php echo $row['image']; ?>">
                                        <div class="ep">Rs <?php echo $row['price'] ?></div>
                                        <?php
                                        $game_id=$row['id'];
                                        $query = "SELECT COUNT(*) AS comment_count FROM reviews WHERE `product_id` = '$game_id'";
                                        $result = mysqli_query($conn, $query);
                                        $data = mysqli_fetch_assoc($result);
                                        $comments=$data['comment_count'];
                                        $comendts=format_views($comments);
                                        ?>
                                        <div class="comment"><i class="fa fa-comments"></i>
                                            <?php echo $comendts?></div>
                                        <div class="view"><i class="fa fa-eye"></i>
                                            <?php echo $formattedViews ?></div>
                                    </div>
                                    <div class="product__item__text">
                                        <ul class="list-inline">
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
                                    </div>
                                </div>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                    <?php }?>
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

                                    <div class="view"><i class="fa fa-eye"></i>
                                        <?php echo $formattedViews ?></div>
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
                                    <span><i class="fa fa-eye"></i> <?php echo $formattedViews ?>
                                        Viewes</span>
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

    <?php
include('Source/Nav/footer.php');
?>

</body>

</html>