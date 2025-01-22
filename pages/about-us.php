<?php
    include('../components/connect.php');
    // include('.../includes/header.php');

    //userid
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
    }

    // Handle the email subscription form
    if (isset($_POST['subscribe'])) {
        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $check_email = $conn->prepare("SELECT * FROM `subscribers` WHERE email = ?");
            $check_email->execute([$email]);

            if ($check_email->rowCount() > 0) {
                $warning_msg[] = 'This email is already subscribed';
            } else {
                // Generate a unique ID for the subscriber
                $subscriber_id = unique_id();

                // Insert the email into the subscribers table
                $insert_subscriber = $conn->prepare("INSERT INTO `subscribers` (id, email) VALUES (?, ?)");
                $insert_subscriber->execute([$subscriber_id, $email]);

                $success_msg[] = 'Successfully subscribed! You will receive a 10% discount.';
            }
        } else {
            $warning_msg[] = 'Please enter a valid email address';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link href="../css/about.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <link rel="icon" href="../images/sources/logo.svg">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        
    </style>
</head>
<body>
    <div class="container">
        <?php include '../includes/header.php' ?>
        <div id="about-us">

            <section class="about-intro">
                <div class="about-text">
                    <h2>Welcome to Tune Trade</h2>
                    <p class="about-hero">Your go-to marketplace for quality musical instruments.</p>
                    <p>                        
                        At Tune Trade, we believe in the power of music to bring people together. 
                        Whether you're a beginner exploring your first instrument or a professional adding to your collection, 
                        we're here to provide the tools you need to bring your musical vision to life.
                    </p>
                    <section class="mission-vision">
                        <div class="mission">
                            <h2>Our Mission</h2>
                            <p>To make quality musical instruments accessible and affordable for all, while fostering a love for music in our community.</p>
                        </div>
                        <div class="vision">
                            <h2>Our Vision</h2>
                            <p>
                                To be a trusted partner for musicians nationwide, empowering creativity and innovation in the world of music.
                            </p>
                        </div>
                    </section>
                </div>
            </section>           

            <section class="why-choose-us">
                <h2>Why Choose Tune Trade?</h2>
                <ul>
                    <li>🎸 Wide variety of instruments for every skill level and style.</li>
                    <li>💼 Affordable pricing without compromising on quality.</li>
                    <li>🌟 A seamless shopping experience with secure payments.</li>
                    <li>🚚 Fast, reliable delivery and hassle-free returns.</li>
                </ul>
            </section>
            <section class="why-choose-us">
                <h2>Join the Tune Trade Community</h2>
                <p>
                    Tune Trade isn’t just an e-commerce site — it’s a hub for music lovers. <br>Follow us on social media, 
                    subscribe to our newsletter, and connect with a community of artists, learners, and enthusiasts.
                </p>
                <div class="social-icons">
                    <a href="#"><i class="bx bxl-facebook"></i></a>
                    <a href="#"><i class="bx bxl-instagram"></i></a>
                    <a href="#"><i class="bx bxl-twitter"></i></a>
                </div>
            </section>
        </div>
         <!-- Get Connected Section -->
         <div id="getconnected">
            <div id="content">
                <p id="offer">Get 10% Off On Your Order!</p>
                <p id="enter">Enter your email and receive a 10% discount on your next order!</p>
                <div id="input">
                <form action="" method="POST" class="subscribe-form">
                    <input type="text" id="emailenter" name="email" placeholder="Your Email Address" required>
                    <input type="submit" id="subscribe" name="subscribe" value="Subscribe">
                </form>
                </div>
            </div>
            <div id="img7">
                <img src="../images/sources/Frame 7.png" alt="Promotional Image">
            </div>
        </div>
    </div>
    <!-- sweetalert cdn -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
        <!-- custom js -->
        <!-- <script src="../js/user_script.js"></script> -->
        <?php include '../components/alert.php'; ?>



</body>
</html>
