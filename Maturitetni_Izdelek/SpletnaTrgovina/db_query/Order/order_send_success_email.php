<?php
    require_once("../Account/account_functions.php");
    require_once("../Account/account_login_functions.php");
    require_once("../Account/account_mail_functions.php");

    if(checkUserLogin() == 1)
    {
        $username = getLoginUsername();
        $email = getEmail($username);
        if($email == -1) 
        {
            echo -1;
            return;
        }
    }
    else $email = $_POST["email"];
    echo sendOrderConformation($email);
?>