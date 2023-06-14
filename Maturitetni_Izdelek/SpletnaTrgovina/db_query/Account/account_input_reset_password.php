<?php
    require_once("account_functions.php");
    require_once("account_registration_functions.php");

    
    $emailCheck = checkPasswordResetEmail($_POST["email"]);
    $emailrCheck = checkRepeatEmail($_POST["email-repeat"], $_POST["email"]);
    $mainCheck = 0;

    if($emailCheck == 1 && $emailrCheck == 1) 
    {
        $mainCheck = checkEmailExists($_POST["email"]); // če je v bazi
        if($mainCheck)
        {
            $reset_email = addResetEmail($_POST["email"]); // doda ga v reset tabelo;
            if(!$reset_email)
                $mainCheck = $reset_email;
        }
    }

    $output = [
        "email" => $emailCheck,
        "email-repeat" => $emailrCheck, 
        "main" => $mainCheck
      ];

    // Pretvori array v JSON, JSON_UNESCAPED_UNICODE za šumnike 
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>