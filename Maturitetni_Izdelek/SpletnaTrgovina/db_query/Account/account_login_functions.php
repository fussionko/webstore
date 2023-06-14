<?php
    include_once("../connect_db.php");
    
    function queryFindUsername($username)
    {
        global $mysqli;
        $sql = $mysqli->prepare("SELECT username FROM user WHERE username = ?");
        $sql->bind_param("s", $username);
        $sql->execute();
        $sql = $sql->get_result();
        
        $username = mysqli_fetch_assoc($sql);
        if($username == NULL)
            return 0;
        return 1;
    }

    function queryCheckMatch($username, $password)
    {
        global $mysqli;
        $sql = $mysqli->prepare("SELECT passwordHash FROM user WHERE username = ?");
        $sql->bind_param("s", $username);
        $sql->execute();

        $user = $sql->get_result();
        $user = $user->fetch_assoc();

        if(empty($user) || !password_verify($password, $user["passwordHash"]))
            return 0;
        return 1;
    }

    function getUserPasswordHash($username)
    {
        global $mysqli;
        $sql = $mysqli->prepare("SELECT passwordHash FROM user WHERE username = ?");
        $sql->bind_param("s", $username);
        $sql->execute();

        $user = $sql->get_result();
        $hash = $user->fetch_assoc();

        if(empty($hash) || count($hash) > 1)
            return 0;
        return $hash["passwordHash"];
    }

    function checkPasswordCorrect($password) 
    {
        $errors = [];
        if(empty($password)){
            $errors[] = "Polje geslo je prazno";
            return $errors;
    } 
        if(empty($errors))
            return 1;
        return $errors;

    }

    function checkUsernameCorrect($username) 
    {
        $errors = [];
        if(empty($username)) 
            return ["Polje uporabniško ime je prazno"];

        if(queryFindUsername($username)==0)
            $errors[] = "Uporabniško ime je napačno";

        if(empty($errors))
            return 1;
        return $errors;
    }

    function checkLogin($username, $password)
    {
        $user = queryCheckMatch($username, $password);

        if($user == 0)
            return ["Uporabniško ime in geslo se neujemata"];

        return 1;
    }

    function checkUserLogin()
    {
        if(!isset($_SESSION['username']) || !isset($_SESSION['password_hash']))
            return 0;

        $check = compareHash($_SESSION['password_hash'], getUserPasswordHash($_SESSION['username']));

        if(queryFindUsername($_SESSION['username']) && $check == 1);
            return 1;
        return 0;
    }

    function getLoginUsername()
    {
        if(!isset($_SESSION['username']) || !isset($_SESSION['password_hash']))
            return 0;
        return $_SESSION['username'];
    }
?>