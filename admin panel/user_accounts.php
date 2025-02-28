<?php
    include '../components/connect.php';

    if (isset($_COOKIE['seller_id'])) {
        $seller_id = $_COOKIE['seller_id'];
    }else{
        $seller_id = '';
        header('location:login.php');
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registerd Users Page</title>
    <link rel="stylesheet" type="text/css" href="../css/header_style.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>

        <section class="message-container">
            <div class="heading">
                <h1>registered users</h1>
            </div>
            <div class="box-container">
            <?php
                $select_users = $conn->prepare("SELECT * FROM `users`");
                $select_users->execute();
                if ($select_users->rowCount() > 0) {
                    while($fetch_users = $select_users->fetch(PDO:: FETCH_ASSOC)) {
                        $user_id = $fetch_users['id'];
            ?>
            <div class="box">
                <img src="../uploaded_files/<?= $fetch_users['image']; ?>">
                <p>user id : <span><?= $user_id; ?></span></p>
                <p>user name : <span><?= $fetch_users['name']; ?></span></p>
                <p>user email : <span><?= $fetch_users['email']; ?></span></p>
            </div>
            <?php
                }}else{
                    echo '
                        <div class="empty">
                            <p>no user registered yet!</p>
                        </div>
                   ';
                }
            ?>
        </section>
    </div>

    <!-- sweetalert cdn -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <!-- custom js -->
    <script src="../js/admin_script.js"></script>

    <?php include '../components/alert.php'; ?>

    
</body>
</html>