<?php
    include '../components/connect.php';

    if (isset($_POST['submit'])) {
        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);

        $pass = sha1($_POST['pass']);
        $pass = filter_var($pass, FILTER_SANITIZE_STRING);

        $select_seller = $conn->prepare("SELECT * FROM `sellers` WHERE email = ? and password = ?");
        $select_seller->execute([$email, $pass]);
        $row = $select_seller->fetch(PDO::FETCH_ASSOC);
        
        if ($select_seller->rowCount() > 0) {
            setcookie('seller_id', $row['id'], time() + 60*60*24*30, '/');
            header('location:dashboard.php');
        }else{
            $warning_msg[] = 'incorrect email or passowrd'; 
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Login Page</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>

</head>
<body>
    <!-- Header Section -->
    <div id="head">
        <div id="top">
            <img src="../images/sources/email.svg" alt="Email Icon">
            contact@tunetrade.com 
            <img src="../images/sources/phone.svg" alt="Phone Icon"> Call us: 015146044 
        </div>
        <div id="socials">
            <img src="../images/sources/Vector.svg" alt="Social Icon 1">
            <img src="../images/sources/Vector-1.svg" alt="Social Icon 2">
            <img src="../images/sources/Vector-2.svg" alt="Social Icon 3">
            <img src="../images/sources/Vector-3.svg" alt="Social Icon 4">
        </div>
    </div>
    <div class="form-container">
        <form action="" method="post" class="login" enctype="multipart/form-data">
            <h3>Login As a seller</h3>
            <div class="input-field">
                <p>Your Email <span>*</span></p>
                <input type="email" name="email" placeholder="Enter your email" maxlength="50" required class="box">                        
            </div>
            <div class="input-field">
                <p>Your Password <span>*</span></p>
                <input type="password" name="pass" placeholder="Enter your password" maxlength="50" required class="box">                        
            </div>             
            <p class="link">Do not have an account? <a href="register.php">Register Now</a></p>
            <input type="submit" name="submit" value="Login Now" class="btnn">
        </form>
    </div>

    <!-- sweetalert cdn -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <!-- custom js -->
    <script src="../js/script.js"></script>

    <?php include '../components/alert.php'; ?>

    
</body>
</html>