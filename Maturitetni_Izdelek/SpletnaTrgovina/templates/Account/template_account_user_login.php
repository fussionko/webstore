<?php
    require_once("../../db_query/Account/account_login_functions.php");
    echo checkUserLogin();
    if(checkUserLogin())
    {
        echo '<li id="button-logout">Odjava</li>';
        echo '<li>Racun</li>';
    }
    else
    {
        echo '<li><a href="../pages/account.php?login=n">Prijava</a></li>';
        echo '<li><a href="../pages/account.php?reg=y">Registracija</a></li>';
    }
?>