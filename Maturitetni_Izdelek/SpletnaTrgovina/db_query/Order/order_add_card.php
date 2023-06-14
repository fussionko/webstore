<?php
    require_once("order_functions.php");
    require_once("order_valid_functions.php");
    require_once("../Payment_processing/payment_functions.php");

    require_once("../Account/account_functions.php");
    require_once("../Account/account_login_functions.php");

    function main_add_card()
    {
        $card_number = checkValidCardNumber($_POST["card_number"]);
        if($card_number == 0) return -1;

        $card_number = correctCardNumber($_POST["card_number"]);
        if($card_number == 0) return -1;

        $cv = checkValidCVV($_POST["cvv"]);
        if($cv == 0) return -1;

        $card_expires = checkValidCardExpires($_POST["card_expires"]);
        if($card_expires == 0) return -1;

        $cardholder_name = checkValidCardholderName($_POST["cardholder_name"]);
        if($cardholder_name == 0) return -1;

        $description = null;
        if(isset($_POST["description"]))
        {
            $description = checkValidDescription($_POST["description"]);
            if($description == 0) return -1;
        }

        if(checkUserLogin() == 1)
        {
            $id_card_exists = getUserCardMatch(getLoginUsername(), $card_number, $_POST["cvv"], $_POST["card_expires"], $_POST["cardholder_name"]);
            if($id_card_exists != -1)
                return $id_card_exists;
        }


        $id_card = insertPaymentCard($card_number, $_POST["cvv"], $_POST["card_expires"], $_POST["cardholder_name"], $description);
        if($id_card == -1) return -1;

        if(checkUserLogin() == 1)
        {
            $username = getLoginUsername();
            if(addUserToPaymentCard($username, $id_card) == 0) return -1;
        }

        return $id_card;
    }

    echo json_encode(main_add_card());

?>