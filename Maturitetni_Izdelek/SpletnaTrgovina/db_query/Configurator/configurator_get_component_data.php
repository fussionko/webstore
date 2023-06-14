<?php
    header('charset=utf-8');
    require_once("configurator_functions.php");

    echo json_encode(getComponentData($_POST["id_component"]));
?>