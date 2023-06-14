<?php
    require_once("order_functions.php");
    require_once("../Account/account_login_functions.php");
    require_once("order_valid_functions.php");
    require_once("../Account/account_functions.php");
    require_once("../Store/store_functions.php");

    calculateTotalPriceInCart($_POST["cart"]);

    function add_items_to_order($data, $id_order)
    {
        $temp_sum = 0;
        foreach($data as $id_item => $quantity)
        {
            if(addProductToOrder($id_item, $id_order, $quantity) == 0) return -1;
            $price = floatval(getItemPrice($id_item));
            if($price == -1) return -1;
            $temp_sum += $price * floatval($quantity);
        }
        return $temp_sum;
    }

    function main_create_order()
    {
        $id_order = -1;
        $id_order = createOrder($_POST["shipping_name"], $_POST["id_address"]);
        
        if($id_order == -1) return -1;

        $order_sum = add_items_to_order($_POST["cart"], $id_order);
        if($order_sum == -1) return -1;

        if(updateOrderSum($id_order, $order_sum) == 0)
            if(updateOrderSum($id_order, $order_sum) == 0) return -1;

        if(checkUserLogin() == 1)
            if(addUserToOrder(getLoginUsername(), $id_order) == 0) return -1;

        return $id_order;
    }

    $id_order = main_create_order();
    if($id_order == -1) echo -1;
    else echo $id_order;
?>