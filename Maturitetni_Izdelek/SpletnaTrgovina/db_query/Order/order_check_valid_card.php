<?php
    require_once("order_functions.php");
    require_once("order_valid_functions.php");
    require_once("../Payment_processing/payment_functions.php");

    function main_check_valid_card()
    {
        $errors = [];

        $card_number = checkValidCardNumber($_POST["card_number"]);
        if($card_number == 0) $errors[] = "card_number";

        $card_number = correctCardNumber($_POST["card_number"]);
        if($card_number == 0) $errors[] = "card_number";

        $cv = checkValidCVV($_POST["cvv"]);
        if($cv == 0) $errors[] = "cvv";

        $card_expires = checkValidCardExpires($_POST["card_expires"]);
        if($card_expires == 0) $errors[] = "card_expires";

        $cardholder_name = checkValidCardholderName($_POST["cardholder_name"]);
        if($cardholder_name == 0) $errors[] = "cardholder_name";

        if(isset($_POST["description"]))
        {
            $description = checkValidDescription($_POST["description"]);
            if($description == 0) $errors[] = "description";
        }

        if(count($errors) == 0) return 1;
        return $errors;
    }

    echo json_encode(main_check_valid_card());

?>