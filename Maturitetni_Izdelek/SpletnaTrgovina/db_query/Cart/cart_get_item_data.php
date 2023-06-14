<?php
    require_once("cart_functions.php");

    function main_get_temp_cart_items()
    {
        $data = $_POST["data"];
        if(count($data) == 0) return -1;
        $cart_items = getTempCartItems($data);
        if($cart_items == -1) return 0;
        return $cart_items;
    }

    $check = main_get_temp_cart_items();
    if($check == -1) echo -1;
    else echo json_encode($check);
?>