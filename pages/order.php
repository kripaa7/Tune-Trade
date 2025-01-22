<?php
    include('../components/connect.php');

    //userid
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
        header('location:login.php');
    }

    if (isset($_POST['delete_order'])) {
        $order_id = $_POST['order_id'];
        $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
        $delete_order->execute([$order_id]);
        header('location: order.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Order Page</title>
    <link rel="stylesheet" href="../css/user_style.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <link rel="icon" href="../images/sources/logo.svg">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        .container {
            display: grid;
            /* grid-template-columns: repeat(12, 1fr); */
            grid-template-columns: repeat(auto-fit, minmax(20rem, 1fr));
            grid-template-rows: 3rem 5rem 30rem;
            background-color: white;
        }
        .orders{
            /* padding: 5% 8%; */
            grid-area: 3/1/3/13;
        }
        
    </style>
</head>
<body>
    <div class="container">
            <?php include '../includes/header.php' ?>
        <div class="orders">
        <div class="heading">
                <h1>my orders</h1>
    </div>
        
    
        <div class="box-container">
        <?php
            $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? ORDER BY
                date DESC");
            $select_orders->execute([$user_id]);
            if ($select_orders->rowCount() > 0) {
                while($fetch_orders = $select_orders->fetch (PDO:: FETCH_ASSOC)){
                    $product_id = $fetch_orders['product_id'];
                    $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                    $select_products->execute([$product_id]);
                    if ($select_products->rowCount() > 0) {
                        while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
        ?>
                        <div class="box" <?php if($fetch_orders['status'] == 'canceled') {echo 'style ="border:2px solid red"';} ?>>
                            <a href="view_order.php?get_id=<?= $fetch_orders['id']; ?>">
                            <img src="../uploaded_files/<?= $fetch_products['image'] ?>" class="image">
                            <p class="date"> <i class="bx bxs-calender-alt"></i> <?= $fetch_orders['date'];?></p>
                            <div class="content">
                                
                                <div class="row">
                                    <h3 class="name"><?= $fetch_products['name'] ?></h3>
                                    <p class="price">Price: <?= $fetch_products['price'] ?>/-</p>
                                    <p class="status" style="color:<?php if($fetch_orders['status'] == 'delivered'){echo "green";}elseif($fetch_orders['status'] == 'cancled'){echo "red";}else{echo "orange";} ?>"><?= $fetch_orders['status']; ?></p>
                                </div>
                            </div>
                            </a>
                            <!-- Delete Button Form -->
                            <?php if ($fetch_orders['status'] != 'canceled') { ?>
                                <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this order?');" style=" align-items: center; justify-content: center;">
                                    <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                                    <button type="submit" name="delete_order" class="btn" style="padding: 15px; margin-bottom: 15px; margin-left: 12rem; ">Delete Order</button>
                                </form>
                            <?php } ?>
                        </div>
        <?php
                        }
                    }
                }
            }else{
                echo '<p class="empty"> no orders placed </p>';
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