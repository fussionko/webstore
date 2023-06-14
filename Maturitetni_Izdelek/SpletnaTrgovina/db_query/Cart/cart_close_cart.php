<?php
    require_once("cart_functions.php");
    require_once("../Account/account_functions.php");
    require_once("../Account/account_login_functions.php");

    if(checkUserLogin() == 1)
    {
        $username = getLoginUsername();
        if(closeCart($username) == 0) echo 0;
        else echo 1;
    }

?>