<header>
    <div class="logo">
        <div id="Brand">
            <span style="color: #AA5757; font-size: 20px; font-family: Poppins; font-weight: 600; letter-spacing: 1px;">TUNE </span>
            <span style="color: #6A826A; font-size: 20px; font-family: Poppins; font-weight: 600; letter-spacing: 1px;">TRADE</span>
        </div>
        <div>
            <span style="color: #6A826A; font-size: 20px; font-family: Poppins; font-weight: 400; letter-spacing: 1px; position:absolute; right: 2rem;"><a href="../pages/index.php">Buyer Portal</a></span>

        </div>
    </div>
    <!-- <div class="logo">
        <img src="../images/sources/logo.svg">
    </div> -->
    <div class="right">
        <div class="bx bxs-user" id="user-btn"></div>
        <div class="toggle-btn"><i class="bx bx-menu"></i></div>
    </div> 
    <div class="profile-detail">
        <?php 
            $select_profile = $conn->prepare("SELECT * FROM `sellers` WHERE id = ?");
            $select_profile->execute([$seller_id]);

            if ($select_profile->rowCount() > 0) {
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="profile">
            <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" class="logo-img" width="100">
            <p><?= $fetch_profile['name'];?></p>
            <div class="flex-btn">
                <a href="profile.php" class="btn">Profile</a>
                <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');" class="btn">Logout</a>
            </div>
        </div>
        <?php } ?>
    </div>
</header>
<div class="sidebar-container">
    <div class="sidebar">
    <?php
        $select_profile = $conn->prepare("SELECT * FROM `sellers` WHERE id = ?");
        $select_profile->execute([$seller_id]);

        if ($select_profile->rowCount() > 0) {
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
    ?>
    <div class="profile">
        <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" class="logo-img" width="100" >
        <p><?= $fetch_profile['name']; ?></p>
    </div>
    <?php } ?>
    <h5>menu</h5>
    <div class="navbar">
        <ul>
            <li><a href="dashboard.php"><i class="bx bxs-home-smile"></i>dashboard</a></li>
            <li><a href="add_products.php"><i class="bx bxs-shopping-bags"></i>add products</a></
            li>
            <li><a href="view_product.php"><i class="bx bxs-food-menu"></i>view product</a></li>
            <li><a href="user_accounts.php"><i class="bx bxs-user-detail"></i>accounts</a></li>
            <li><a href="../components/admin_logout.php" onclick="return confirm('Logout from this website');"><i class="bx bxs-log-out"></i> logout</a></li>
        </ul>
    </div>

    </div>
</div>