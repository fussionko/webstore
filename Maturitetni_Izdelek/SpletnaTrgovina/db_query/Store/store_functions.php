<?php
    header('charset=utf-8');
    require_once("../connect_db.php");

    function getCategory() 
    {
        global $mysqli;
        $sql_category = "SELECT * FROM category WHERE active = 1"; //Izbere vse izpise iz kategorije
        $result_category = $mysqli->query($sql_category);
        $rows = [];
        while ($row = mysqli_fetch_assoc($result_category)) {
            $rows[] = $row;
        }
        return $rows;
    }

    function getSubCategory($name_parent_category) {
        global $mysqli;
        $sql_subCategory = $mysqli->prepare("SELECT s.name_category FROM Category  WHERE  ? = name_parent_category AND active = 1");
        $sql_subCategory->bind_param("s", $name_parent_category);
        $sql_subCategory->execute();
        $sql_subCategory = $sql_subCategory->get_result();
        $rows = [];
        while ($row = mysqli_fetch_assoc($sql_subCategory)) {
            $rows[] = $row;
        }
        return $rows; 
    }

    // Vrne asociativno tabelo z podatki o izdelku ki bodo prikazani
    function getParentItems($name_category, $name_parent_category)
    {
        global $mysqli;
        try {
            $sql_item = $mysqli->prepare("WITH RECURSIVE items(idItem, itemName, name_category, name_parent_category, discount, price, product_description, image_location) AS (
                SELECT idItem, itemName, Product.name_category, Product.name_parent_category, amount, price, Product.description, image_location FROM Product
                    LEFT JOIN category ON(category.name_category = Product.name_category AND category.active = 1)
                    LEFT JOIN product_discount ON (Product.idItem = product_discount.product_iditem AND product_discount.active = 1)
                    LEFT JOIN discount ON (discount.iddiscount = product_discount.discount_iddiscount)
                    LEFT JOIN price ON (Product.idItem = price.product_iditem AND price.active = 1)
                    LEFT JOIN image ON (Product.idItem = image.product_iditem AND image.active = 1)
                        WHERE Product.name_parent_category = ? AND Product.name_category = ? AND Product.active = 1
           
                UNION ALL
           
                SELECT p.idItem, p.itemName, p.name_category, c.name_parent_category, dis.amount, pr.price, p.description, i.image_location FROM Product p
                    LEFT JOIN product_discount d ON (p.idItem = d.product_iditem AND d.active = 1)
                    LEFT JOIN category c ON(c.name_category = p.name_category AND c.active = 1)
                    LEFT JOIN discount dis ON (dis.iddiscount = d.discount_iddiscount)
                    LEFT JOIN price pr ON (p.idItem = pr.product_iditem AND pr.active = 1)
                    LEFT JOIN image i ON (p.idItem = i.product_iditem AND i.active = 1)
   
               INNER JOIN items ON(c.name_parent_category = items.name_category))
   
                SELECT * FROM items UNION ALL SELECT idItem, itemName, Product.name_category, Product.name_parent_category, amount, price, Product.description, image_location  FROM Product 
                    LEFT JOIN category ON(category.name_category = Product.name_category AND category.active = 1)
                    LEFT JOIN product_discount ON (Product.idItem = product_discount.product_iditem AND product_discount.active = 1)
                    LEFT JOIN discount ON (discount.iddiscount = product_discount.discount_iddiscount)
                    LEFT JOIN price ON (Product.idItem = price.product_iditem AND price.active = 1)
                    LEFT JOIN image ON (Product.idItem = image.product_iditem AND image.active = 1)
                        WHERE Product.name_category = ? AND Product.name_category = ? AND Product.active = 1");

            if($sql_item === false)
                throw new Exception($mysqli->error);

            $sql_item->bind_param("ssss", $name_parent_category, $name_category, $name_parent_category, $name_category);

            if($sql_item === false)
                throw new Exception($mysqli->error);

            $sql_item->execute();

            if($sql_item === false)
                throw new Exception($mysqli->error);

            $sql_item = $sql_item->get_result();
        }
        catch(Exception $e) 
        {
            return([$e->getMessage(), $name_parent_category]);
        }
    
    
        $rows = [];
        while ($row = $sql_item->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows; // vrne array 
    }

    function getMainParentItems($name_parent_category)
    {
        global $mysqli;
        try {
            $sql_item = $mysqli->prepare("WITH RECURSIVE items(idItem, itemName, name_category, name_parent_category, discount, price, product_description, image_location) AS (
                SELECT idItem, itemName, Product.name_category, Product.name_parent_category, amount, price, Product.description, image_location FROM Product
                    LEFT JOIN category ON(category.name_category = Product.name_category AND category.active = 1)
                    LEFT JOIN product_discount ON (Product.idItem = product_discount.product_iditem AND product_discount.active = 1)
                    LEFT JOIN discount ON (discount.iddiscount = product_discount.discount_iddiscount)
                    LEFT JOIN price ON (Product.idItem = price.product_iditem AND price.active = 1)
                    LEFT JOIN image ON (Product.idItem = image.product_iditem AND image.active = 1)
                        WHERE Product.name_parent_category = ? AND Product.active = 1
           
                UNION ALL
           
                SELECT p.idItem, p.itemName, p.name_category, c.name_parent_category, dis.amount, pr.price, p.description, i.image_location FROM Product p
                    LEFT JOIN product_discount d ON (p.idItem = d.product_iditem AND d.active = 1)
                    LEFT JOIN category c ON(c.name_category = p.name_category AND c.active = 1)
                    LEFT JOIN discount dis ON (dis.iddiscount = d.discount_iddiscount)
                    LEFT JOIN price pr ON (p.idItem = pr.product_iditem AND pr.active = 1)
                    LEFT JOIN image i ON (p.idItem = i.product_iditem AND i.active = 1)
   
               INNER JOIN items ON(c.name_parent_category = items.name_category))
   
                SELECT * FROM items UNION ALL SELECT idItem, itemName, Product.name_category, Product.name_parent_category, amount, price, Product.description, image_location  FROM Product 
                    LEFT JOIN category ON(category.name_category = Product.name_category AND category.active = 1)
                    LEFT JOIN product_discount ON (Product.idItem = product_discount.product_iditem AND product_discount.active = 1)
                    LEFT JOIN discount ON (discount.iddiscount = product_discount.discount_iddiscount)
                    LEFT JOIN price ON (Product.idItem = price.product_iditem AND price.active = 1)
                    LEFT JOIN image ON (Product.idItem = image.product_iditem AND image.active = 1)
                        WHERE Product.name_category = ? AND Product.active = 1");

            if($sql_item === false)
                throw new Exception($mysqli->error);

            $sql_item->bind_param("ss", $name_parent_category, $name_parent_category);

            if($sql_item === false)
                throw new Exception($mysqli->error);

            $sql_item->execute();

            if($sql_item === false)
                throw new Exception($mysqli->error);

            $sql_item = $sql_item->get_result();
        }
        catch(Exception $e) 
        {
            return([$e->getMessage(), $name_parent_category]);
        }
    
    
        $rows = [];
        while ($row = $sql_item->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows; // vrne array 
    }

    function getAllItems()
    {
        global $mysqli;
        $sql_item = $mysqli->query("WITH RECURSIVE items(idItem, itemName, name_category, name_parent_category, discount, price, product_description, image_location) AS (
            SELECT idItem, itemName, Product.name_category, Product.name_parent_category, amount, price, Product.description, image_location FROM Product
                LEFT JOIN category ON(category.name_category = Product.name_category AND category.active = 1)
                LEFT JOIN product_discount ON (Product.idItem = product_discount.product_iditem AND product_discount.active = 1)
                LEFT JOIN discount ON (discount.iddiscount = product_discount.discount_iddiscount)
                LEFT JOIN price ON (Product.idItem = price.product_iditem AND price.active = 1)
                LEFT JOIN image ON (Product.idItem = image.product_iditem AND image.active = 1)
                    WHERE Product.active = 1
        
            UNION ALL
        
            SELECT p.idItem, p.itemName, p.name_category, c.name_parent_category, dis.amount, pr.price, p.description, i.image_location FROM Product p
                LEFT JOIN product_discount d ON (p.idItem = d.product_iditem AND d.active = 1)
                LEFT JOIN category c ON(c.name_category = p.name_category AND c.active = 1)
                LEFT JOIN discount dis ON (dis.iddiscount = d.discount_iddiscount)
                LEFT JOIN price pr ON (p.idItem = pr.product_iditem AND pr.active = 1)
                LEFT JOIN image i ON (p.idItem = i.product_iditem AND i.active = 1)

           INNER JOIN items ON(c.name_parent_category = items.name_category))
           SELECT * FROM items"
        );

        if($sql_item === false) return -1;

        if($sql_item->num_rows == 0)
            return -1;
    
        $rows = [];
        while ($row = $sql_item->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows; // vrne array 
    }

    function getItemData($id_item)
    {
        global $mysqli;

        try{
            $sql = $mysqli->prepare("SELECT itemName, Product.name_category, Product.name_parent_category, amount, price, Product.description FROM Product 
                    	                    LEFT JOIN category ON(category.name_category = Product.name_category AND category.active = 1)
                    	                    LEFT JOIN product_discount ON (Product.idItem = product_discount.product_iditem AND product_discount.active = 1)
                    	                    LEFT JOIN discount ON (discount.iddiscount = product_discount.discount_iddiscount)
                    	                    LEFT JOIN price ON (Product.idItem = price.product_iditem AND price.active = 1)
                    	                        WHERE product.idItem = ? AND Product.active = 1 LIMIT 1");
            if($sql===false)
                throw new Exception($mysqli->error);

            $sql->bind_param("i", $id_item);
            if($sql===false)
                throw new Exception($mysqli->error);
            
            $sql->execute();
            if($sql===false)
                throw new Exception($mysqli->error);

            $sql = $sql->get_result();

        }
        catch(Exception $e) {
            return -1;
        }

        if($sql->num_rows == 0)
            return -1;

        $sql = $sql->fetch_assoc();
        return $sql; 
    }

    function getItemAttributesData($id_item)
    {
        global $mysqli;

        try{
            $sql = $mysqli->prepare("SELECT pav.value, attribute.name_attribute FROM attribute
                                        INNER JOIN product_attribute_value pav ON(pav.name_attribute = attribute.name_attribute AND pav.active = 1)
                    	                    WHERE pav.product_idItem = ? AND attribute.active = 1");
            if($sql===false)
                throw new Exception($mysqli->error);

            $sql->bind_param("i", $id_item);
            if($sql===false)
                throw new Exception($mysqli->error);
            
            $sql->execute();
            if($sql===false)
                throw new Exception($mysqli->error);

            $sql = $sql->get_result();

        }
        catch(Exception $e) {
            return -1;
        }
        

        if($sql->num_rows == 0)
            return -1;

        $rows = [];
        while ($row = $sql->fetch_assoc()) 
        {
            $rows[$row["name_attribute"]][] = $row["value"];
        }
            
        return $rows; 
    }

    function getItemImages($id_item)
    {
        global $mysqli;

        try{
            $sql = $mysqli->prepare("SELECT image_location FROM image WHERE product_idItem = ? AND active = 1");
            if($sql===false)
                throw new Exception($mysqli->error);

            $sql->bind_param("i", $id_item);
            if($sql===false)
                throw new Exception($mysqli->error);
            
            $sql->execute();
            if($sql===false)
                throw new Exception($mysqli->error);

            $sql = $sql->get_result();

        }
        catch(Exception $e) {
            return($e->getMessage());
        }
        
        // print_r($sql);

        if($sql->num_rows == 0)
            return -1;

        $rows = [];
        while ($row = $sql->fetch_assoc()) 
            $rows[] = $row["image_location"];
        return $rows; 
    }

    function getSearchItems($search_query)
    {
        global $mysqli;

        try{
            $components = $mysqli->prepare("SELECT idItem, itemName, Product.name_category, Product.name_parent_category, amount, price, Product.description, image_location  FROM Product 
                    	                    LEFT JOIN category ON(category.name_category = Product.name_category AND category.active = 1)
                    	                    LEFT JOIN product_discount ON (Product.idItem = product_discount.product_iditem AND product_discount.active = 1)
                    	                    LEFT JOIN discount ON (discount.iddiscount = product_discount.discount_iddiscount)
                    	                    LEFT JOIN price ON (Product.idItem = price.product_iditem AND price.active = 1)
                    	                    LEFT JOIN image ON (Product.idItem = image.product_iditem AND image.active = 1)
                    	                        WHERE UPPER(itemName) LIKE UPPER(?) AND Product.active = 1");
            if($components===false)
                throw new Exception($mysqli->error);

            $components->bind_param("s", $search_query);
            if($components===false)
                throw new Exception($mysqli->error);
            
            $components->execute();
            if($components===false)
                throw new Exception($mysqli->error);
            $components = $components->get_result();

        }
        catch(Exception $e) {
            return($e->getMessage());
        }
        
        if($components->num_rows == 0)
            return -1;

        $rows = [];
        while ($row = $components->fetch_assoc()) 
            $rows[] = $row;
        return $rows; 
    }

    function orderData($data)
    {
        $temp_arr = [];

        for($i = 0, $t = $i; $i < count($data); $i++)
        {
            if($i != 0)
                if($temp_arr[$t][0] != $data[$i]["idItem"])
                    $t++;
            if(!isset($temp_arr[$t]))
            {   
                $temp_arr[$t] = [$data[$i]["idItem"], $data[$i]];
                if($data[$i]["image_location"] != null)
                    $temp_arr[$t][1]["image_location"] = [$data[$i]["image_location"]];
            }
            else if($data[$i]["image_location"] != null)
                $temp_arr[$t][1]["image_location"][] = $data[$i]["image_location"]; 
        }
      
        return array_values($temp_arr);
    }

    function checkItemExists($id_item)
    {
        global $mysqli;

        try{
            $components = $mysqli->prepare("SELECT * FROM product WHERE idItem = ? LIMIT 1");
            if($components===false)
                throw new Exception($mysqli->error);

            $components->bind_param("i", $id_item);
            if($components===false)
                throw new Exception($mysqli->error);
            
            $components->execute();
            $components = $components->get_result();
        }
        catch(Exception $e) {
            return($e->getMessage());
        }

        if($components->num_rows == 0)
            return 0;
        return 1;
    }

    function getItemPrice($id_item)
    {
        global $mysqli;

        try{
            $sql = $mysqli->prepare("SELECT price FROM price WHERE product_idItem = ? AND active = 1 LIMIT 1");
            if($sql===false)
                throw new Exception($mysqli->error);

            $sql->bind_param("i", $id_item);
            if($sql===false)
                throw new Exception($mysqli->error);
            
            $sql->execute();
            if($sql===false)
                throw new Exception($mysqli->error);

            $sql = $sql->get_result();
            if($sql===false)
                throw new Exception($mysqli->error);
        }
        catch(Exception $e) {
            return($e->getMessage());
        }

        if($sql->num_rows == 0)
            return -1;
        $sql = $sql->fetch_assoc();
        return $sql["price"];
    }


    function getCategories($id_item)
    {
        global $mysqli;
        try { 
            $sql = $mysqli->prepare("WITH RECURSIVE items(name_category, name_parent_category) AS (
                SELECT Product.name_category, Product.name_parent_category FROM Product
                    LEFT JOIN category ON(category.name_category = Product.name_category AND category.active = 1)
                        WHERE Product.active = 1 AND Product.idItem = ?
                
                UNION ALL
                
                SELECT p.name_category, c.name_parent_category FROM Product p
                    LEFT JOIN category c ON(c.name_category = p.name_category AND c.active = 1)

            	INNER JOIN items ON(c.name_parent_category = items.name_category))
            	SELECT * FROM items");

            $sql->bind_param("i", $id_item);
            if($sql===false)
                throw new Exception($mysqli->error);
            
            $sql->execute();
            if($sql===false)
                throw new Exception($mysqli->error);

            $sql = $sql->get_result();
            if($sql===false)
                throw new Exception($mysqli->error);

        }
        catch(Exception $e) {
            return($e->getMessage());
        }
       
        if($sql->num_rows == 0)
            return -1;

        return $sql->fetch_assoc();
    }
?>