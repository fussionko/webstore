<?php
    header('Content-Type: text/html; charset=utf-8');
    require_once("store_functions.php");
    require_once("store_search_parameter_functions.php");


    $search_query = urldecode($_POST["data"]);
    $search_query = '%'.$search_query.'%';
 
    $data = getSearchItems($search_query);

    if($data == -1)
    {
        echo 0;
        return;
    }

    $data = sortSearchParams(orderData($data), $_POST["param"]);
 
    if($data == 0)
        echo -1;
    else
        echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>