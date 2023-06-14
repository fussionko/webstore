<?php
    require_once("admin_functions.php");

    if(isset($_POST["table_name"]) && isset($_POST["table_atr"]))
        echo json_encode(["main" => insertData($_POST["table_name"], $_POST["table_atr"])]);
    else
        echo json_encode("not set");
?>