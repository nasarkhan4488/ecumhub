<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('Source/Nav/config.php');

$order_id = $_GET['id'] ?? null;

if (!$order_id) {
    die("Order ID is required");
}

// Fetch the order details from the database
$stmt = $conn->prepare("SELECT status FROM cod_orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$status = $order['status'] ?? null;

if (!$status) {
    die("Order not found");
}

// Function to determine the position and colors based on status
function getStatusSteps($status) {
    $steps = [
        'Processing' => 'Pending',
        'Shipped' => 'Shipped',
        'Delivered' => 'Delivered',
        'Received' => 'Received',
        'Cancelled' => 'Cancelled',
    ];

    $active_step = array_search($status, $steps) ?: 'Processing'; // Default to 'Processing' if status is unknown
    return $active_step;
}

$active_step = getStatusSteps($status);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Track Order</title>
    <?php include('Source/Nav/link.php'); ?>
    <style>
    .order-tracker {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
    }

    .order-step {
        text-align: center;
        flex: 1;
        position: relative;
    }

    .order-step::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 100%;
        height: 2px;
        background-color: #ddd;
        z-index: -1;
    }

    .order-step:first-child::before {
        left: 50%;
        width: 50%;
    }

    .order-step:last-child::before {
        width: 50%;
    }

    .order-step.active .circle {
        background-color: green;
    }

    .order-step.inactive .circle {
        background-color: red;
    }

    .order-step .circle {
        display: inline-block;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #ddd;
        line-height: 40px;
        color: #fff;
    }

    .order-step .label {
        margin-top: 10px;
        color: red;
        font-weight: bold;
    }

    /* You can use these classes to set each step active/inactive based on status */
    .active-step {
        background-color: green !important;
    }

    .inactive-step {
        background-color: red !important;
    }
    </style>
</head>

<body>
    <?php include('Source/Nav/header.php') ?>

    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="./index.php"><i class="fa fa-home"></i> Home</a>
                        <span><i class="bi bi-geo-alt"></i>Track Order</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Track Order Section Begin -->
    <section class="track-order-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-white">
                    <h4>Track Your Order</h4>
                    <div class="order-tracker ">
                        <!-- Processing Step -->
                        <div
                            class="order-step text-white <?= ($active_step == 'Processing') ? 'active' : 'inactive' ?>">
                            <div class="circle"><i class="bi bi-clock"></i></div>
                            <div class="label">Processing</div>
                        </div>

                        <!-- Packed Step -->
                        <div class="order-step <?= ($active_step == 'Shipped') ? 'active' : 'inactive' ?>">
                            <div class="circle"><i class="bi bi-box-seam"></i></div>
                            <div class="label">Shipped</div>
                        </div>

                        <!-- Shipped Step -->
                        <div class="order-step <?= ($active_step == 'Delivered') ? 'active' : 'inactive' ?>">
                            <div class="circle"><i class="bi bi-truck"></i></div>
                            <div class="label">Delivered</div>
                        </div>

                        <!-- Delivered Step -->
                        <div class="order-step <?= ($active_step == 'Received') ? 'active' : 'inactive' ?>">
                            <div class="circle"><i class="bi bi-check"></i></div>
                            <div class="label">Received</div>
                        </div>
                    </div>
                    <div class="order-step <?= ($active_step == 'Cancelled') ? 'active' : 'inactive' ?>">
                        <div class="circle"><i class="bi bi-trash-fill"></i></div>
                        <div class="label">Cancelled</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Track Order Section End -->

    <?php include('Source/Nav/footer.php'); ?>
</body>

</html>