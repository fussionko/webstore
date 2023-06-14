<?php
    header('charset=utf-8');
    require_once("connect_db.php");

    // Vrne vse direktne otroke podane kategorije
    function getCategory($category_name) {
        global $mysqli;
        if($category_name == "all")
        {
            $sql_category = "SELECT name_category FROM category WHERE name_parent_category = 'x' AND active = 1"; //Izbere vse izpise iz kategorije
            $sql = $mysqli->query($sql_category) or die($mysqli->error);
            $rows = [];
            while ($row = $sql->fetch_assoc()) {
                $rows[] = $row;
            }
            return $rows;
            
        }
        else 
        {  
            $sql_category = $mysqli->prepare("SELECT name_category FROM category WHERE name_parent_category = ?");
            $sql_category->bind_param("s", $category_name); // tipi , imespremenljivke
            $sql_category->execute();
            $sql = $sql_category->get_result() or die($mysqli->error);
            $rows = [];
            while ($row = $sql->fetch_assoc()) {
                $rows[] = $row;
            }
            return $rows;
        }
    }
?>