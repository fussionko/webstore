<?php
    require_once("configurator_functions.php");
    require_once("configurator_valid_functions.php");

    if(checkValidNum($_POST["id_build"]) == 0)
    {
        echo -1;
        return;
    }

    if(checkValidDescription($_POST["description"]) == 0)
    {
        echo 0;
        return;
    }
    
    $insert = update_description_in_build($_POST["description"], $_POST["id_build"]);

    if($insert == 1) echo 1;
    else if($insert == 0) echo 0;
    else echo -1;
?>