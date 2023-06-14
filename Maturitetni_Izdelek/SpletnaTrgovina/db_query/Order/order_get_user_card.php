<?php
    require_once("../Payment_processing/payment_functions.php");

    $temp = getCard($_POST["id_card"]);
    if($temp == -1) echo 0;
    else echo json_encode($temp);
?>