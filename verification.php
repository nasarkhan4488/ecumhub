<?php
include 'Source/Nav/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $token = $_POST['token'];

    // Check the token
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND token = ? AND token_expiration > NOW()");
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token is valid
        echo "Email verified successfully!";
        // Optionally update the user's status
    } else {
        echo "Invalid or expired token.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>Anime | Template</title>
    <?php include 'Source/Nav/link.php'; ?>
</head>

<body>
    <!-- Page Preloader -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Header Section Begin -->
    <?php include('Source/Nav/header.php'); ?>

    <!-- Normal Breadcrumb Begin -->
    <section class="normal-breadcrumb set-bg" data-setbg="img/normal-breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="normal__breadcrumb__text">
                        <h2>Sign Up</h2>
                        <p>Welcome to the official Anime blog.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Normal Breadcrumb End -->

    <!-- Signup Section Begin -->
<section class="signup spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="login__form">
                    <h3>Sign Up</h3>
                    <h3>Email Verification</h3>
    <form action="" method="POST">
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">
        <span class="icon_mail"></span>
        <input type="text" name="token" placeholder="Enter your verification code" required>
        <span class="icon_lock"></span>
        <button type="submit">Verify</button>
    </form>
    <button onclick="resendToken()">Resend Token</button>
                    
                   
                </div>
            </div>
            
        </div>
    </div>
</section>
<!-- Signup Section End -->


    <!-- Footer Section Begin -->
    <?php include('Source/Nav/footer.php'); ?>

    <!-- Js Plugins -->
     
    <script>
        function resendToken() {
            const email = "<?php echo htmlspecialchars($_GET['email']); ?>";
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "resend_token.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert(xhr.responseText);
                }
            };
            xhr.send("email=" + encodeURIComponent(email));
        }
    </script>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/player.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/mixitup.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>
