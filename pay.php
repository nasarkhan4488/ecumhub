<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); 
date_default_timezone_set('Asia/Karachi');


include('Source/Nav/config.php'); 
include('Source/Nav/payconfig.php'); 

include('Source/ajax/JazzcashApi.php'); 
if (isset($_POST['submit'])) {
    // Fetch product details
    $result = mysqli_query($conn, "SELECT * FROM game_anime_details WHERE id='$_POST[product_id]'");
    $row = mysqli_fetch_assoc($result);

    // Prepare data for API request
    $data['jazz_cash_no'] = $_POST['PHONE'];
    $data['cnic_digits'] = $_POST['CNIC'];
    $data['price'] = $row['price'];
    $data['paymentMethod'] = $_POST['paymentMethod'];
    $data['ccNo'] = $_POST['ccNo'];
    $data['expMonth'] = $_POST['expMonth'];
    $data['expYear'] = $_POST['expYear'];
    $data['cvv'] = $_POST['cvv'];

    // Instantiate JazzCash API
    $jc_api = new JazzcashApi();
    $response = $jc_api->createCharge($data);
 
 if($_POST['paymentMethod'] == 'jazzcashCard'){
    $response_code=$response['responseCode'];
    $response_message=$response['responseMessage'];
    $status=$response['status'];
    $pp_RetreivalReferenceNo=$response['pp_RetreivalReferenceNo'];
    $type="Card";
    if($response_code==100){
       // Prepare SQL for inserting data into `jazzcash_transactions` table
         // Here, game_id and user_id are assumed to be passed from your form or session
         $game_id = $_POST['product_id']; // Assuming you have a field for game ID
         $user_id = $_SESSION['user_id']; // Assuming you have a field for user ID
 
         // Insert data into the database
         $query = "INSERT INTO jazzcash_transactions (`type`, `amount`, `response_code`, `response_message`, `status`, game_id, user_id, created_at) 
                 VALUES ('$type', '$amount', '$response_code', '$response_message', '$status', '$game_id', '$user_id', NOW())";
         
         // Execute the query
         if (mysqli_query($conn, $query)) {
             echo"<script>alert('Payment Successful.')</script>";
             // Extract the necessary data from the response
  // Trigger file download for successful payment
  $game_file = $row['game_file'];  // Assuming the game file is stored in the database
  $file_path = "uploads/games/$game_file"; // Construct the file path

  if (file_exists($file_path)) {
      // Set headers for file download
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
       // Insert notification for JazzCash Card payment success
$notification_message = "Your payment for Game ID: $game_id using JazzCash Card was successful.";
$notification_query = "INSERT INTO notifications ( notification_type ,user_id, event_type, entity_type, entity_id, message, status, created_at) 
                       VALUES ('admin','$user_id', 'Payment Success', 'Game', '$game_id', '$notification_message', 'unread', NOW())";

mysqli_query($conn, $notification_query);

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
      exit; // Terminate script to prevent further output
  } else {
      echo "File not found.";


  }
             echo"<script>window.location.href='index.php'</script>";
         } else {
             echo "Error saving transaction data: " . mysqli_error($conn);
         }
    }
    else{
        echo"<script>alert('".$response_message."')</script>";
              // Insert notification for JazzCash Card payment success
$notification_message = "Error : Your payment for Game ID: $game_id using JazzCash Card was Not successful.";
$notification_query = "INSERT INTO notifications ( `notification_type` `user_id`, `event_type`, `entity_type`, `entity_id`, `message`, `status`, crea`ted_at) 
                       VALUES ('admin', '$user_id', 'Payment Error', 'Game', '$game_id', '$notification_message', 'unread', NOW())";

mysqli_query($conn, $notification_query);
    }
  }else if($paymentMethod == 'jazzcashMobile'){
     $type = "Mobile account";
     $amount = $row['price'];
     $response_code = $response['pp_ResponseCode'];
     $response_message = $response['pp_ResponseMessage'];
     $txn_currency = $response['pp_TxnCurrency'];
     $txn_date_time = $response['pp_TxnDateTime'];
     $mobile_number = $response['pp_MobileNumber'];
     $cnic = $response['pp_CNIC'];
     $secure_hash = $response['pp_SecureHash'];
     $game_id = $_POST['product_id']; // Assuming you have a field for game ID
     $user_id = $_SESSION['user_id']; // Assuming you have a field for user ID
     if($response_code == 100){
     // Insert data into the database
  $query = "INSERT INTO jazzcash_transactions (`type`, `amount`, `response_code`, `response_message`, `txn_currency`, `txn_date_time`, `mobile_number`, `cnic`,  game_id, user_id, created_at) 
  VALUES ('$type', '$amount', '$response_code', '$response_message', '$txn_currency', '$txn_date_time', '$mobile_number', '$cnic',  '$game_id', '$user_id', NOW())";

if (mysqli_query($conn, $query)) {
echo"<script>alert('Payment Successful.')</script>";
// Extract the necessary data from the response
  // Trigger file download for successful payment
  $game_file = $row['game_file'];  // Assuming the game file is stored in the database
  $file_path = "uploads/games/$game_file"; // Construct the file path

  if (file_exists($file_path)) {
      // Set headers for file download
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
       // Insert notification for JazzCash Mobile payment success
$notification_message = "Your payment for Game ID: $game_id using JazzCash Mobile was successful.";
$notification_query = "INSERT INTO notifications ( notification_type ,user_id, event_type, entity_type, entity_id, message, status, created_at) 
                       VALUES ('admin','$user_id', 'Payment Success', 'Game', '$game_id', '$notification_message', 'unread', NOW())";

mysqli_query($conn, $notification_query);

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
      exit; // Terminate script to prevent further output
  } else {
      echo "File not found.";
  }
echo"<script>window.location.href='index.php'</script>";
} else {
echo "Error saving transaction data: " . mysqli_error($conn);
      // Insert notification for JazzCash Mobile payment success
      $notification_message = "Error: Your payment for Game ID: $game_id using JazzCash Mobile was successful.";
      $notification_query = "INSERT INTO notifications (`notification_type`,`user_id`, `event_type`, `entity_type`, `entity_id`, `message`, `status`, `created_at`) 
                             VALUES ('admin','$user_id', 'Payment Error', 'Game', '$game_id', '$notification_message', 'unread', NOW())";
      
      mysqli_query($conn, $notification_query);
} 
    }
    else{
        echo"<script>alert('".$response_message."')</script>";
    }
     
  }
 

}
if (isset($_POST['cod_submit'])) {
    // Get the data from the form
    $user_id = $_SESSION['id'];  // Assuming the user is logged in and their ID is stored in the session
    $game_id = $_POST['product_id'];  // The product ID passed from the form
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $product_price = $_POST['product_price'];  // Price of the game/product
    $delivery_charges = $_POST['delivery_charges'];  // Delivery charges entered by the user
    $total_amount = $product_price + $delivery_charges;  // Calculate total amount

    // Insert the data into the `cod_orders` table
    $query = "INSERT INTO `cod_orders` (`user_id`, `game_id`, `fullname`, `phone`, `email`, `address`, `product_price`, `delivery_charges`, `total_amount`, `status`, `created_at`) 
              VALUES ('$user_id', '$game_id', '$fullname', '$phone', '$email', '$address', '$product_price', '$delivery_charges', '$total_amount', 'Pending', NOW())";

    // Check if the query was successful
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Your Cash on Delivery order has been placed successfully!');</script>";
        $user_id = $_SESSION['id'];
        // Insert notification for Cash on Delivery order placement
$notification_message = "Your Cash on Delivery order for Game ID: $game_id has been placed successfully.";
$notification_query = "INSERT INTO notifications ( notification_type ,user_id, event_type, entity_type, entity_id, message, status, created_at) 
                       VALUES ('admin','$user_id', 'Order Placed', 'Game', '$game_id', '$notification_message', 'unread', NOW())";

mysqli_query($conn, $notification_query);

        $check_cart_query = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$game_id'";
        $cart_result = mysqli_query($conn, $check_cart_query);

        // Proceed only if the game exists in the cart
        if (mysqli_num_rows($cart_result) > 0) {
            $update_cart_query = "UPDATE cart SET purchased = 1 WHERE user_id = '$user_id' AND product_id = '$game_id'";
            // Execute the update query
            mysqli_query($conn, $update_cart_query);
            echo "<script>updateSavedProductCount();</script>";
        }
        echo "<script>window.location.href='index.php';</script>";  // Redirect to the homepage or another page
    } else {
        // If the query fails, display an error message
        echo "Error: " . mysqli_error($conn);
    }
}

