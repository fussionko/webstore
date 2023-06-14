<?php
    require_once("cart_functions.php");
    require_once("../Account/account_functions.php");
    require_once("../Account/account_login_functions.php");

    if(checkUserLogin() == 1)
    {
        $username = getLoginUsername();
        if($username === 0)
        {
            echo -1;
            return;
        }
        $cart_id = userCart($username);
 
        if($cart_id === -1)
        {
            echo -1;
            return;
        }
        if(!is_numeric($_POST["quantity"]) || !is_numeric($_POST["id_item"]))
        {
            echo -1;
            return;
        }
   
        if(!updateItemToCart($_POST["id_item"], $cart_id, $_POST["quantity"]))
        {
            echo 0;
            return;
        }
        echo 1;
    }

?>