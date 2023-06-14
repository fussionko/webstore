<?php
    require_once("configurator_functions.php");
    require_once("configurator_valid_functions.php");

    if(checkValidNum($_POST["id_build"]) == 0)
    {
        echo -1;
        return;
    }

    if(checkValidNum($_POST["data"]["sum_price"]) == 0)
    {
        echo 0;
        return;
    }

    if(checkValidNum($_POST["data"]["power_usage"]) == 0)
    {
        echo 0;
        return;
    }

    if(checkValidNum($_POST["data"]["heat"]) == 0)
    {
        echo 0;
        return;
    }

    $insert = update_data_in_build($_POST["id_build"], $_POST["data"]["sum_price"], $_POST["data"]["power_usage"], $_POST["data"]["heat"]);

    if($insert == 1) echo 1;
    else if($insert == 0) echo 0;
    else echo -1;
?>