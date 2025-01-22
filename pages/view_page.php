<?php
    include('./components/connect.php');

    // User ID from cookie
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    } else {
        $user_id = '';
    }

    // Include necessary components for cart and wishlist functionality
    include './components/add_wishlist.php';
    include './components/add_cart.php';

    // Get product ID from the query string
    if (isset($_GET['pid'])) {
        $product_id = $_GET['pid'];
        $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ? AND status = ?");
        $select_product->execute([$product_id, 'active']);
        $product = $select_product->fetch(PDO::FETCH_ASSOC);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product View</title>
    <link rel="stylesheet" href="./css/shop.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <link rel="icon" href="./images/sources/logo.svg">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        
    </style>
</head>
<body>
    <div id="container">
        <?php include './includes/header.php'; ?>

        <div class="product-details">
            <?php if ($product) { ?>
                <div class="product-image">
                    <img src="uploaded_files/<?= $product['image']; ?>" alt="<?= $product['name']; ?>">
                </div>

                <div class="product-info">
                    <h1><?= $product['name']; ?></h1>
                    <p><?= $product['product_detail']; ?></p>
                    <p class="price">Price: $<?= $product['price']; ?></p>

                    <?php if ($product['stock'] > 9) { ?>
                        <span class="stock" style="color: green;">In stock</span>
                    <?php } elseif ($product['stock'] == 0) { ?>
                        <span class="stock" style="color: red;">Out of stock</span>
                    <?php } else { ?>
                        <span class="stock" style="color: red;">Hurry, only <?= $product['stock']; ?> left</span>
                    <?php } ?>

                    <!-- Back button -->
                     <div class="back-btn">
                     <a href="menu.php" class="back-btn" style=" color: #fff;">Back to Menu</a>
                     </div>
                    
                </div>

            <?php } else { ?>
                <div class="empty">
                    <p>Product not found.</p>
                </div>
            <?php } ?>
        </div>

    </div>

    <!-- SweetAlert CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <?php include 'components/alert.php'; ?>
</body>
</html>
