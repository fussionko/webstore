<?php
    require_once("cart_functions.php");
    require_once("../Account/account_functions.php");
    require_once("../Account/account_login_functions.php");

    if(checkUserLogin() == 1)
    {
        $username = getLoginUsername();
        $cart_id = userCart($username);
        if($cart_id == -1)
        {
            echo -1;
            return;
        }

        echo json_encode(getCartItemsOrder($cart_id));
    }
    else echo -1;
?>