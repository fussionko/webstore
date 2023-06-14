<?php
    header('charset=utf-8');
    require_once("configurator_functions.php");

    echo getComponentImage($_POST["id_component"]);
?>