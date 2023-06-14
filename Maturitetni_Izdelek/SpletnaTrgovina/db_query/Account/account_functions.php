<?php
    include_once("../../db_query/connect_db.php");

    session_start();

    function encryptPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    
    function checkUserAdmin()
    {
        if(isset($_SESSION['username']))
            for($i=0; $i<count(admin_table); $i++)
                if($_SESSION['username'] == admin_table[$i])
                    return 1;
        return 0;
    }

    function addUserToSession($username, $password_hash)
    {
        $_SESSION['username'] = $username;
        $_SESSION['password_hash'] = $password_hash;
    }

    function compareHash($h1, $h2)
    {
        if($h1 === $h2)
            return 1;
        return 0;
    }

    function sessionLoginData()
    {
        if(isset($_SESSION['username']) && isset($_SESSION['password_hash']))
            return 1;
        return 0;
    }

    function validateEmail($string)
    {
        if(filter_var($string, FILTER_VALIDATE_EMAIL))
            return 1;
        return 0;
    }

    function getEmail($username)
    {
        global $mysqli;
        $sql = $mysqli->prepare("SELECT email FROM user WHERE username = ? LIMIT 1");
        $sql->bind_param("s", $username);
        $sql->execute();
        $sql = $sql->get_result();

        if($sql->num_rows == 0)
            return -1;

        $sql = $sql->fetch_assoc();
        return $sql["email"];
    }

    function getUsername($email)
    {
        global $mysqli;
        $sql = $mysqli->prepare("SELECT username FROM user WHERE email = ? LIMIT 1");
        $sql->bind_param("s", $email);
        $sql->execute();
        $sql = $sql->get_result();

        if($sql->num_rows == 0)
            return 0;

        $username = $sql->fetch_row();
        return $username[0];
    }

    function addResetEmail($email)
    {
        if(!validateEmail($email)) return "Napačna oblika email naslova";

        global $mysqli;

        $username = getUsername($email);
        if($username == 0) return 0;

        $sql = $mysqli->prepare("INSERT INTO password_reset(user_username) VALUES (?)");
        $sql->bind_param("s", $username);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function removeResetEmail($email)
    {
        if(!validateEmail($email)) return "Napačna oblika email naslova";
        global $mysqli;

        $username = getUsername($email);
        if($username == 0) return 0;

        $sql = $mysqli->prepare("UPDATE password_reset SET active = 0 WHERE user_username = ? AND active = 1");
        $sql->bind_param("s", $username);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function checkPasswordReset($check)
    {
        if(!validateEmail($check)) return "Napačna oblika email naslova";

        global $mysqli;
        $sql = $mysqli->prepare("SELECT * FROM user WHERE email = ? LIMIT 1");
        $sql->bind_param('s', $check);
        $sql->execute();
        $sql = $sql->get_result();

        if($sql->num_rows == 0)
            return 0;
        return 1;
    }

    function replacePassword($email, $password_hash)
    {
        global $mysqli;
        $sql = $mysqli->prepare("UPDATE user SET passwordHash = ? WHERE email = ?");
        $sql->bind_param("ss", $password_hash, $email);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function checkPasswordResetEmail($email)
    {
        $errors = [];
        if(empty($email)) return ["Polje email je prazno"];
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email naslov je napačno napisan";
        if(!checkEmailExists($email)) return ["Uporabnik z tem email naslovom neobstaja"];
        if(empty($errors))
            return 1;
        return $errors;
    }

    function userCart($username)
    {
        global $mysqli;
        $sql = $mysqli->prepare("SELECT idcart FROM user INNER JOIN cart ON(username = user_username) WHERE username = ? AND cart.active = 1 LIMIT 1");
        $sql->bind_param("s", $username);
        $sql->execute();
        $sql = $sql->get_result();

        if($sql->num_rows == 0)
            return -1;

        $sql = $sql->fetch_assoc();
        return $sql["idcart"];
    }

    function getUserAddresses($username)
    {
        global $mysqli;
        $sql = $mysqli->prepare("SELECT id_address, country, city, postal_code, address, telephone_number FROM address WHERE user_username = ?");
        $sql->bind_param("s", $username);
        $sql->execute();
        $sql = $sql->get_result();

        if($sql->num_rows == 0)
            return -1;

        $rows = [];
        while ($row = $sql->fetch_assoc()) 
            $rows[] = $row;
        
        return $rows;
    }

    function checkAddressMatch($username, $country, $city, $postal_code, $address, $telephone_number)
    {
        global $mysqli;
        $sql = $mysqli->prepare("SELECT id_address  FROM address WHERE country = ? AND city = ? AND postal_code = ? AND address = ? AND telephone_number = ? AND user_username = ? LIMIT 1");
        $sql->bind_param("ssisss", $country, $city, $postal_code, $address, $telephone_number, $username);
        $sql->execute();
        $sql = $sql->get_result();

        if($sql->num_rows == 0)
            return -1;

        $sql = $sql->fetch_assoc();
        return $sql["id_address"];
    }

    function getUserCards($username)
    {
        global $mysqli;
        $sql = $mysqli->prepare("SELECT id_payment_card, card_number, cvv, card_expires, cardholder_name, description FROM payment_card WHERE user_username = ?");
        $sql->bind_param("s", $username);
        $sql->execute();
        $sql = $sql->get_result();

        if($sql->num_rows == 0)
            return -1;

        $rows = [];
        while ($row = $sql->fetch_assoc()) 
            $rows[] = $row;
        
        return $rows;
    }

    function getUserCardMatch($username, $card_number, $cvv, $card_expires, $cardholder_name)
    {
        global $mysqli;
        $sql = $mysqli->prepare("SELECT id_payment_card FROM payment_card WHERE card_number = ? AND cvv = ? AND card_expires = ? AND cardholder_name = ? AND user_username = ? LIMIT 1");
        $sql->bind_param("sisss", $card_number, $cvv, $card_expires, $cardholder_name, $username);
        $sql->execute();
        $sql = $sql->get_result();

        if($sql->num_rows == 0)
            return -1;

        $sql = $sql->fetch_assoc();
        return $sql["id_payment_card"];
    }
?>