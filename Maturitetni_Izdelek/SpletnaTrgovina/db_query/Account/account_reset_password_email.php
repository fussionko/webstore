<?php
    require_once("account_mail_functions.php");

    echo sendRequestPassowordResetMail(file_get_contents("php://input"));
?>