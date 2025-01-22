<?php
    include('./components/connect.php');

    //userid
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
        header('location:login.php');
    }
    
    if (isset($_POST['place_order'])) {

        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);

        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);

        $number = $_POST['number'];
        $number = filter_var($number, FILTER_SANITIZE_STRING); 

        $address = $_POST['flat'].', '.$_POST['street'].', '.$_POST['city'].', '.$_POST['country'].', '.$_POST['pin'];
        $address = filter_var($address, FILTER_SANITIZE_STRING);
        
        $address_type = $_POST['address_type'];
        $address_type = filter_var($address_type, FILTER_SANITIZE_STRING);

        $method = $_POST['method'];
        $method = filter_var($method, FILTER_SANITIZE_STRING); 

        $verify_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $verify_cart->execute([$user_id]);

        if (isset($_GET['get_id'])) {
            $product_ids = explode(',', $_GET['get_id']);
            $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
            $get_products = $conn->prepare("SELECT * FROM `products` WHERE id IN ($placeholders)");
            $get_products->execute($product_ids);
            if ($get_products->rowCount() > 0) {
                while($fetch_p = $get_products->fetch(PDO::FETCH_ASSOC)){
                    $seller_id = $fetch_p['seller_id'];
                    $insert_order = $conn->prepare("INSERT INTO orders (id, user_id, seller_id, name, number, email, address, address_type, method, product_id, price, qty) VALUES (?,?,?,?, ?, ?, ?, ?, ?, ?, ?,?)");
                    $insert_order->execute([uniqid(), $user_id, $seller_id, $name, $number, $email, $address, $address_type, $method, $fetch_p['id'], $fetch_p['price'], 1]);
                }
                header('location: order.php');
            } else {
                $warning_msg[] = 'Something went wrong';
            }
        }elseif($verify_cart->rowCount() > 0) {
            while($f_cart = $verify_cart->fetch(PDO::FETCH_ASSOC)){
                $s_products = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
                $s_products->execute([$f_cart['product_id']]);
                $f_product = $s_products->fetch(PDO::FETCH_ASSOC);

                $seller_id = $f_product['seller_id'];

                $insert_order = $conn->prepare("INSERT INTO `orders` (id, user_id, seller_id, name, number, email, address, address_type, method, product_id, price, qty) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                    $insert_order->execute([uniqid(), $user_id, $seller_id, $name, $number, $email, $address, $address_type, $method, $f_cart['product_id'], $f_cart['price'], $f_cart['qty']]);
            }
            if ($insert_order) {
                $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
                $delete_cart->execute([$user_id]);
                header('location:order.php');
            }
        }else{
            $warning_msg[] = 'somthing went wrong';
        }
    }

    // Fetch the products being viewed (if any)
