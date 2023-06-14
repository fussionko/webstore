<?php
    header('charset=utf-8');
    require_once("../connect_db.php");

    function createDefaultCart()
    {
        global $mysqli;
        $mysqli->query("INSERT INTO cart(active_start_time) VALUES (CURRENT_TIMESTAMP)");
        if($mysqli->affected_rows == -1)
            return -1;
        return $mysqli->insert_id;
    }

    function createUserCart($username)
    {
        global $mysqli;
        $sql = $mysqli->prepare("INSERT INTO cart(active_start_time, user_username) VALUES (CURRENT_TIMESTAMP, ?)");
        $sql->bind_param('s', $username);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return -1;
        return $sql->insert_id;
    }

    function linkCartToUser($id_cart, $username)
    {
        global $mysqli;
        $sql = $mysqli->prepare("UPDATE cart SET user_username = ? WHERE idcart = ?");
        $sql->bind_param('is', $id_cart, $username);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function closeCart($username)
    {
        global $mysqli;
        $sql = $mysqli->prepare("UPDATE cart SET active_stop_time = CURRENT_TIMESTAMP, active = 0 WHERE user_username = ? AND active = 1");
        $sql->bind_param('s', $username);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function addItemToCart($id_item, $id_cart, $quantity)
    {
        global $mysqli;
        $sql = $mysqli->prepare("INSERT INTO product_to_cart(product_idItem, cart_idcart, quantity) VALUES (?, ?, ?)");
        $sql->bind_param('iii', $id_item, $id_cart, $quantity);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function updateItemToCart($id_item, $id_cart, $quantity)
    {
        global $mysqli;
        $sql = $mysqli->prepare("UPDATE product_to_cart SET quantity = ? WHERE product_idItem = ? AND cart_idcart = ? AND active = 1");
        $sql->bind_param('iii', $quantity, $id_item, $id_cart);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function removeItemFromCart($id_item, $id_cart)
    {
        global $mysqli;
        $sql = $mysqli->prepare("DELETE FROM product_to_cart WHERE product_idItem = ? AND cart_idcart = ? AND active = 1");
        $sql->bind_param('ii', $id_item, $id_cart);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function increaseQuantityItemInCart($id_item, $id_cart, $quantity)
    {
        global $mysqli;
        $sql = $mysqli->prepare("UPDATE product_to_cart SET quantity = quantity + ? WHERE product_idItem = ? AND cart_idcart = ? AND active = 1");
        $sql->bind_param('iii', $quantity, $id_item, $id_cart);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function decreaseQuantityItemInCart($id_item, $id_cart, $quantity)
    {
        global $mysqli;
        $sql = $mysqli->prepare("UPDATE product_to_cart SET quantity = quantity - ? WHERE product_idItem = ? AND cart_idcart = ? AND active = 1");
        $sql->bind_param('iii', $quantity, $id_item, $id_cart);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function activeItemInCart($id_item, $id_cart)
    {
        global $mysqli;
        $sql = $mysqli->prepare("UPDATE product_to_cart SET active = 1 WHERE product_idItem = ? AND cart_idcart = ?");
        $sql->bind_param('iii', $quantity, $id_item, $id_cart);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function inactiveItemInCart($id_item, $id_cart)
    {
        global $mysqli;
        $sql = $mysqli->prepare("UPDATE product_to_cart SET active = 0 WHERE product_idItem = ? AND cart_idcart = ?");
        $sql->bind_param('iii', $quantity, $id_item, $id_cart);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function checkInCart($id_cart, $id_item)
    {
        global $mysqli;
        $sql = $mysqli->prepare("SELECT * FROM product_to_cart WHERE cart_idcart = ? AND product_idItem = ? AND active = 1 LIMIT 1");
        $sql->bind_param('ii', $id_cart, $id_item);
        $sql->execute();
        $sql = $sql->get_result();

        if($sql->num_rows == 0)
            return 0;
        return 1;
    }
    
    function getCartItems($id_cart)
    {
        global $mysqli;
        $sql = $mysqli->prepare("SELECT idItem, itemName, amount, price, image_location, product_to_cart.quantity FROM Product
                                LEFT JOIN product_discount ON (Product.idItem = product_discount.product_iditem AND product_discount.active = 1)
                                LEFT JOIN discount ON (discount.iddiscount = product_discount.discount_iddiscount)
                                LEFT JOIN price ON (Product.idItem = price.product_iditem AND price.active = 1)
                                LEFT JOIN image ON (Product.idItem = image.product_iditem AND image.active = 1)
                                LEFT JOIN product_to_cart ON(product_to_cart.product_idItem = Product.idItem AND product_to_cart.active = 1)
                                LEFT JOIN cart ON(cart.idcart = cart_idcart AND cart.active = 1)
                                    WHERE cart_idcart = ? AND Product.active = 1");
        $sql->bind_param('i', $id_cart);
        $sql->execute();
        $sql = $sql->get_result();

        if($sql->num_rows == 0)
            return -1;

        $rows = [];
        while ($row = $sql->fetch_assoc()) 
            $rows[] = $row;

        $temp = [];
        foreach($rows as $row)
            $temp[$row["idItem"]] = $row;

        return $temp; 
    }

    function getTempCartItems($items)
    {
        global $mysqli;
        $rows = [];
        foreach($items as $id_item => $quantity)
        {
            $sql = $mysqli->prepare("SELECT idItem, itemName, amount, price, image_location FROM Product
                                    LEFT JOIN product_discount ON (Product.idItem = product_discount.product_iditem AND product_discount.active = 1)
                                    LEFT JOIN discount ON (discount.iddiscount = product_discount.discount_iddiscount)
                                    LEFT JOIN price ON (Product.idItem = price.product_iditem AND price.active = 1)
                                    LEFT JOIN image ON (Product.idItem = image.product_iditem AND image.active = 1)
                                        WHERE Product.active = 1 AND idItem = ?");
            $sql->bind_param('i', $id_item);
            $sql->execute();
            $sql = $sql->get_result();

            if($sql->num_rows == 0)
            return -1;


            $row = $sql->fetch_assoc();
            $row["quantity"] = $quantity;
            $rows[] = $row;
        }


        $temp = [];
        foreach($rows as $row)
            $temp[$row["idItem"]] = $row;

        return $temp; 
    }

    function getCartItemsOrder($id_cart)
    {        
        global $mysqli;
        $sql = $mysqli->prepare("SELECT idItem, product_to_cart.quantity FROM Product
                                LEFT JOIN product_to_cart ON(product_to_cart.product_idItem = Product.idItem AND product_to_cart.active = 1)
                                    WHERE cart_idcart = ? AND Product.active = 1");
        $sql->bind_param('i', $id_cart);
        $sql->execute();
        $sql = $sql->get_result();

        if($sql->num_rows == 0)
            return -1;

        $rows = [];
        while ($row = $sql->fetch_assoc()) 
            $rows[] = $row;


        $temp = [];
        foreach($rows as $row)
            $temp[$row["idItem"]] = $row;

        return $temp; 
    }

    function calculateTotalPriceInCart($id_cart)
    {   
        global $mysqli;
        $sql = $mysqli->prepare("SELECT idItem, price, quantity FROM product_to_cart INNER JOIN product ON(product_idItem = idItem AND product.active = 1)
                                    INNER JOIN price ON(price.product_idItem = idItem AND price.active = 1)
                                    WHERE cart_idcart = ? AND product_to_cart.active = 1");
        $sql->bind_param('i', $id_cart);
        $sql->execute();
        $sql = $sql->get_result();

        if($sql->num_rows == 0)
            return 0;

        $sum_price = 0;
        while($row = $sql->fetch_assoc())
            $sum_price += $row["quantity"]*$row["price"];

        return $sum_price;
        
    }


?>