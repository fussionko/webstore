<div class="logo-top">
    <a href="../pages/index.php">
        <img src="../images/site_image/home.jpg" alt="Home">
    </a>
</div>
<div class="navbar-top">
    <nav>
        <ul>
            <li>
                <a href="../pages/index.php" class="dot"> 
                    <img src="../images/site_image/home.jpeg" alt="HOME" class="user-top">
                    DOMOV 
                </a>
            </li>
            <li>
                <a href="../pages/webstore.php" class="dot"> 
                    <img src="../images/site_image/store.jpeg" alt="STORE" class="user-top">
                    TRGOVINA 
                </a>
                <ul>
                    <?php
                        require_once("../../db_query/querys.php");
                        $result_category = getCategory('all');
                        if(count($result_category) > 0)
                            foreach($result_category as $cat) // shrani podatke iz vrstice v row[]
                                echo '<li><a class="click_top">'.$cat["name_category"].'</a></li>';
                        else
                            echo '<li>'.'Ni kategorij'.'</li>';
                    ?>
                </ul>
            </li>
            <li>
                <a href="../pages/configurator.php" class="dot"> 
                    <img src="../images/site_image/build.jpeg" alt="Builder" class="user-top">
                    SESTAVLJALNIK
                </a>
            </li>
            <li>
                <a href="../pages/cart.php" class="dot"> 
                    <img src="../images/site_image/shopping_cart.jpeg" alt="CART" class="user-top">
                    KOŠARICA 
                </a>
                <ul id="cart-list">
                          
                </ul>
            </li>
            <li>
                <a href="../pages/account.php" class="dot">
                    <img src="../images/site_image/user_acc.jpeg" alt="UP" class="user-top">
                    RAČUN
                    <ul id="account-list">
                    </ul>
                </a>
            </li>
        </ul>
    </nav>
</div>