if (isset($_GET['get_id'])) {
    // Split the get_id parameter into an array of product IDs
    $product_ids = explode(',', $_GET['get_id']);
    
    // Generate placeholders for the SQL query
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    
    // Fetch all products matching the given IDs
    $get_products = $conn->prepare("SELECT * FROM `products` WHERE id IN ($placeholders)");
    $get_products->execute($product_ids);

    if ($get_products->rowCount() > 0) {
        // Loop through each fetched product
        $recommended_products = [];
        while ($fetch_p = $get_products->fetch(PDO::FETCH_ASSOC)) {
            $product_name = $fetch_p['name']; // Get the product name for keyword-based search
            $seller_id = $fetch_p['seller_id'];

            // Split product name into keywords
            $keywords = explode(" ", $product_name);

            // Create an SQL query to find products with similar keywords
            $keyword_conditions = [];
            foreach ($keywords as $keyword) {
                $keyword_conditions[] = "name LIKE ?";
            }

            $query = "SELECT * FROM `products` WHERE (" . implode(" OR ", $keyword_conditions) . ") AND id NOT IN ($placeholders) LIMIT 4";
            $params = array_map(function ($keyword) { return "%$keyword%"; }, $keywords);
            $params = array_merge($params, $product_ids); // Exclude current products from recommendations
            $recommendations = $conn->prepare($query);
            $recommendations->execute($params);

            // Merge similar products into recommendations
            $recommended_products = array_merge($recommended_products, $recommendations->fetchAll(PDO::FETCH_ASSOC));

            // If no similar products are found, recommend products from the same seller
            if (empty($recommended_products)) {
                $recommendations = $conn->prepare("SELECT * FROM `products` WHERE seller_id = ? AND id NOT IN ($placeholders) LIMIT 4");
                $params = array_merge([$seller_id], $product_ids); // Exclude current products
                $recommendations->execute($params);
                $recommended_products = array_merge($recommended_products, $recommendations->fetchAll(PDO::FETCH_ASSOC));
            }
        }

        // Remove duplicate recommendations
        $recommended_products = array_unique($recommended_products, SORT_REGULAR);
    }
}


    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <link rel="stylesheet" href="./css/checkout.css">
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
        <div class="checkout">
            <div class="heading">
                <h1>Checkout Summary</h1>
            </div>
            <div class="row">
                <form action="" method="post" class="register">
                    <input type="hidden" name="p_id" value="<?= $get_id; ?>">
                    <h3>billing details</h3>
                    <div class="flex">
                        <div class="box">
                            <div class="input-field">
                                <p>your name <span>*</span> </p>
                                <input type="text" name="name" required maxlength="50" placeholder="Enter your name" class="input">
                            </div>
                            <div class="input-field">
                                <p>your number <span>*</span> </p>
                                <input type="number" name="number" required maxlength="10" placeholder="Enter your number" class="input">
                            </div>
                            <div class="input-field">
                                <p>your email <span>*</span> </p>
                                <input type="email" name="email" required maxlength="50" placeholder="Enter your email" class="input">
                            </div>
                            <div class="input-field">
                                <p>payment method <span>*</span> </p>
                                <select name="method" class="input">
                                    <option value="cash on delivery">cash on delivery</option>
                                    <option value="credit or debit">credit or debit</option>
                                    <option value="esewa">Esewa</option>
                                    <option value="paytm">paytm</option>
                                </select>
                            </div>
                            <div class="input-field">
                                <p>address type <span>*</span> </p>
                                <select name="address_type" class="input">
                                    <option value="home">Home</option>
                                    <option value="office">Office</option>
                                </select>
                            </div>
                        </div>
                        <div class="box">
                            <div class="input-field">
                                <p>address line 01 <span>*</span> </p>
                                <input type="text" name="flat" required maxlength="50" placeholder="e.g flat or building name" class="input">
                            </div>
                            <div class="input-field">
                                <p>address line 02 <span>*</span> </p>
                                <input type="text" name="street" required maxlength="50" placeholder="e.g street name" class="input">
                            </div>
                            <div class="input-field">
                                <p>city name <span>*</span> </p>
                                <input type="text" name="city" required maxlength="50" placeholder="e.g city name" class="input">
                            </div>
                            <div class="input-field">
                                <p>country name <span>*</span> </p>
                                <input type="text" name="country" required maxlength="50" placeholder="e.g country name" class="input">
                            </div>
                            <div class="input-field">
                                <p>pincode <span>*</span> </p>
                                <input type="number" name="pin" required maxlength="6" min="0" placeholder="e.g 110011" class="input">
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="place_order" class="btn">place order</button>
                </form>
                <div class="summary">
    <h3>Items</h3>
    <div class="box-container">
        <?php
            $grand_total = 0;
            if (isset($_GET['get_id'])) {
                // Split the get_id parameter into an array of product IDs
                $product_ids = explode(',', $_GET['get_id']);
                
                // Generate placeholders for the SQL query
                $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
                
                // Fetch all products matching the given IDs
                $select_get = $conn->prepare("SELECT * FROM `products` WHERE id IN ($placeholders)");
                $select_get->execute($product_ids);

                if ($select_get->rowCount() > 0) {
                    // Display each fetched product
                    while ($fetch_get = $select_get->fetch(PDO::FETCH_ASSOC)) {
                        $sub_total = $fetch_get['price'];
                        $grand_total += $sub_total;
        ?>
                        <div class="flex">
                            <img src="uploaded_files/<?= $fetch_get['image']; ?>" class="image">
                            <div>
                                <h3 class="name"><?= $fetch_get['name']; ?></h3>
                                <p class="price">$<?= $fetch_get['price']; ?></p>
                            </div>
                        </div>
        <?php
                    }
                } else {
                    echo '<p class="empty">No products found for the given IDs!</p>';
                }
            } else {
                // Handle cart-based items if no specific product IDs are provided
                $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                $select_cart->execute([$user_id]);

                if ($select_cart->rowCount() > 0) {
                    while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                        $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                        $select_products->execute([$fetch_cart['product_id']]);
                        $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
                        $sub_total = ($fetch_cart['qty'] * $fetch_products['price']);
                        $grand_total += $sub_total;
        ?>
                        <div class="flex">
                            <img src="uploaded_files/<?= $fetch_products['image']; ?>" class="image">
                            <div>
                                <h3 class="name"><?= $fetch_products['name']; ?></h3>
                                <p class="price">$<?= $fetch_products['price']; ?> X <?= $fetch_cart['qty']; ?></p>
                            </div>
                        </div>
        <?php
                    }
                } else {
                    echo '<p class="empty">Your cart is empty.</p>';
                }
            }
        ?>
    </div>
    <div class="grand-total">
        <span>Total amount payable:</span>
        <p>$<?= $grand_total; ?>/-</p>
    </div>
</div>

        </div>
        <div class="recommendations">
            <div class="heading">
                <h1>Recommended for You</h1>
            </div>
            <div class="recommendation-container">
                <?php if (!empty($recommended_products)) {
                    foreach ($recommended_products as $product) { ?>
                        <div class="product-box">
                            <img src="uploaded_files/<?= $product['image']; ?>" alt="<?= $product['name']; ?>">
                            <h3><?= $product['name']; ?></h3>
                            <p>$<?= $product['price']; ?></p>
                            <a href="view_page.php?pid=<?= $product['id'] ?>" class="btn">View Product</a>
                        </div>
                    <?php }
                } else { ?>
                    <p>No recommendations available at the moment.</p>
                <?php } ?>
            </div>
        </div>
    </div>
   
    

    <!-- sweetalert cdn -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <!-- custom js -->
    <script src="js/user_script.js"></script>

    <?php include 'components/alert.php'; ?>
</body>
</html>
