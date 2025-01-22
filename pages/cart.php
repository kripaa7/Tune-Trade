<?php
    include('./components/connect.php');

    //userid
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
        header('location:login.php');
    }

    // Collect all product IDs in the cart
    $product_ids = [];
    $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $select_cart->execute([$user_id]);
    if ($select_cart->rowCount() > 0) {
        while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
            $product_ids[] = $fetch_cart['product_id'];  // Add product ID to the array
        }
    }

    // Join product IDs with commas to pass them as a query parameter
    $product_ids_str = implode(',', $product_ids);



    if (isset($_POST['place_order'])) {

        $cart_id = $_POST['cart_id'];
        $cart_id = filter_var($cart_id, FILTER_SANITIZE_STRING);

        $qty = $_POST['qty'];
        $qty = filter_var($qty, FILTER_SANITIZE_STRING);

        $update_qty = $conn->prepare("UPDATE cart SET qty = ? WHERE id = ?");
        $update_qty->execute([$qty, $cart_id]);

        $success_msg[] = 'cart quantity updated successfully';
    }
    // Update cart quantity logic
    if (isset($_POST['update_cart'])) {
        $cart_id = filter_var($_POST['cart_id'], FILTER_SANITIZE_STRING);
        $qty = filter_var($_POST['qty'], FILTER_SANITIZE_STRING);

        $update_qty = $conn->prepare("UPDATE cart SET qty = ? WHERE id = ?");
        $update_qty->execute([$qty, $cart_id]);

        $success_msg[] = 'Cart quantity updated successfully';
    }

    // Empty cart logic
    if (isset($_POST['empty_cart'])) {
        $empty_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $empty_cart->execute([$user_id]);

        $success_msg[] = 'Cart emptied successfully';
    }

    // Check if 'delete_item' is triggered
    if (isset($_POST['delete_item'])) {
        $cart_id = $_POST['cart_id'];  // Get the cart item id
        $cart_id = filter_var($cart_id, FILTER_SANITIZE_STRING);  // Sanitize input

        // Prepare and execute delete query
        $delete_item = $conn->prepare("DELETE FROM cart WHERE id = ?");
        $delete_item->execute([$cart_id]);

        // Show success message
        $success_msg[] = 'Product removed from cart successfully';
    }



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="./css/cart.css">
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
        <div class="products">
            <div class="heading">
                <h1>Cart</h1>
            </div>
            <div class="box-container">
            <?php
                $grand_total = 0;
                $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                $select_cart->execute([$user_id]);
                if ($select_cart->rowCount() > 0) {
                    while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                        $select_products = $conn->prepare("SELECT * FROM products WHERE id = ?");
                        $select_products->execute([$fetch_cart['product_id']]);
                        if ($select_products->rowCount() > 0) {
                            $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
            ?>
            <form action="" method="post" class="box <?php if($fetch_products['stock'] == 0){echo'disabled'; }; ?>">
                <input type="hidden" name="cart_id" value="<?= $fetch_cart ['id']; ?>">
                <img src="uploaded_files/<?= $fetch_products['image']; ?>" class="image">
                <?php if($fetch_products['stock'] > 9){ ?>
                    <span class="stock" style="color: green;">In stock</span>
                <?php }elseif($fetch_products['stock'] == 0){ ?>
                    <span class="stock" style="color: red;">out of stock</span>
                <?php }else{ ?>
                    <span class="stock" style="color: red;">Hurry, only <?= $fetch_products ['stock']; ?>left</span>
                <?php } ?>
                <div class="content">
                    <h3 class="name"><?= $fetch_products['name']; ?></h3>
                    <div class="flex-btn">
                        <p class="price">price $<?= $fetch_products['price']; ?>/-</p>
                        <input type="number" name="qty" required min="1" value="<?= $fetch_cart['qty']; ?>" max="99" maxlength="2" class="box qty">
                        <button type="submit" name="update_cart" class="bx bxs-edit fa-edit box"></button>
                    </div>
                    <div class="flex-btn">
                        <p class="sub-total">sub-total: <span class="sub-total">$<?= $sub_total = ($fetch_cart['qty']*$fetch_products['price']); ?></span></p>
                        <button type="submit" name="delete_item" class="btn" onclick="return confirm('remove form cart');">delete</button>
                    </div>                                    
                </div>
            </form>
            <?php
            $grand_total+= $sub_total;
                        }else{
                            echo '
                                <div class="empty">
                                    <p>no products was found!</p>
                                </div>
                            ';
                        }
                    }
                }else{
                    echo '
                        <div class="empty">
                            <p>no products added yet!</p>
                        </div>
                    ';
                }
            ?>
        </div>
        <?php if($grand_total != 0) { ?>
            <div class="cart-total">
                <p>total amount payable : <span> $ <?= $grand_total; ?>/-</span></p>
                <div class="button">
                    <form action="" method="post">
                        <button type="submit" name="empty_cart" class="btn" onclick="return confirm
                            ('are you sure to empty your cart');" >empty cart</button>
                    </form>
                    <a href="checkout.php?get_id=<?= $product_ids_str ?>" class="btn">proceed to checkout</a>
                </div>
            </div>
        <?php } ?>

    </div>

    <!-- sweetalert cdn -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <!-- custom js -->
    <script src="js/user_script.js"></script>

    <?php include 'components/alert.php'; ?>
</body>
</html>
