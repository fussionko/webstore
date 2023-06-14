<?php
    header('charset=utf-8');

    require_once("../../db_query/connect_db.php");

    function insertCategory($name_category, $name_parent_category)
    {
        global $mysqli;
        $sql_insert = $mysqli->prepare("INSERT INTO Category (name_category, name_parent_category) VALUES (?, ?)");
        $sql_insert->bind_param("ss", $name_category, $name_parent_category);
        
        try {
            if($sql_insert === false)
                throw new Exception("Error, could not process data submitted.");
            $sql_insert->execute();
    
            if ($sql_insert === false ) 
                throw new Exception("Error, count not execute database query.");
        }
        
        catch(Exception $e) {
            return;
        }
        return 0;      
    }

    function getTableNames()
    {
        global $mysqli; 
        $names = $mysqli->query("SHOW TABLES") or die($mysqli->error);
        $rows = [];
        while ($row = $names->fetch_assoc()) {
            $rows[] = $row["Tables_in_".dbname];
        }
        return $rows;
    }

    function loadTables($tables)
    {
        foreach($tables as $table)
        {
            echo '<li class="overview-item"><span>'.$table.'</span>';
            echo '</li>';
        }
    }

    // Vrne ime, podatkovni tip in če je lahko null
    function getTableAttributes($table)
    {
        global $mysqli;
        $database = dbname; // bind_param nedela za klic po referenci

        try {
            $attributes = $mysqli->prepare("SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE FROM INFORMATION_SCHEMA.COLUMNS
                                            WHERE TABLE_SCHEMA=? AND TABLE_NAME=?");
            if($attributes === false)
                throw new Exception("Error");                                
                                            
            $attributes->bind_param("ss", $database, $table);
            if($attributes === false)
                throw new Exception("Error, could not process data submitted.");

            $attributes->execute();
    
            if($attributes === false)
                throw new Exception("Error, count not execute database query.");
        }
        
        catch(Exception $e) {
            return($e->getMessage());
        }

        $attributes = $attributes->get_result();
        while ($row = $attributes->fetch_assoc()) 
            $rows[] = $row;
        return $rows; 
    }

    function getTableData($table)
    {
        global $mysqli;
        try {
            $q = "SELECT * FROM $table";
            $sql = $mysqli->query($q);
            if($sql === false)
                throw new Exception($mysqli->error);
        }
        catch(Exception $e) {
            return $e->getMessage();
        }

        $rows = [];
        while ($row = $sql->fetch_assoc()) 
            $rows[] = $row;
        return $rows; 
    }

    function insertData($table_name, $table_atr)
    {
        global $mysqli;

        $sql = "INSERT INTO $table_name (";

        foreach($table_atr as $key => $value)
            $sql .= $key . ', ';

        $sql = substr($sql, 0, -2);
        $sql .= ") VALUES("; 

        foreach($table_atr as $value)
        {
            if(is_string($value) && $value != "NULL")
                $sql .= "'"."$value"."', ";
            else
                $sql .= "$value, ";
        }

        $sql = substr($sql, 0, -2);
        $sql .= ')';
        
        if ($mysqli->query($sql) === TRUE)
            return 1;
        return "Error: " . $sql .' '. $mysqli->error;
    }

    function getItemsToCategory($name_parent_category)
    {
        global $mysqli;
        $rows = [];
        $sql_item = $mysqli->prepare("WITH RECURSIVE items(idItem, name_category, name_parent_category, description_product, image_location, idprice, value_attribute, name_attribute) AS (
            SELECT idItem, Product.name_category, Product.name_parent_category, Product.description, image_location, idprice, pr1.value, pr1.name_attribute FROM Product
                LEFT JOIN category ON(category.name_category = Product.name_category AND category.active = 1)
                LEFT JOIN price ON (Product.idItem = price.product_iditem AND price.active = 1)
                LEFT JOIN image ON (Product.idItem = image.product_iditem AND image.active = 1)
                LEFT JOIN product_attribute_value pr1 ON (pr1.product_idItem = Product.idItem AND pr1.active = 1)
                LEFT JOIN attribute ON (attribute.name_attribute = pr1.name_attribute AND attribute.active = 1)
                    WHERE Product.name_parent_category = ? AND Product.active = 1
        
            UNION ALL
        
            SELECT p.idItem, c.name_category, c.name_parent_category, p.description, i.image_location, pr.idprice, pr2.value, a.name_attribute FROM Product p
                LEFT JOIN category c ON(c.name_category = p.name_category AND c.active = 1)
                LEFT JOIN product_attribute_value pr2 ON (pr2.product_idItem = p.idItem AND pr2.active = 1)
                LEFT JOIN attribute a ON (a.name_attribute = pr2.name_attribute AND a.active = 1 AND)
                LEFT JOIN price pr ON (p.idItem = pr.product_iditem AND pr.active = 1)
                LEFT JOIN image i ON (p.idItem = i.product_iditem AND i.active = 1)
               
            INNER JOIN items ON(c.name_parent_category = items.name_category))

            SELECT * FROM items UNION ALL SELECT idItem, Category.name_category, name_parent_category, Product.description, image_location, idprice, pr1.value, pr1.name_attribute  FROM Product 
            LEFT JOIN category ON(category.name_category = Product.name_category AND category.active = 1)
                LEFT JOIN product_attribute_value pr1 ON (pr1.product_idItem = Product.idItem AND pr1.active = 1)
                LEFT JOIN attribute ON (attribute.name_attribute = pr1.name_attribute AND attribute.active = 1)
                LEFT JOIN price ON (Product.idItem = price.product_iditem AND price.active = 1)
                LEFT JOIN image ON (Product.idItem = image.product_iditem AND image.active = 1)
                    WHERE Category.name_category = ? AND Product.active = 1");
        if($sql_item === false)
            return $mysqli->error;
        $sql_item->bind_param("ss", $name_parent_category, $name_parent_category);
        $sql_item->execute();
        $sql = $sql_item->get_result();
            
        if(mysqli_num_rows($sql) > 0)
            while($row = $sql->fetch_assoc())
                    $rows[] = $row;

        return $rows;
    }
?>