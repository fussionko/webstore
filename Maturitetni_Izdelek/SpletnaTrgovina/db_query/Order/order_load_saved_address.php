<?php
    require_once("order_functions.php");

    if(preg_match('/^[0-9]+$/', $_POST["id_address"]))
    $address_table = getAddress($_POST["id_address"]);
    if($address_table == 0) echo 0;
    else echo json_encode($address_table);
?>