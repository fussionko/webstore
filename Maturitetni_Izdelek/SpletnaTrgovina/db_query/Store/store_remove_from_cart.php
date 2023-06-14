<?php
    header('charset=utf-8');
    require_once("../Cart/cart_functions.php");
    require_once("../Account/account_login_functions.php");
    require_once("../Account/account_functions.php");

    function main_remove_cart_item()
    {
        $id_cart = -1;
        if(checkUserLogin() == 1)
        {
            $username = getLoginUsername();
            if($username === 0) return 0;
            $id_cart = userCart($username);
            if($id_cart == -1) return 0;
        }
        else return 0;

        if(checkInCart($id_cart, $_POST["id_item"]) == 0) return 0;
        if(removeItemFromCart($_POST["id_item"], $id_cart) == 0) return 0;
        return 1;
    }

    if(main_remove_cart_item() == 0) echo 0;
    else echo 1;
?>