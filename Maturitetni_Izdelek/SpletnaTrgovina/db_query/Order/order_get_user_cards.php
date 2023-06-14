<?php
    require_once("../Account/account_functions.php");
    require_once("../Account/account_login_functions.php");

    if(checkUserLogin() == 1)
    {
        $username = getLoginUsername();
        $data = getUserCards($username);
        if($data == -1) echo 0;
        else echo json_encode($data);
    }
    else echo -1;
    
?>