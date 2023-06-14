
<?php
    $servername = "localhost";
    $usernameDB = "root";
    $passwordDB = "";
    const dbname = "webstore_ver9_3";
    
    //mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ALL);

    try {
        $mysqli = new mysqli($servername, $usernameDB, $passwordDB, dbname);
        if($mysqli === false)
            throw new Exception("rip");
    } catch (Exception $e) {
        echo '<pre>';
        print_r($e);
        echo '</pre>';
        exit();
    }

$results = $mysqli->query("SET session wait_timeout=28800", FALSE);
// UPDATE - this is also needed
$results = $mysqli->query("SET session interactive_timeout=28800", FALSE);

?>