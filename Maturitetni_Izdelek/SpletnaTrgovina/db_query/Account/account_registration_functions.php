<?php
    function addUser($name, $lastname, $username, $passwordhash, $email, $gender)
    {
        global $mysqli;
        $sql = $mysqli->prepare("INSERT INTO User (name, lastName, username, passwordHash, email, gender)
                                    VALUES (?, ?, ?, ?, ?, ?)
                                ");
        $sql->bind_param("ssssss", $name, $lastname, $username, $passwordhash, $email, $gender);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function checkPassword($password) 
    {
        $errors = [];
        if(empty($password))
            return ["Polje geslo je prazno"];

        if(strlen($password) < 6 && strlen($password) > 30) $errors[] = "Geslo mora biti med 6 in 30 znaki!";

        if (!preg_match("#[0-9]+#", $password)) 
            $errors[] = "Geslo mora vsebovati vsaj eno številko!";
        if (!preg_match("#[A-Z]+#", $password))
            $errors[] = "Geslo mora vsebovati vsaj eno veliko črko!";        

        if(empty($errors))
            return 1;
        return $errors;
    }

    function checkUsername($username) 
    {
        $errors = [];
        if(empty($username))
            return ["Polje uporabniško ime je prazno"];

        if(checkUsernameExists($username))
            return ["To uporabniško ime že obstaja"];

        if(strlen($username) < 3 && strlen($username) > 30) $errors[] = "Uporabniško ime mora biti med 6 in 30 znaki!";

        if(empty($errors))
            return 1;
        return $errors;
    }

    function checkName($name)
    {
        $errors = [];
        if(empty($name))
            return  ["Polje ime je prazno"];

        if(strlen($name) <= 2) $errors[] = "Ime je prekratko!";
        else if(strlen($name) > 40) $errors[] = "Ime je predolgo!";

        if(preg_match("/[a-zA-Z]/", $name) == 0) $errors[] = "Ime vsebuje prepovedane znake!";

        if(empty($errors))
            return 1;
        return $errors;
    }

    function checkLastName($lastname)
    {
        $errors = [];
        if(empty($lastname)){
            $errors[] = "Polje priimek je prazno";
            return $errors;
        }
        if(strlen($lastname) <= 2) $errors[] = "Priimek je prekratko!";
        else if(strlen($lastname) > 40) $errors[] = "Priimek je predolgo!";

        if(preg_match("/[a-zA-Z]/", $lastname) == 0) $errors[] = "Priimek vsebuje prepovedane znake!";

        if(empty($errors))
            return 1;
        return $errors;
    }

    function checkRepeatPassword($password_repeat, $password) 
    {
        $errors = [];
        if($password == $password_repeat && !empty($password))
            return 1;
        $errors[] = "Gesli se neujemata";
        return $errors;
         
    }

    function checkEmail($email)
    {
        $errors = [];
        if(empty($email)) return ["Polje email je prazno"];
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email je napačno napisan";
        if(checkEmailExists($email)) return ["Email naslov je že v uporabi"];
        if(empty($errors))
            return 1;
        return $errors;
    }

    function checkRepeatEmail($email_repeat, $email)
    {
        $errors = [];
        if($email == $email_repeat && !empty($email))
            return 1;
        $errors[] = "Email naslova se neujemata";
        return $errors;
    }

    function checkUsernameExists($username)
    {
        global $mysqli;
        $sql = $mysqli->prepare("SELECT username FROM user WHERE username = ?");
        $sql->bind_param("s", $username);
        $sql->execute();
        $sql = $sql->get_result();
        
        if($sql->num_rows == 0)
            return 0;
        return 1;
    }

    function checkEmailExists($email)
    {
        global $mysqli;
        $sql = $mysqli->prepare("SELECT email FROM user WHERE email = ?");
        $sql->bind_param("s", $email);
        $sql->execute();
        $sql = $sql->get_result();
        
        if($sql->num_rows == 0)
            return 0;
        return 1;
    }
?>