<?php
    require_once("order_functions.php");

    if(preg_match('/^[0-9]+$/', $_POST["id_address"]) == 0)
    {
        echo 0;
        return;
    }
    $temp = getAddress($_POST["id_address"]);
    if($temp == -1) echo 0;
    else echo json_encode($temp);
?>