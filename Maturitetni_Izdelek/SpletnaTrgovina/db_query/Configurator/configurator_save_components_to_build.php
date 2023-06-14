<?php
    header('charset=utf-8');
    require_once("configurator_functions.php");
    require_once("configurator_valid_functions.php");


    function main_add()
    {
        foreach($_POST["id_components"] as $id_component)
        {
            if(checkValidNum($id_component) == 0) return -1;
            if(add_component_to_build($_POST["id_build"], $id_component) == 0) return -1;
        }
    }

    if(checkValidNum($_POST["id_build"]) == 0)
    {
        echo -1;
        return;
    }

    if(main_add() == -1) echo -1;
    else echo 1;
?>
