<?php
    require_once("order_functions.php");
    require_once("order_valid_functions.php");

    function real_phone_number($phone_number, $country_name)
    {
        $country_code = getCountryCodeByName($country_name);
        if($country_code == -1) return 0;

        $correct_phone_number = correctPhoneNumber($phone_number);
        if($correct_phone_number == 0) return 0;

        $phone_number = $country_code.'-'.$correct_phone_number;
        return $phone_number;
    }

    function main_check_valid_address()
    {
        $errors = [];

        $country_name = checkValidCountry($_POST["country_name"]);
        if($country_name == 0) $errors[] = "country_name";

        $city_name = checkValidCityName($_POST["city_name"]);
        if($city_name == 0) $errors[] = "city_name";

        $postal_code = checkValidPostalCode($_POST["postal_code"]);
        if($postal_code == 0) $errors[] = "postal_code";

        $address_name = checkValidAddressName($_POST["address_name"]);
        if($address_name == 0) $errors[] = "address_name";

        $phone_num = checkValidPhoneNumber($_POST["telephone_number"]);
        if($phone_num == 0) $errors[] = "telephone_number";

        $phone_num = real_phone_number($_POST["telephone_number"], $_POST["country_name"]);
        if($phone_num == 0) $errors[] = "telephone_number";

        if(isset($_POST["email"]))
            if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
            {

            }else $errors[] = "email";

        if(count($errors) == 0) return 1;
        return $errors;
    }

    echo json_encode(main_check_valid_address());

?>