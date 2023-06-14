<?php
    require_once("admin_functions.php");

    $table_name = json_decode(file_get_contents('php://input'));
    $table_name = json_decode(json_encode($table_name), true);

    if(is_array($table_name))
        echo json_encode(getTableAttributes($table_name["table"]));
    else
        echo json_encode("error table GET not set");
?>