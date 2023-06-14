<?php
    require_once("order_shipping_company_functions.php");
    require_once("order_valid_functions.php");

    function main_check_valid_shipping()
    {
        $errors = [];

        $shipping_company = checkValidShippingCompanyName($_POST["shipping_company"]);
        if($shipping_company == 0) $errors[] = "shipping_company";

        $shipping_company_exists = checkShippingCompanyExists($_POST["shipping_company"]);
        if($shipping_company_exists == 0) $errors[] = "shipping_company";

        if(count($errors) == 0) return 1;
        return $errors;
    }

    echo json_encode(main_check_valid_shipping());
?>