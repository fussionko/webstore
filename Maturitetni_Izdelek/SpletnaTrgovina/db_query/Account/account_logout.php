<?php
    // Izbrise uporabnika iz seje
    session_start();
    if(isset($_SESSION['username']))
        unset($_SESSION['username']); 
    if(isset($_SESSION['password_hash']))
        unset($_SESSION['password_hash']);
?>