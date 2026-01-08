<?php
// Start session and include your config
session_start();
include('Source/Nav/config.php');

// Check if the game_id is passed in the URL
if (isset($_GET['game_id'])) {
    $game_id = $_GET['game_id'];
    
    // Fetch the game details
    $query = "SELECT `game_file` FROM `game_anime_details` WHERE `id` = '$game_id' AND `price`=0.00";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $game_file = $row['game_file'];  // Get the game file from the database
        $file_path = "uploads/games/$game_file";  // Construct the file path

        if (file_exists($file_path)) {
            // Set headers to force download
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));

            
            // Read the file
            readfile($file_path);
            
            // Increment the download count
            $update_downloads_query = "UPDATE `game_anime_details` SET `downloads` = `downloads` + 1 WHERE `id` = '$game_id'";
            mysqli_query($conn, $update_downloads_query);
           
            $user_id = $_SESSION['id'];
            $check_cart_query = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$game_id'";
            $cart_result = mysqli_query($conn, $check_cart_query);

            // Proceed only if the game exists in the cart
            if (mysqli_num_rows($cart_result) > 0) {
                $update_cart_query = "UPDATE cart SET purchased = 1 WHERE user_id = '$user_id' AND product_id = '$game_id'";
                // Execute the update query
                mysqli_query($conn, $update_cart_query);
                echo "<script>updateSavedProductCount();</script>";

            }
            exit; // Stop script after file download
        } else {
            echo "File not found.";
        }
    } else {
        echo "Game not found.";
    }
} else {
    echo "No game specified.";
}

// Redirect to homepage if file not found or on error
echo "<script>window.location.href='index.php'</script>";
?>