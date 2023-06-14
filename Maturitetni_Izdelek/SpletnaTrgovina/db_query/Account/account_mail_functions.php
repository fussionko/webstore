<?php
    const send_email = "pctrgovinavegova@gmail.com";

    function sendRequestPassowordResetMail($reciever_email)
    {
        if (!filter_var($reciever_email, FILTER_VALIDATE_EMAIL)) return "Napačna oblika email naslova";

        $subject = "Ponastavitev gesla";
        $message = '<html><body>';
        $message .= '<span>Pozdravljeni,</span><br>';
        $message .= '<p>Če želite ponastaviti geslo klikna na naslednji link</p>';
        $message .= '<p><a href ="http://localhost/Maturitetni_Izdelek/SpletnaTrgovina/pages/account.php?password-reset='.$reciever_email.'">ponastavitev</a></p>';
        $message .= '</body></html>';
        $headers = "From: ".send_email."\r\n";
        $headers .= "CC: ".$reciever_email."\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        return mail($reciever_email, $subject, $message, $headers);
    }

    function sendRegistrationConformation($reciever_email, $name, $gender)
    {
        if (!filter_var($reciever_email, FILTER_VALIDATE_EMAIL)) return "Napačna oblika email naslova";

        $subject = "Uspešna registracija!";
        $message = '<html><body>';
        if($gender == "man") 
            $message .= '<span>Pozdravljen g.'.$name.',</span><br>';
        else if($gender == "woman")
            $message .= '<span>Pozdravljena go.'.$name.',</span><br>';
        else 
            $message .= '<span>Pozdravljeni '.$name.',</span><br>';
        $message .= '<p>Hvala za registracijo na naši spletni strani</p>';
        $message .= '</body></html>';
        $headers = "From: ".send_email."\r\n";
        $headers .= "CC: ".$reciever_email."\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        return mail($reciever_email, $subject, $message, $headers);
    }

    function sendOrderConformation($reciever_email)
    {
        if (!filter_var($reciever_email, FILTER_VALIDATE_EMAIL)) return 0;

        $subject = "Naročilo je bilo uspešno oddano";
        $message = '<html><body>';
        // if($gender == "man") 
        //     $message .= '<span>Pozdravljen g.'.$name.',</span><br>';
        // else if($gender == "woman")
        //     $message .= '<span>Pozdravljena go.'.$name.',</span><br>';
        // else 
        //     $message .= '<span>Pozdravljeni '.$name.',</span><br>';
        $message .= '<p>Hvala</p>';
        $message .= '</body></html>';
        $headers = "From: ".send_email."\r\n";
        $headers .= "CC: ".$reciever_email."\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        return mail($reciever_email, $subject, $message, $headers);
    }
?>