$select_game=mysqli_query($conn,"SELECT * FROM game_anime_details WHERE id =".$_GET['id']."");
$row_game=mysqli_fetch_assoc($select_game);
$amount=$row_game["price"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pay Now</title>
    <?php include 'Source/Nav/link.php'; ?>
</head>

<body>

    <!-- Header Section -->
    <?php include('Source/Nav/header.php'); ?>
    <!-- Header End -->

    <!-- Breadcrumb Section Begin -->
    <section class="normal-breadcrumb set-bg" data-setbg="img/normal-breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="normal__breadcrumb__text">
                        <h2>Pay Now</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb End -->

    <!-- Login Section Begin -->
    <section class="login spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="login__form">
                        <h3>Mobile Account</h3>
                        <!-- ----------------------------------------------------------------------------------------- -->
                        <!-- JAZZCASH payment form -->
                        <!-- ----------------------------------------------------------------------------------------- -->
                        <form action="<?php  echo BASE_URL.'pay.php?id='.$_GET['id'];?>" method="POST" id="myCCForm">

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="paymentMethod" class="small text-muted mb-1"><b>Pay With
                                                Mobile</b></label>
                                        <input type="radio" name="paymentMethod" value="jazzcashMobile" checked=""
                                            required="" onchange="getValue(this)">
                                        <i class="fa fa-mobile" style="color:orange;"></i>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="paymentMethod" class="small text-muted mb-1"><b>Pay With
                                                Card</b></label>
                                        <input type="radio" name="paymentMethod" value="jazzcashCard" checked=""
                                            required="" onchange="getValue(this)">
                                        <i class="fab fa-cc-visa" style="color:navy;"></i>
                                        <i class="fab fa-cc-mastercard" style="color:red;"></i>
                                    </div>
                                </div>
                            </div>
                            <hr>

                            <!-- NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN -->
                            <div id="mobile_fields">

                                <div class="form-group">
                                    <label for="PHONE" class="small text-muted mb-1">PHONE NUMBER</label>
                                    <input type="text" name="PHONE" value="03123456789"
                                        class="form-control form-control-sm">
                                </div>

                                <div class="form-group">
                                    <label for="CNIC" class="small text-muted mb-1">LAST 6 DIGITS OF CNIC</label>
                                    <input type="text" name="CNIC" value="345678" class="form-control form-control-sm">
                                </div>

                            </div>
                            <!-- NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN -->

                            <!-- NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN -->
                            <div id="card_fields">

                                <div class="form-group">
                                    <label for="ccNo" class="small text-muted mb-1">CARD NUMBER</label>
                                    <input type="text" name="ccNo" value="5123456789012346"
                                        class="form-control form-control-sm">
                                </div>

                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="expMonth" class="small text-muted mb-1">EXP MONTH</label>
                                            <input type="text" name="expMonth" value="08"
                                                class="form-control form-control-sm">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="expYear" class="small text-muted mb-1">EXP YEAR</label>
                                            <input type="text" name="expYear" value="21"
                                                class="form-control form-control-sm">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="cvv" class="small text-muted mb-1">CVV</label>
                                            <input type="text" name="cvv" value="123"
                                                class="form-control form-control-sm">
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <!-- NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN -->

                            <input type="hidden" name="product_id" value="<?php echo $_GET['id'];?>">

                            <div class="row mb-md-5">
                                <div class="col">
                                    <button type="submit" name="submit" id=""
                                        class="btn btn-lg btn-block btn-primary">PURCHASE <?php echo $amount;?>
                                        PKR</button>
                                </div>
                            </div>
                        </form>
                        <!-- ----------------------------------------------------------------------------------------- -->
                        <!-- ./JAZZCASH payment form -->
                        <!-- ----------------------------------------------------------------------------------------- -->


                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="login__register">
                        <h3>Cash On Delivery</h3>
                        <form action="" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $_GET['id'];?>">
                            <!-- The product ID -->

                            <!-- User Info -->
                            <div class="form-group">
                                <label class="small text-muted mb-1" for="fullname">Full Name</label>
                                <input type="text" name="fullname" placeholder="Full Name" class="form-control"
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="small text-muted mb-1" for="phone">Phone Number</label>
                                <input type="text" placeholder="Phone Number" name="phone" class="form-control"
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="small text-muted mb-1" for="email">Email</label>
                                <input type="email" placeholder="email@email.com" name="email" class="form-control"
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="small text-muted mb-1" for="address">Address</label>
                                <textarea name="address" placeholder="Your Address...." class="form-control"
                                    required></textarea>
                            </div>

                            <!-- Order Details -->
                            <div class="form-group">
                                <label class="small text-muted mb-1" for="product_price">Product Price (PKR)</label>
                                <input type="number" name="product_price" class="form-control"
                                    value="<?php echo $amount;?>" required>
                            </div>
                            <div class="form-group">
                                <label class="small text-muted mb-1" for="delivery_charges">Delivery Charges
                                    (PKR)</label>
                                <input type="number" name="delivery_charges" class="form-control" value="200" required>
                            </div>

                            <button type="submit" name="cod_submit" class="btn btn-primary">Place Order</button>
                        </form>

                    </div>
                </div>

                <script>
                // This script calculates the total amount (product price + delivery charges) when the delivery charges are entered
                document.getElementById('delivery_charges').addEventListener('input', function() {
                    var deliveryCharges = parseFloat(this.value) || 0; // Get delivery charges
                    var productPrice = parseFloat('<?php echo $amount; ?>'); // Product price from PHP
                    var totalAmount = productPrice + deliveryCharges; // Calculate total

                    // Update total amount input field
                    document.getElementById('cod_total_amount').value = totalAmount.toFixed(2);
                });
                </script>


            </div>
        </div>
    </section>
    <!-- Login Section End -->

    <!-- Token Verification Pop-Up -->


    <?php include('Source/Nav/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    document.getElementById("mobile_fields").style.display = 'none';

    function getValue(x) {
        if (x.value == 'jazzcashMobile') {
            document.getElementById("mobile_fields").style.display = 'block';
            document.getElementById("card_fields").style.display = 'none';
        } else if (x.value == 'jazzcashCard') {
            document.getElementById("mobile_fields").style.display = 'none';
            document.getElementById("card_fields").style.display = 'block';
        }
    }
    </script>

</body>

</html>