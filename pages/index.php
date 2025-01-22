<?php
    include('../components/connect.php');
    // include('../includes/header.php');

    //userid
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
    }

    // Fetch active products from the database
    $sql = "SELECT * FROM products WHERE status = 'active'"; 
    $result = $conn->query($sql);

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
    <title>Tune Trade</title>
    <link rel="stylesheet" href="../css/index_style.css">
    <link rel="stylesheet" href="../css/user_style.css">

    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <link rel="icon" href="../images/sources/logo.svg">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        
        #subscribe:hover {
            background-color: #e9c391;
            color: #333;
            cursor: pointer;
            transform: scale(1.05);
            transition: all 0.3s ease;
        }

        #img7:hover img {
            transform: scale(1.1);
            transition: transform 0.4s ease;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div id="head">
            <div class="top">
                <img src="../images/sources/email.svg" alt="Email Icon">
                contact@tunetrade.com 
                <img src="../images/sources/phone.svg" alt="Phone Icon"> Call us: 015146044 
            </div>
            <div id="socials">
                <div class="bx bx-list-plus" id="menu-btn"></div>
                <div class="bx bx-search-alt-2" id="search-btn"></div>
                <?php
                    $count_wishlist_item = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
                    $count_wishlist_item->execute([$user_id]);
                    $total_wishlist_items = $count_wishlist_item->rowCount();
                ?>

                <a href="wishlist.php"><i class="bx bx-heart"></i><sup><?= $total_wishlist_items; ?></sup></a>

                <?php
                    $count_cart_item = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                    $count_cart_item->execute([$user_id]);
                    $total_cart_items = $count_cart_item->rowCount();
                ?>
                <a href="cart.php"><i class="bx bx-cart"></i><sup><?= $total_cart_items; ?></sup></a>
                <div class="bx bxs-user" id="user-btn"></div>
            </div>
            <div class="profile-detail">
                <?php
                    $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                    $select_profile->execute([$user_id]);
                    if ($select_profile->rowCount() > 0) {
                        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                ?>
                <img src="uploaded_files/<?= $fetch_profile['image']; ?>">
                <h3 style="margin-bottom: 1rem;"><?= $fetch_profile['name']; ?></h3>
                <div class="flex-btn">
                    <a href="profile.php" class="btn">view profile</a>
                    <a href="components/user_logout.php" onclick="return confirm('logout from this website');" class="btn">logout</a>
                </div>
                <?php }else{ ?>
                    <h3 style="margin-bottom: 1rem;"> Please login or register</h3>
                    <div class="flex-btn">
                        <a href="login.php" class="btn">login</a>
                        <a href="register.php" class="btn">register</a>
                    </div>
                <?php } ?>
            </div>

        </div>

        <!-- Navigation Section -->
        <div id="nav">
            <img src="../images/sources/logo.svg" alt="Tune Trade Logo">
            <div id="navright">
                <a href="index.php">HOME</a>
                <a href="about-us.php">ABOUT US</a>
                <a href="menu.php">SHOP</a>
                <a href="order.php">ORDER</a>
                <a href="login.php">SELL YOUR ITEMS</a>
                
            </div>
            <form action="search_product.php" method="post" class="search-form">
                    <input type="text" name="search_product" placeholder="Search Product.." required maxlength="100">
                    <button type="submit" class="bx bx-search-alt-2" id="search_product_btn"></button>
            </form>
        </div>

        <!-- Main Section -->
        <div id="main">
            <img src="../images/sources/bg.svg" id="bg" alt="Background Image">
            <div id="AbstractRect">
                <div id="Brand">
                    <span style="color: #e9c391; font-size: 40px; font-family: Poppins; font-weight: 600; letter-spacing: 1px;">TUNE<br/></span>
                    <span style="color: #6A826A; font-size: 40px; font-family: Poppins; font-weight: 600; letter-spacing: 1px;">TRADE</span>
                </div>
            </div>
            <img id="IM" src="../images/sources/abs.png" alt="Abstract Image">
        </div>

        <!-- About Section -->
        <div id="about">
            <div id="Rec1"></div>
            <div id="Rec2">
                <p id="About">About Tune Trade</p>
            </div>
            <p id="Text">
                Tune Trade provides musicians with a seamless shopping experience of a comprehensive collection of musical instruments. 
                We help musicians worldwide to sell their instruments, buy new ones, or rent them out. 
                We take pride in introducing quality products to the Nepali music scene at an affordable price range. 
                We believe everyone, from beginners and hobbyists to semi-professional artists and performers, deserves fine-quality musical instruments to unleash their true potential. <br>
                Tune Trade is where melody meets convenience.
            </p>
            <p id="lm">LEARN MORE</p>
        </div>

        <!-- Products Section -->
        <!-- <div id="products">
            <p id="pheading">Our Products</p>
            <div id="borderr"></div>
            <div class="pp1">
                <?php
                // while ($product = $result->fetch(PDO::FETCH_ASSOC)) {
                //     echo "<div class='inside'>
                //             <img class='less' src='/uploads/{$product['image']}' alt='{$product['name']}'>
                //             <div class='middle'>{$product['name']}</div>
                //           </div>";
                // }
                ?>
            </div>
            <p id="vm">VIEW MORE</p>
        </div> -->

        <!-- Products Section -->
        <div id="products">
            <p id="pheading">Our Products</p>
            <div id="borderr"></div>
            <div class="pp1">
                <div class="inside">
                    <img class="less" src="../images/sources/Frame 1.svg" alt="Acoustic Guitar">
                    <div class="middle">Acoustic Guitar</div>
                </div>
                <div class="inside">
                    <img class="less" src="../images/sources/Frame 2.svg" alt="Guitar Bag">
                    <div class="middle">Guitar Bag</div>
                </div>
                <div class="inside">
                    <img class="less" src="../images/sources/Frame 3.svg" alt="Bass Guitar">
                    <div class="middle">Bass Guitar</div>
                </div>
            </div>
            <div class="pp2">
                <div class="inside">
                    <img class="less" src="../images/sources/Frame 4.svg" alt="Electric Guitar">
                    <div class="middle">Electric Guitar</div>
                </div>
                <div class="inside">
                    <img class="less" src="../images/sources/Frame 5.svg" alt="Capo">
                    <div class="middle">Capo</div>
                </div>
                <div class="inside">
                    <img class="less" src="../images/sources/Frame 6.svg" alt="AMP">
                    <div class="middle">AMP</div>
                </div>
            </div>
            <p id="vm">VIEW MORE</p>
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

        <!-- Footer Section -->
        <footer>
            <div class="sections">
                <div>
                    <ul>
                        <li class="category">Address</li>
                        <li class="list">Eye Plex Mall</li>
                        <li>Baneshwor, KTM</li>
                    </ul>
                </div>
                <div>
                    <ul>
                        <li class="category">Important Links</li>
                        <li class="list"><a href="index.php">Home</a></li>
                        <li class="list"><a href="menu.php">Products</a></li>
                        <li class="list"><a href="about_us.php">About Us</a></li>
                        <li class="list"><a href="order.php">Your orders</a></li>
                        <li class="list"><a href="../admin panel/login.php">Sell Your Items</a></li>
                    </ul>
                </div>
                <div>
                    <ul>
                        <li class="category">Support</li>
                        <li class="list">How to Order</li>
                        <li class="list">How to Make Payment</li>
                        <li class="list">FAQ</li>
                        <li class="list">Help Center</li>
                        <li class="list">Live Chat</li>
                        <li class="list">Contact Us</li>
                    </ul>
                </div>
                <div>
                    <ul>
                        <li class="category">Stay Connected</li>
                        <li><img src="../images/sources/icons.svg" alt="Social Icons"></li>
                    </ul>
                </div>
            </div>
        </footer>
    </div>
    <!-- sweetalert cdn -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
        <!-- custom js -->
        <script src="../js/user_script.js"></script>

        <?php include '../components/alert.php'; ?>
</body>
</html>
