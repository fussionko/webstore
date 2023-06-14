<?php
    header('charset=utf-8');
    require_once("../Cart/cart_functions.php");
    require_once("../Account/account_login_functions.php");
    require_once("../Account/account_functions.php");

    function main_get_cart()
    {

        $data = -1;
        if(checkUserLogin() == 1)
        {
            $username = getLoginUsername();
            if($username === 0) return -1;
            $id_cart = userCart($username);
            if($id_cart == -1) return 0;
           
            $data = getCartItems($id_cart);
        }
        else return 0;

        if($data == -1) return 0;
        return $data;
    }

    $data = main_get_cart();

    if($data == -1) echo -1;
    else if($data == 0) echo 0;
    else echo json_encode($data);
?>