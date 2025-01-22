<?php
    include('../components/connect.php');

    //userid
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
    }
    
    include '../components/add_wishlist.php';
    include '../components/add_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search product</title>
    <link rel="stylesheet" href="../css/shop.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <link rel="icon" href="../images/sources/logo.svg">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        
    </style>
</head>
<body>
    <div id="container">
        <?php include '../includes/header.php' ?>
        <div class="products">
            <div class="heading">
                <h1>Search Result</h1>
            </div>
            <div class="box-container">
            <?php
                if (isset($_POST['search_product']) or isset($_POST['search_product_btn'])) {
                    $search_products = $_POST['search_product'];
                    $select_products = $conn->prepare("SELECT * FROM `products` WHERE name LIKE '%{$search_products}%' AND status=?");
                    $select_products->execute(['active']);
                    if ($select_products->rowCount() > 0) {
                        while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
                            $product_id = $fetch_products['id'];
            ?>
            <form action="" method="post" class="box <?php if($fetch_products['stock'] == 0) {echo "disabled"; } ?>">
                    <img src="../uploaded_files/<?= $fetch_products['image']; ?>" class="image">
                    <?php if($fetch_products['stock'] > 9){ ?>
                        <span class="stock" style="color: green;">In stock</span>
                    <?php }elseif($fetch_products['stock'] == 0){ ?>
                        <span class="stock" style="color: red;">out of stock</span>
                    <?php }else{ ?>
                        <span class="stock" style="color: red;">Hurry, only <?= $fetch_products ['stock']; ?>left</span>
                    <?php } ?>
                    <div class="content">
                        <!-- <img src="image/shape-19.png" alt="" class="shap"> -->
                        <div class="button">
                            <div> <h3 class="name"><?= $fetch_products['name']; ?></h3> </div>
                            <div>
                                <button type="submit" name="add_to_cart"><i class="bx bx-cart"></i></button>
                                <button type="submit" name="add_to_wishlist"><i class="bx bx-heart"></i></button>
                                <a href="view_page.php?pid=<?= $fetch_products['id'] ?>" class="bx bxs-show"></a>
                            </div>
                        </div>
                        <p class="price">price $<?= $fetch_products['price']; ?></p>
                        <input type="hidden" name="product_id" value="<?= $fetch_products['id'] ?>">
                        <div class="flex-btn">
                            <a href="checkout.php?get_id=<?= $fetch_products['id'] ?>" class="btn">buynow</a>
                            <input type="number" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty box">
                        </div>
                    </div>
                </form>
            <?php
                        }
                    }else{
                        echo '
                            <div class="empty">
                                <p> No product found! </p>
                            </div>
                        ';
                    }
                }else{
                    echo '
                        <div class="empty">
                            <p>Search something else </p>
                        </div>
                    ';
                }
            ?>
            </div>
        </div>
    </div>

    <!-- sweetalert cdn -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <!-- custom js -->
    <script src="../js/user_script.js"></script>

    <?php include '../components/alert.php'; ?>
</body>
</html>
