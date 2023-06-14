<?php
    require_once("../Account/account_login_functions.php");
    require_once("../Account/account_functions.php");
    require_once("../Store/store_functions.php");

    function main_validate_cart()
    {
        if(checkUserLogin() == 0)
            foreach($_POST["temp_cart"] as $id_item => $quantity)
                if(checkItemExists($id_item) == 0) return 0;
        return 1;
    }

    if(main_validate_cart() == 0) echo 0;
    else echo 1;
?>