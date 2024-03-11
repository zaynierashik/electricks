<?php
    session_start();
    include('../config/dbconn.php');

    if (!isset($_SESSION['id']) || (trim($_SESSION['id']) == '')) {
        header('location:user_login_page.php');
        exit();
    }

    $status = $_GET['status'];
    $amount = $_GET['amount'] / 100;
    $user_id = $_SESSION['id'];
    $transac_id = $_GET['transaction_id'];

    // Fetch data from the appropriate table (cart_data or order_details)
    $sql = "SELECT * FROM order_details WHERE user_id = ?";
    $stmt = mysqli_prepare($dbconn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $orderItems = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $orderItems[] = array(
            'order_details_id' => $row['order_details_id'],
            'prod_id' => $row['prod_id'],
            'prod_qty' => $row['prod_qty'],
            'total' => $row['total'],
            'user_id' => $row['user_id']
        );
    }

    // Insert data into the order_details table
    foreach ($orderItems as $item) {
        $order_id = $item['order_details_id'];
        $prod_id = $item['prod_id'];
        $prod_qty = $item['prod_qty'];
        $total = $item['total'];
        $user_id = $item['user_id'];

        $sql = "INSERT INTO `order` (order_id, prod_id, prod_qty, totalprice, user_id, transac_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($dbconn, $sql);
        mysqli_stmt_bind_param($stmt, 'isssis', $order_id, $prod_id, $prod_qty, $total, $user_id, $transac_id);
        mysqli_stmt_execute($stmt);
    }

    // Clean up the cart_data table
    $sql = "DELETE FROM order_details WHERE user_id = ?";
    $stmt = mysqli_prepare($dbconn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $user_id);
    mysqli_stmt_execute($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Lego Empire</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon/favicon-16x16.png">
    <link rel="manifest" href="../../favicon/site.webmanifest">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <!-- <link href="https://fonts.cdnfonts.com/css/louis-george-cafe" rel="stylesheet"> -->
    <link rel="stylesheet" href="../../css/user.css">

    <style>
        body{
            background-color: #f6f4f9;
        }
    </style>
</head>
<body>
    <div class="container payment-container mt-5">
        <div class="row text-center">
            <div class="col-md-12">
                <div>
                    <h3 class="fw-bold mt-5">Payment Success</h3>
                    <h6 class="mt-3 mb-5">Thank you for purchasing via Khalti Payment Gateway! Your payment has been confirmed successfully.</p>

                    <h6 class="fw-bold">Paid Amount: NRs. <?php echo $amount ?></h6>
                </div>
            </div>
            
            <div class="col-md-12 mt-5">
                <a href="user_index.php">
                    <button class="btn btn-primary py-2 fw-bold w-50">Back to userpage</button>
                </a>
            </div>
        </div>
    </div>
</body>
</html>