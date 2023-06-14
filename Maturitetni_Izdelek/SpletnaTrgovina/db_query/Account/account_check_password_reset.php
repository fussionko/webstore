<?php
    require_once("account_functions.php");

    echo checkPasswordReset(file_get_contents("php://input"));
?>