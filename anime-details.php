<?php
session_start();
error_reporting(0);
include('Source/Nav/config.php');
if (!isset($_SESSION['id']) || !isset($_SESSION['email']) ) {
    header("Location: login.php"); // Change this to your login page URL
    
    }
    $gameId = $_GET['id'];
$select = "SELECT * FROM game_anime_details WHERE id = '$_GET[id]'";
$result = mysqli_query($conn, $select);
$row = mysqli_fetch_assoc(result: $result);
$gameId = $row['id'];
$categories = $row['categories'];
$image = $row['image'];
$name = $row['name'];
$title = $row['title'];
$description = $row['description'];
$type = $row['type'];
$studios = $row['studios'];
$dateAired = $row['date_aired'];
$game_status = $row['game_status'];
$genre = $row['genre'];
$size = $row['size'];
$quality = $row['quality'];
$price = $row['price'];
$views = $row['views'];

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
// Format the view count
$formattedViews = format_views($views);
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <?php include('Source/Nav/link.php'); ?>
</head>
<style>
/* Custom Modal Styles */
.btn-data {
    font-size: 11px;
    color: #ffffff;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    background: #e53637;
    border: none;
    padding: 10px 15px;
    border-radius: 2px;
}

#commentModal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4);
}

#commentModal .modal-content {
    background-color: #fff;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 500px;
}

