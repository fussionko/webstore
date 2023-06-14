<?php
    header('charset=utf-8');
    require_once("store_functions.php");
    require_once("store_search_parameter_functions.php");
   
    $temp = '';

    if(!isset($_POST["data"]))
    {
        echo 0;
        return;
    }

    if($_POST["data"] == 1)
        $temp = getAllItems();
    else if(urldecode($_POST["data"]["parent_category"]) == 'x')
        $temp = getMainParentItems(urldecode($_POST["data"]["category"]));
    else
        $temp = getParentItems(urldecode($_POST["data"]["category"]), urldecode($_POST["data"]["parent_category"]));

    if($temp == -1)
    {
        echo 0;
        return;
    }

    $temp = sortSearchParams(orderData($temp), $_POST["param"]);
 
    if($temp == 0)
        echo -1;
    else
        echo json_encode($temp, JSON_UNESCAPED_UNICODE);

?>