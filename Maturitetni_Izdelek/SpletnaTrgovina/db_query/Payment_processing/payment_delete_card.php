<?php
    require_once("payment_functions.php");

    if(is_numeric($_POST["id_card"]) == 0)
    {
        echo 0;
        return;
    }

    $success = deletePaymentCard($_POST["id_card"]);
    if($success == 0) echo 0;
    else if($success == 1) echo 1;
?>