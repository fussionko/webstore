<?php
    require_once("account_login_functions.php");
    require_once("account_functions.php");
    
    $uCheck = checkUsernameCorrect($_POST['username']);
    $pCheck = checkPasswordCorrect($_POST['password']);
    $mCheck = 0;

    if($uCheck == 1 && $pCheck == 1)
        $mCheck = checkLogin($_POST['username'], $_POST['password']);

    if($mCheck == 1)
        addUserToSession($_POST['username'], getUserPasswordHash($_POST['username']));



    //Obvezno array
    $output = ["username" => $uCheck, "password" => $pCheck, "main" => $mCheck];

    // Pretvori array v JSON, JSON_UNESCAPED_UNICODE za šumnike
    echo json_encode($output, JSON_UNESCAPED_UNICODE);

?>