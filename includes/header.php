<!-- Header Section -->
<link rel="stylesheet" href="../css/template.css">
<link rel="stylesheet" href="../css/user_style.css">


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
        <script>
            const userBtn = document.getElementById('user-btn');
            const profileDetail = document.querySelector('.profile-detail');

            // Add click event listener to the user button
            userBtn.addEventListener('click', function() {
                // Toggle the display property of the profile detail div
                if (profileDetail.style.display === 'block') {
                    profileDetail.style.display = 'none'; // Hide the div
                } else {
                    profileDetail.style.display = 'block'; // Show the div
                }
            });
        </script>
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
        <a href="../admin panel/login.php">SELL YOUR ITEMS</a>
        
    </div>
    <form action="../pages/search_product.php" method="post" class="search-form">
            <input type="text" name="search_product" placeholder="Search Product.." required maxlength="100">
            <button type="submit" class="bx bx-search-alt-2" id="search_product_btn"></button>
    </form>
</div>

