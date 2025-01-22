<?php
    include('./components/connect.php');

    //userid
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
    }
    if(isset($_GET['get_id'])){
        $get_id = $_GET['get_id'];
    }else{
        $get_id = '';
        header('location:order.php');
    }
    if (isset($_POST['cancle'])) {
      $update_order = $conn->prepare("UPDATE `orders` SET status = ? WHERE id = ?");
      $update_order->execute(['cancled', $get_id]);
      header('location: order.php');
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Detail</title>
    <link rel="stylesheet" href="./css/order.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <link rel="icon" href="./images/sources/logo.svg">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        
    </style>
</head>
<body>
    <div class="container">
        <?php include './includes/header.php' ?>
        <div class="order-detail">
            <div class="heading">
                <h1>my order detail</h1>
            </div>
            <div class="box-container">
                <?php
                    $grand_total = 0;
                    $select_order = $conn->prepare("SELECT * FROM `orders` WHERE id = ? LIMIT 1");
                    $select_order->execute([$get_id]);
                    if ($select_order->rowCount() > 0) {
                        while($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)){
                            $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
                            $select_product->execute([$fetch_order['product_id']]);
                            if ($select_product->rowCount() > 0) {
                                while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)) {
                                    $sub_total = ($fetch_order['price']* $fetch_order['qty']);
                                    $grand_total += $sub_total;
                ?>
                <div class="box">
                    <div class="col">
                        <p class="title"> <i class="bx bxs-calendar-alt"></i><?= $fetch_order['date'];?> </p>
                        <img src="uploaded_files/<?= $fetch_product['image']; ?>" class="image">
                        <p class="price">$<?= $fetch_product['price']; ?>/-</p>
                        <h3 class="name"><?= $fetch_product['name']; ?></h3>
                        <p class="grand-total">total amount payable : <span><?= $grand_total; ?></span></p>
                    </div>
                    <div class="col">
                        <p class="title">billing address</p>
                        <p class="user"><i class="bi bi-person-bounding-box"></i><?= $fetch_order['name']; ?></p>
                        <p class="user"><i class="bi bi-phone"></i><?= $fetch_order [ 'number']; ?></p>
                        <p class="user"><i class="bi bi-envelope"></i><?= $fetch_order['email']; ?></p>
                        <p class="user"><i class="bi bi-pin-map-fill"></i><?= $fetch_order['address'];?></p>
                        <p class="status" style="color:<?php if($fetch_order['status'] == 'delivered') {echo "green"; } elseif($fetch_order['status'] == 'cancled') { echo "red" ;}else{echo "orange";} ?>"><?= $fetch_order['status']; ?></p>
                        <?php if($fetch_order['status']=='cancled'){ ?>
                            <a href="checkout.php?get_id=<?= $fetch_product['id']; ?>" class="btn">order again</a>
                        <?php }else{ ?>
                            <form action="" method="post">
                                <button type="submit" name="cancle" class="btn" onclick="return confirm('do you want to cancel this product');">Cancel</button>
                            </form>
                        <?php } ?>
                    </div>
                </div>
                <?php
                            }
                        }
                    }
                }
                else{
                    echo '<p class="empty">no order take placed yet</p>';
                }      
                ?>
            </div>
    </div>

    <!-- sweetalert cdn -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <!-- custom js -->
    <script src="js/user_script.js"></script>

    <?php include 'components/alert.php'; ?>
</body>
</html>
