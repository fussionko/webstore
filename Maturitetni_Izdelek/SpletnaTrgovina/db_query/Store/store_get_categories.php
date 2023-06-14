<?php
    header('charset=utf-8');
    require_once("store_functions.php");

    echo json_encode(getCategories($_POST["id_item"]));
?>