<?php
    include('./components/connect.php');

    //userid
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = 'location:login.php';
    }
    $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
    $select_orders->execute([$user_id]);
    $total_orders = $select_orders->rowCount();
    $select_message = $conn->prepare("SELECT * FROM `message` WHERE user_id = ?");
    $select_message->execute([$user_id]);
    $total_message = $select_message->rowCount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile Page</title>
    <link rel="stylesheet" href="./css/profile.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <link rel="icon" href="./images/sources/logo.svg">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        
    </style>
</head>
<body>
    <div class="container">
        <?php include 'includes/header.php' ?>
        <section class="profile">
            <div class="heading">
                <h1>profile detail</h1>
            </div>
            <div class="details">
                <div class="user">
                    <img src="uploaded_files/<?= $fetch_profile['image']; ?>">
                    <h3><?= $fetch_profile['name']; ?></h3>
                    <p>user</p>
                    <a href="update.php" class="btn">update profile</a>
                </div>
                <div class="box-container">
                    <div class="box">
                        <div class="flex">
                            <i class="bx bxs-folder-minus"></i>
                            <h3><?= $total_orders; ?></h3>
                        </div>
                        <a href="order.php" class="btn">view orders</a>
                    </div>
                    <div class="box">
                        <div class="flex">
                            <i class="bx bxs-chat"></i>
                            <h3><?= $total_message; ?></h3>
                        </div>
                        <a href="message.php" class="btn">view message</a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- sweetalert cdn -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <!-- custom js -->
    <script src="js/user_script.js"></script>

    <?php include 'components/alert.php'; ?>
</body>
</html>
