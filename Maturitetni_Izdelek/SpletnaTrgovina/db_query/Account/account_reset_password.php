<?php
    require_once("account_functions.php");
    require_once("account_registration_functions.php");

    
    $pCheck = checkPassword($_POST["password-reset"]);
    $prCheck = checkRepeatPassword($_POST["password-reset-repeat"], $_POST["password-reset"]);
    $mainCheck = 0;

    if($pCheck == 1 && $prCheck == 1) 
    {
        $mainCheck = replacePassword($_POST["email"], encryptPassword($_POST["password-reset"]));
        if($mainCheck)
        {
            $reset_email = removeResetEmail($_POST["email"]);
            if(!$reset_email)
                $mainCheck = $reset_email;
        }
    }

    $output = [
        "password-reset" => $pCheck,
        "password-reset-repeat" => $prCheck, 
        "main" => $mainCheck,
    ];

    // Pretvori array v JSON, JSON_UNESCAPED_UNICODE za šumnike 
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>