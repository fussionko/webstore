<?php
    require_once("store_functions.php");

    $data = [];
    $data["data"] = getItemData($_POST["id_item"]);
    $data["images"] = getItemImages($_POST["id_item"]);
    $data["attributes"] = getItemAttributesData($_POST["id_item"]);

    echo json_encode($data);
?>