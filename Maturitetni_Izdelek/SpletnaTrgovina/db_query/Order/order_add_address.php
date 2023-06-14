<?php
    require_once("../Account/account_functions.php");
    require_once("../Account/account_login_functions.php");

    require_once("order_functions.php");
    require_once("order_valid_functions.php");

    function real_phone_number($phone_number)
    {
        $correct_phone_number = correctPhoneNumber($phone_number);
        if($correct_phone_number == 0) return 0;

        return $phone_number;
    }

    function main_check_valid_address()
    {
        $country_name = checkValidCountry($_POST["country_name"]);
        if($country_name == 0) return 0;

        $city_name = checkValidCityName($_POST["city_name"]);
        if($city_name == 0) return 0;

        $postal_code = checkValidPostalCode($_POST["postal_code"]);
        if($postal_code == 0) return 0;

        $address_name = checkValidAddressName($_POST["address_name"]);
        if($address_name == 0) return 0;

        $phone_num = checkValidPhoneNumber($_POST["telephone_number"]);
        if($phone_num == 0) return 0;

        return 1;
    }

    function main_add_address()
    {
        if(main_check_valid_address() == 0) return -1;
    
        $phone_num = real_phone_number($_POST["telephone_number"]);
        if($phone_num == 0) return -1;

        if(checkUserLogin() == 1)
        {
            $id_address_exists = checkAddressMatch(getLoginUsername(), $_POST["country_name"], $_POST["city_name"],
                                            $_POST["postal_code"], $_POST["address_name"], $phone_num);
            if($id_address_exists != -1) return $id_address_exists;
        }
            
        $id_address = createAddress($_POST["country_name"], $_POST["city_name"],
                                    $_POST["postal_code"], $_POST["address_name"], $phone_num);
        if($id_address == -1) return -1;

        if(checkUserLogin() == 1)
        {
            $username = getLoginUsername();
            if(addUserToAddress($username, $id_address) == 0) return -1;
        }
        return $id_address;
    }

    echo main_add_address();
?>