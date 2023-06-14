<?php
    require_once("order_shipping_company_functions.php");
    require_once("order_valid_functions.php");

    function main_add_shipping()
    {
        $shipping_company = checkValidShippingCompanyName($_POST["shipping_company"]);
        if($shipping_company == 0) return -1;

        $shipping_company_exists = checkShippingCompanyExists($_POST["shipping_company"]);
        if($shipping_company_exists == 0) return -1;

        return 1;
    }

    echo json_encode(main_add_shipping());
?>