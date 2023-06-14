<?php
    require_once("configurator_functions.php");
    require_once("configurator_valid_functions.php");

    if(checkValidNum($_POST["id_build"]) == 0)
    {
        echo -1;
        return;
    }

    if(update_name_in_build($_POST["name"], $_POST["id_build"]) == 1) echo 1;
    else echo -1;
?>