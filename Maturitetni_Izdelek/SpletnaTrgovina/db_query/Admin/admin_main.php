<?php
    require_once("admin_functions.php");

    echo json_encode(insertCategory($_POST["name_category"], $_POST["parent_category"]));
?>