.close-modal {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close-modal:hover,
.close-modal:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

.star-rating i {
    font-size: 2rem;
    color: gray;
    cursor: pointer;
    margin-right: 5px;
}

.star-rating i.hovered,
.star-rating i.selected {
    color: yellow;
}
</style>

<body>
    <?php include('Source/Nav/header.php');?>

    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="./index.php"><i class="fa fa-home"></i> Home</a>
                        <a href="./categories.php">Categories</a>
                        <span><?php echo $categories?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Anime Section Begin -->
    <section class="anime-details spad">
        <div class="container">
            <div class="anime__details__content">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="anime__details__pic set-bg" data-setbg="img/game/<?php echo $image;?>">
                            <div class="comment" id="comment-count">
                                <i class="fa fa-comments"></i> <span id="num-comments">0</span>
                            </div>
                            <div class="view"><i class="fa fa-eye"></i> <?php echo $formattedViews ?></div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="anime__details__text">
                            <div class="anime__details__title">
                                <h3><?php echo $name; ?></h3>
                                <span><?php echo $title; ?></span>
                            </div>
                            <div class="anime__details__rating">
                                <div id="rating-stars" class="rating">
                                    <!-- Dynamic stars will be loaded here via AJAX -->
                                </div>
                                <span id="total-votes">0 Votes</span>
                            </div>
                            <p><?php echo $description; ?></p>
                            <div class="anime__details__widget">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <ul>
                                            <li><span>Type:</span><?php echo $type;?></li>
                                            <li><span>Studios:</span> <?php echo $studios; ?></li>
                                            <li><span>Date aired:</span> <?php echo $dateAired; ?></li>
                                            <li><span>Status:</span> <?php echo $game_status; ?></li>
                                            <li><span>Genre:</span> <?php echo $genre; ?></li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <ul>
                                            <li><span>Size:</span> <?php echo $size; ?></li>
                                            <li><span>Rating:</span><span id="rating-stars"></span>/ <span
                                                    id="total-votes">0 Votes</span></li>
                                            <li><span>Price:</span> <?php echo $price; ?></li>
                                            <li><span>Quality:</span> <?php echo $quality; ?></li>
                                            <li><span>Views:</span> <?php echo $formattedViews; ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="anime__details__btn">
                                <button class="btn btn-sm text-white save-product" data-id="<?php echo $gameId; ?>">
                                    <i class="bi bi-bookmark-heart fs-6"></i> Save
                                </button>
                                <?php 
                                if($price == 0.00){
                                    echo '<a href="download_free_game.php?game_id='.$_GET['id'].'" class="watch-btn">
                                            <span>Play Free</span> <i class="fa fa-angle-right"></i>
                                          </a>';
                                }else{
                                    echo'<a href="pay.php?id='.$_GET["id"].'" class="watch-btn"><span>Buy Now</span> <i
                                        class="fa fa-angle-right"></i></a>';
                                }
                                
                                ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-md-8">
                    <div class="anime__details__form">
                        <div class="section-title">
                            <h5>Your Comment</h5>
                        </div>
                        <button id="openModal" class="btn btn-data"><i class="fa fa-location-arrow"></i>
                            Comment</button>
                        <div id="commentModal">
                            <div class="modal-content">
                                <span class="close-modal">&times;</span>
                                <h5>Your Review</h5>
                                <div class="star-rating mb-3">
                                    <i class="fa fa-star star" data-rating="1"></i>
                                    <i class="fa fa-star star" data-rating="2"></i>
                                    <i class="fa fa-star star" data-rating="3"></i>
                                    <i class="fa fa-star star" data-rating="4"></i>
                                    <i class="fa fa-star star" data-rating="5"></i>
                                </div>
                                <p id="ratingLabel">Hover over the stars</p>
                                <textarea id="commentText" class="form-control" rows="4"
                                    placeholder="Your Comment"></textarea>
                                <button type="button" id="submitComment" class="btn btn-primary mt-3">Submit
                                    Review</button>
                            </div>
                        </div>
                    </div>
                    <div class="anime__details__review mt-5">
                        <div class="section-title">
                            <h5>Reviews</h5>
                        </div>
                        <div id="reviews-container">
                            <!-- Reviews will be loaded here by AJAX -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="anime__details__sidebar">
                        <div class="section-title">
                            <h5>you might like...</h5>
                        </div>
                        <?php 
                            $select=mysqli_query($conn,"SELECT * FROM `game_anime_details` WHERE `categories` = '$categories' LIMIT 4");
                            while($row = mysqli_fetch_array($select)){
                                $views = $row['views'];
                                $formattedViews = format_views($views);
                            ?>
                        <div class="product__sidebar__view__item set-bg"
                            data-setbg="img/game/<?php echo $row['image']; ?>">
                            <div class="view"><i class="fa fa-eye"></i><?php echo $formattedViews; ?></div>
                            <h5><a href="anime-details.php?id=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a>
                            </h5>
                        </div>
                        <?php
                            }
                            ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Anime Section End -->
    <?php include('Source/Nav/footer.php'); ?>
    <script>
    $(document).ready(function() {
        var gameId = <?php echo$_GET['id'] ?>;
        var productId = <?php echo $_GET['id']?>;
        const user_id = <?php echo $_SESSION['id'] ?>;

        if (!gameId || !user_id) {
            console.error('Missing gameId or user_id.');
            return; // Exit if essential variables are missing
        }

        // Fetch comment count on page load
        $.ajax({
            url: 'Source/ajax/fetch_comments.php',
            type: 'POST',
            data: {
                game_id: gameId
            },
            success: function(response) {
                try {
                    var data = JSON.parse(response);
                    $('#num-comments').text(data.comment_count);
                } catch (e) {
                    console.error('Failed to parse comment count response:', e);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error fetching comments:', textStatus, errorThrown);
                $('#num-comments').text('0');
            }
        });

        // Fetch game rating on page load
        $.ajax({
            url: 'Source/ajax/fetch_rating.php',
            type: 'POST',
            data: {
                game_id: gameId
            },
            success: function(response) {
                try {
                    var data = JSON.parse(response);
                    $('#rating-stars').html(generateStars(data.average_rating));
                    $('#total-votes').text(data.total_votes + ' Votes');
                } catch (e) {
                    console.error('Failed to parse rating response:', e);
                    $('#rating-stars').html('<p>Unable to load rating at this time.</p>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error fetching rating:', textStatus, errorThrown);
                $('#rating-stars').html('<p>Unable to load rating at this time.</p>');
            }
        });

        // Fetch and display reviews on page load
        $.ajax({
            url: 'Source/ajax/fetch_reviews.php',
            type: 'POST',
            data: {
                product_id: productId
            },
            success: function(response) {
                $('#reviews-container').html(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error fetching reviews:', textStatus, errorThrown);
                $('#reviews-container').html('<p>Unable to load reviews at this time.</p>');
            }
        });

        // Modal functionality
        const modal = document.getElementById('commentModal');
        const openModalBtn = document.getElementById('openModal');
        const closeModalBtn = document.querySelector('.close-modal');
        let selectedRating = 0;

        if (openModalBtn) {
            openModalBtn.onclick = function() {
                modal.style.display = 'block';
            }
        }

        if (closeModalBtn) {
            closeModalBtn.onclick = function() {
                modal.style.display = 'none';
            }
        }

        window.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };

        // Star hover and selection effects
        const stars = document.querySelectorAll('.star');
        const labels = {
            1: '🤮 worst',
            2: '🤢 bad',
            3: '🤨 average',
            4: '😊 good',
            5: '🥰 excellent'
        };

        stars.forEach(star => {
            star.addEventListener('mouseover', function() {
                const rating = this.getAttribute('data-rating');
                updateStars(rating);
                document.getElementById('ratingLabel').innerText = labels[rating];
            });

            star.addEventListener('click', function() {
                selectedRating = this.getAttribute('data-rating');
                updateStars(selectedRating, true);
            });
        });

        function updateStars(rating, isSelected = false) {
            stars.forEach(star => {
                if (star.getAttribute('data-rating') <= rating) {
                    star.classList.add(isSelected ? 'selected' : 'hovered');
                } else {
                    star.classList.remove('hovered', 'selected');
                }
            });
        }

        // Submit comment using AJAX
        document.getElementById('submitComment').onclick = function() {
            const comment = document.getElementById('commentText').value;

            if (selectedRating && comment) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "Source/ajax/save_comment.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                const data =
                    `user_id=${user_id}&product_id=${productId}&rating=${selectedRating}&comment=${encodeURIComponent(comment)}`;
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        modal.style.display = 'none';
                        location.reload(); // Reload the page to refresh comments
                    }
                };
                xhr.send(data);
            } else {
                alert('Please provide a rating and comment!');
            }
        };

        // Increment views after delay
        setTimeout(function() {
            $.ajax({
                url: 'Source/ajax/increment_views.php',
                type: 'POST',
                data: {
                    game_id: gameId
                },
                success: function() {
                    console.log("Views updated successfully!");
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error incrementing views:', textStatus, errorThrown);
                }
            });
        }, 2000); // 2-second delay to simulate user interaction
    });

    // Helper function to generate stars based on rating
    function generateStars(averageRating) {
        var starsHtml = '';
        var fullStars = Math.floor(averageRating);
        var halfStar = averageRating % 1 >= 0.5 ? 1 : 0;
        var emptyStars = 5 - fullStars - halfStar;

        for (var i = 0; i < fullStars; i++) {
            starsHtml += '<a href="#"><i class="fa fa-star"></i></a>';
        }
        if (halfStar) {
            starsHtml += '<a href="#"><i class="fa fa-star-half-o"></i></a>';
        }
        for (var i = 0; i < emptyStars; i++) {
            starsHtml += '<a href="#"><i class="fa fa-star-o"></i></a>';
        }
        return starsHtml;
    }
    </script>

</body>

</html>