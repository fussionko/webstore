<?php
    header('charset=utf-8');
    require_once("../Cart/cart_functions.php");
    require_once("../Account/account_login_functions.php");
    require_once("../Account/account_functions.php");

    function addProductToCartMain($id_cart, $id_item, $quantity)
    {
        if(checkInCart($id_cart, $id_item) == 1)
        {
            if(increaseQuantityItemInCart($id_item, $id_cart, $quantity) == 0) return 0;
            return 1;
        }

        if(addItemToCart($id_item, $id_cart, $quantity) == 0) return 0;
        return 1;
    }

    function main_add_cart()
    {
        if(checkUserLogin() == 1)
        {
            $username = getLoginUsername();
            $id_cart = userCart($username);
            if($id_cart == -1)
            {
                $id_cart = createUserCart($username);
                if($id_cart == -1) return 0;
            }

            if(addProductToCartMain($id_cart, $_POST["id_item"], $_POST["quantity"]) == 0) return 0;
        }
        return 1;
    }

    if(main_add_cart() == 0) echo 0;
    else echo 1;
?>