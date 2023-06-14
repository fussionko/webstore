<?php
    require_once("../Account/account_login_functions.php");
    require_once("../Account/account_functions.php");

    $output = checkUserLogin();

    echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>