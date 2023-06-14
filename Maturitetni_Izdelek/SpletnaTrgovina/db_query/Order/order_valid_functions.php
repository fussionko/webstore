<?php
    require_once("countries_list.php");

    function checkValidCountry($country_name)
    {
        global $countries;
        if(empty($country_name) == 1) return 0;
        foreach($countries as $country)
            if($country["name"] == $country_name)
                return 1;
        return 0;
    }

    function getCountryCodeByName($country_name)
    {
        global $countries;
        if(empty($country_name) == 1) return 0;
        foreach($countries as $country)
            if($country["name"] == $country_name)
                return $country["code"];
        return -1;
    }

    function checkValidCityName($city_name)
    {
        if(empty($city_name) == 1) return 0;
        if(preg_match('/^[a-zA-ZčćšžđČŠĆŽĐ]+(?:[\s-][a-zA-ZčćšžđČĆŠŽĐ]+)*$/', $city_name) == 0) return 0;
        return 1;
    }

    function checkValidPostalCode($postal_code)
    {
        if(empty($postal_code) == 1) return 0;
        if(preg_match('/^[0-9]{4}$/', $postal_code) == 0) return 0;
        return 1;
    }

    function checkValidAddressName($address_name)
    {
        if(empty($address_name) == 1) return 0;
        if(preg_match('/[a-zA-ZčćšžđČĆŠŽĐ0-9, ]/', $address_name) == 0) return 0;
        return 1;
    }

    function checkValidPhoneNumber($phone_number)
    {
        if(empty($phone_number) == 1) return 0;
        if(preg_match('/^[0-9]{3}[- ]?[0-9]{3}[- ]?[0-9]{3}$/', $phone_number) == 0) return 0;
        return 1;
    }

    function correctPhoneNumber($phone_number)
    {
        if(empty($phone_number) == 1) return 0;

        $phone_number = str_replace(' ', '', $phone_number);
        $phone_number = str_replace('-', '', $phone_number);
    
        return $phone_number;
    }

    function checkValidCardNumber($card_number)
    {
        if(empty($card_number) == 1) return 0;
        if(preg_match('/^[0-9]{4}[- ]?[0-9]{4}[- ]?[0-9]{4}[- ]?[0-9]{4}$/', $card_number) == 0) return 0;
        return 1;
    }

    function correctCardNumber($card_number)
    {
        if(empty($card_number) == 1) return 0;

        $card_number = str_replace(' ', '', $card_number);
        $card_number = str_replace('-', '', $card_number);

        if(strlen($card_number) != 16) return 0;

        return $card_number;   
    }

    function checkValidCVV($cvv)
    {
        if(empty($cvv) == 1) return 0;
        if(preg_match('/^[0-9]{3}$/', $cvv) == 0) return 0;
        return 1;
    }

    function checkValidCardExpires($card_expires)
    {
        if(empty($card_expires) == 1) return 0;
        $check_arr = explode('/', $card_expires);

        if($check_arr[0] > '12' || $check_arr[0] <= '00') return 0;
        if(intval($check_arr[1]) > 99) return 0;
        if($check_arr[0] < date("m") && $check_arr[1] <= date("y")) return 0;
        if($check_arr[1] < date("y")) return 0;
        if(preg_match('/^[01]{1}[0-9]{1}\/[0-9]{2}$/', $card_expires) == 0) return 0;
        return 1;
    }

    function checkValidCardholderName($cardholder_name)
    {
        if(empty($cardholder_name) == 1) return 0;
        if(preg_match('/^[a-zA-ZčćđšžČĆĐŠŽ. ]+$/', $cardholder_name) == 0) return 0;
        return 1;
    }

    function checkValidDescription($description)
    {
        if(empty($description) == 1) return 0;
        if(strlen($description) >= 45) return 0;
        if(preg_match('/^[a-zA-ZčćđšžČĆĐŠŽ ]*$/', $description) == 0) return 0;
        return 1;
    }

    function checkValidShippingCompanyName($name)
    {
        if(empty($name) == 1) return 0;
        if(preg_match('/^[a-zA-ZčćđšžČĆĐŠŽ -]*$/', $name) == 0) return 0;
        return 1;
    }


?>