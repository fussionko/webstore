<?php
    require_once("../connect_db.php");
    require_once("../Cart/cart_functions.php");

    function addCartToOrder($id_cart, $id_order)
    {
        if(!is_int($id_cart) || !is_int($id_order)) return 0;

        $cart_items = getCartItemsOrder($id_cart);
        if($cart_items == -1) return 0;

        foreach($cart_items as $id => $data)
        {
            if(addProductToOrder($id, $id_order, $data["quantity"]) == 0)
            {
                if(removeAllProductsFromOrder($id_order) == 0) return -1;
                return 0;
            }
        }
        return 1;
    }

    function addProductToOrder($id_item, $id_order, $quantity)
    {
        global $mysqli;
        $sql = $mysqli->prepare("INSERT INTO product_to_order(product_idItem, order_idorder, quantity) VALUES(?, ?, ?)");
        $sql->bind_param('iii', $id_item, $id_order, $quantity);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function removeAllProductsFromOrder($id_order)
    {
        global $mysqli;
        $sql = $mysqli->prepare("DELETE FROM product_to_order WHERE order_idorder = ?") ;
        $sql->bind_param('i', $id_order);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function createOrder($shipping_company, $id_addres)
    {
        global $mysqli;
        $sql = $mysqli->prepare("INSERT INTO `order`(shipping_company_name, address_id_address) VALUES(?, ?)");
        $sql->bind_param('si', $shipping_company, $id_addres);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return -1;
        return $sql->insert_id;
    }

    function addUserToOrder($username, $id_order)
    {
        global $mysqli;
        $sql = $mysqli->prepare("UPDATE `order` SET user_username = ? WHERE idorder = ?");
        $sql->bind_param('si', $username, $id_order);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function updateOrderSum($id_order, $sum_price)
    {
        global $mysqli;
        $sql = $mysqli->prepare("UPDATE `order` SET sum_price = ? WHERE idorder = ?");
        $sql->bind_param('di', $sum_price, $id_order);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function updateOrderTrackingNumber($id_order, $tracking_number)
    {
        global $mysqli;
        $sql = $mysqli->prepare("UPDATE `order` SET tracking_number = ? WHERE idorder = ?");
        $sql->bind_param('si', $tracking_number, $id_order);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function createDeliveryStatus($id_order, $datetime, $location)
    {
        global $mysqli;
        $sql = $mysqli->prepare("INSERT INTO order_delivery_status(date, location, order_idorder) VALUES(?, ?, ?)");
        $sql->bind_param('isi', $datetime, $location, $id_order);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return -1;
        return $sql->insert_id;
    }

    function createAddress($country, $city, $postal_code, $address, $telephone_number)
    {
        global $mysqli;
        $sql = $mysqli->prepare("INSERT INTO address(country, city, postal_code, address, telephone_number) VALUES(?, ?, ?, ?, ?)");
        $sql->bind_param('ssiss', $country, $city, $postal_code, $address, $telephone_number);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return -1;
        return $sql->insert_id;
    }

    function getAddress($id_address)
    {
        global $mysqli;
        $sql = $mysqli->prepare("SELECT country, city, postal_code, address, telephone_number FROM address WHERE id_address = ? LIMIT 1");
        $sql->bind_param('i', $id_address);
        $sql->execute();
        $sql = $sql->get_result();

        if($sql->num_rows == 0)
            return -1;
        return $sql->fetch_assoc(); 
    }

    function addUserToAddress($username, $id_address)
    {
        global $mysqli;
        $sql = $mysqli->prepare("UPDATE address SET user_username = ? WHERE id_address = ?");
        $sql->bind_param('si', $username, $id_address);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function checkShippingCompanyExists($shipping_company)
    {
        global $mysqli;
        $sql = $mysqli->prepare("SELECT * FROM shipping_company WHERE name = ? AND active = 1 LIMIT 1");
        $sql->bind_param('s', $shipping_company);
        $sql->execute();
        $sql = $sql->get_result();

        if($sql->num_rows == 0)
            return 0;
        return 1;
    }

?>