<?php
    require_once("../connect_db.php");

    function getAllShippingCompanies()
    {
        global $mysqli;
        $sql = $mysqli->query("SELECT name, price, time_to_deliver FROM shipping_company WHERE active = 1");
        
        if($sql->num_rows == 0)
            return -1;

        $rows = [];
        while ($row = $sql->fetch_assoc()) 
            $rows[] = $row;

        return $rows;
    }

    function checkShippingCompanyExists($name)
    {
        global $mysqli;
        $sql = $mysqli->prepare("SELECT * FROM shipping_company WHERE name = ? AND active = 1 LIMIT 1");
        $sql->bind_param('s', $name);
        $sql->execute();
        $sql = $sql->get_result();

        if($sql->num_rows == 0)
            return 0;
        return 1;
    }

?>