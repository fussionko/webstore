<?php
    header('Content-Type: text/html; charset=utf-8');
    require_once("../db_query/connect_db.php");

    //-----------------------------------------------------------------------------------------------

    function insertAttribute($attribute)
    {
        global $mysqli;
        try{
            $sql = $mysqli->prepare("INSERT INTO attribute(name_attribute) VALUES(?)");
            if($sql===false)
                throw new Exception($mysqli->error);

            $sql->bind_param('s', $attribute);
            if($sql===false)
                throw new Exception($mysqli->error);

            $sql->execute();
            if($sql===false)
                throw new Exception($mysqli->error);
        }
        
        catch(Exception $e) {
            return($e->getMessage());
        }

        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function removeAttribute($attribute)
    {
        global $mysqli;
        $sql = $mysqli->prepare("DELETE FROM attribute WHERE name_attribute = ?");
        $sql->bind_param('s', $attribute);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    //-----------------------------------------------------------------------------------------------

    function insertComponentAttribute($component_attribute)
    {
        global $mysqli;
        try{
            $sql = $mysqli->prepare("INSERT INTO component_attribute(idcomponent_attribute) VALUES(?)");
            if($sql===false)
                throw new Exception($mysqli->error);

            $sql->bind_param('s', $component_attribute);
            if($sql===false)
                throw new Exception($mysqli->error);

            $sql->execute();
            if($sql===false)
                throw new Exception($mysqli->error);
        }
        
        catch(Exception $e) {
            return($e->getMessage());
        }

        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function removeComponentAttribute($component_attribute)
    {
        global $mysqli;
        $sql = $mysqli->prepare("DELETE FROM component_attribute WHERE idcomponent_attribute = ?");
        $sql->bind_param('s', $component_attribute);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    //-----------------------------------------------------------------------------------------------

    function insertProduct($product_name, $name_category, $name_parent_category)
    {
        global $mysqli;
        $sql = $mysqli->prepare("INSERT INTO product(itemName, name_category, name_parent_category) VALUES (?, ?, ?)");
        $sql->bind_param('sss', $product_name, $name_category, $name_parent_category);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return -1;
        return $sql->insert_id;
    }

    function removeProduct($product_id, $name_category, $name_parent_category)
    {
        global $mysqli;
        $sql = $mysqli->prepare("DELETE FROM product WHERE idItem = ? AND name_category = ? AND name_parent_category = ?") ;
        $sql->bind_param('iss', $product_id, $name_category, $name_parent_category);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return -1;
        return 1;
    }

    //-----------------------------------------------------------------------------------------------

    function insertComponent($componentName, $internal_category, $price, $id_product_link)
    {
        global $mysqli;
        $sql = $mysqli->prepare("INSERT INTO component(id_group, itemName, price, id_product_link) VALUES (?, ?, ?, ?)");
        $sql->bind_param('ssdi', $internal_category, $componentName, $price, $id_product_link);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return -1;
        return $sql->insert_id;
    }

    function removeComponent($component_id, $internal_category)
    {
        global $mysqli;
        $sql = $mysqli->prepare("DELETE FROM component WHERE id_group = ?, idcomponent = ?");
        $sql->bind_param('si', $internal_category, $component_id);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return -1;
        return 1;
    }

    //-----------------------------------------------------------------------------------------------

    function insertProductAttributeValue($attribute, $idItem, $value)
    {
        global $mysqli;
        $sql = $mysqli->prepare("INSERT INTO product_attribute_value(name_attribute, product_idItem, value) VALUES(?, ?, ?)");
        $sql->bind_param('sis', $attribute, $idItem, $value);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function removeProductAttributeValue($attribute, $idItem)
    {
        global $mysqli;
        $sql = $mysqli->prepare("DELETE FROM product_attribute_value WHERE name_attribute = ?, product_idItem = ?");
        $sql->bind_param('si', $attribute, $idItem);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    //-----------------------------------------------------------------------------------------------

    function insertComponentAttributeValue($component_attribute, $idComponent, $value)
    {
        global $mysqli;
        $sql = $mysqli->prepare("INSERT INTO component_has_component_attribute(idcomponent_attribute, component_idcomponent, value) VALUES(?, ?, ?)");
        $sql->bind_param('sis', $component_attribute, $idComponent, $value);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function removeComponentAttributeValue($component_attribute, $idComponent)
    {
        global $mysqli;
        $sql = $mysqli->prepare("DELETE FROM component_has_component_attribute WHERE idcomponent_attribute = ?, component_idcomponent = ?");
        $sql->bind_param('si', $component_attribute, $idComponent);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    //-----------------------------------------------------------------------------------------------

    function insertImageToProduct($description, $idItem, $paths)
    {
        global $mysqli;
        $q = "INSERT INTO image(image_location, description_alt, product_idItem) VALUES ";
        foreach($paths as $path)
            $q .= '("'.$path.'", "'.$description.'", '.$idItem.'),';
        $q = rtrim($q, ',');
        $mysqli->query($q);
        
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    //-----------------------------------------------------------------------------------------------

    function insertImageToComponent($idComponent, $internal_category, $path)
    {
        global $mysqli;
        $sql = $mysqli->prepare("UPDATE component SET image_location = ? WHERE idcomponent = ? AND id_group = ?");
        $sql->bind_param('sis', $path, $idComponent, $internal_category);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    //-----------------------------------------------------------------------------------------------

    function insertPriceToProduct($idProduct, $price)
    {
        global $mysqli;
        $sql = $mysqli->prepare("INSERT INTO price(product_idItem, price) VALUES (?, ?)");
        $sql->bind_param('id', $idProduct, $price);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    //-----------------------------------------------------------------------------------------------

    function insertCategory($category, $parent)
    {
        global $mysqli;
        
        try {
            $mysqli->query("SET FOREIGN_KEY_CHECKS=0");
            if($mysqli === false)
                throw new Exception($mysqli->error);
            $sql = $mysqli->prepare("INSERT INTO category(name_category, name_parent_category) VALUES (?, ?)");
            if($sql === false)
                throw new Exception($mysqli->error);
            $sql->bind_param('ss', $category, $parent);
            if($sql === false)
                throw new Exception($mysqli->error);
            $sql->execute();

            if($sql === false)
                throw new Exception($mysqli->error);

            $mysqli->query("SET FOREIGN_KEY_CHECKS=1");
            if($mysqli === false)
                throw new Exception($mysqli->error);
        }
        catch(Exception $e) {
            return $e->getMessage();
        }

        if($mysqli->affected_rows == -1)
        {
            return 0;
        }
            
        return 1;
    }

    //-----------------------------------------------------------------------------------------------

    function getAllCategories()
    {
        global $mysqli;
        try {
            $sql = $mysqli->query("SELECT name_category, name_parent_category FROM category");
            if($sql === false)
                throw new Exception($mysqli->error);
        }
        catch(Exception $e) {
            return $e->getMessage();
        }

        if($sql->num_rows == 0)
            return -1;
        
        $rows = [];
        while($row = $sql->fetch_assoc())
            $rows[] = $row;
        return $rows;
    }

    //-----------------------------------------------------------------------------------------------
?>