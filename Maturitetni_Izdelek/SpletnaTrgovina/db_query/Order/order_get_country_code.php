<?php
    require_once("order_valid_functions.php");

    if(checkValidCountry($_POST["country_name"]) == 0) echo 0;
    else echo getCountryCodeByName($_POST["country_name"]);
?>