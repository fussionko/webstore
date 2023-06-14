<?php
    require_once("account_functions.php");
    require_once("account_registration_functions.php");
    require_once("account_mail_functions.php");

    $uCheck = checkUsername($_POST['username']);
    $eCheck = checkEmail($_POST['email']);
    $pCheck = checkPassword($_POST['password']);
    $prCheck = checkRepeatPassword($_POST['password-repeat'], $_POST['password']);
    $nCheck = checkName($_POST['name']);
    $lCheck = checkLastName($_POST['lastname']);  
    $mainCheck = 0;
    
    if($uCheck == 1 && $eCheck == 1 && $pCheck == 1 && $prCheck == 1 && $nCheck == 1 && $lCheck == 1)  
    {
        $mainCheck = addUser($_POST['name'], $_POST['lastname'], $_POST['username'], encryptPassword($_POST['password']), $_POST['email'], $_POST['gender']);
        if($mainCheck==1)
            sendRegistrationConformation($_POST['email'], $_POST['name'], $_POST['gender']);
    }

    $output = [
        "username" => $uCheck,
        "email" => $eCheck,
        "password" => $pCheck,
        "password-repeat" => $prCheck, 
        "name" => $nCheck,
        "lastname" => $lCheck,
        "main" => $mainCheck
    ];

    // Pretvori array v JSON, JSON_UNESCAPED_UNICODE za šumnike
    echo json_encode($output, JSON_UNESCAPED_UNICODE);

?>