<?php
    require_once("admin_functions.php");
    if(isset($_GET["name_parent_category"]))
        echo json_encode(getItemsToCategory($_GET["name_parent_category"]), JSON_UNESCAPED_UNICODE);
    else
        echo "not set";
?>