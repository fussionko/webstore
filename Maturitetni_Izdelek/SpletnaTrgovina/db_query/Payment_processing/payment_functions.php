<?php
    require_once("../connect_db.php");

    function insertPaymentCard($card_number, $cvv, $card_expires, $cardholder_name, $description)
    {
        global $mysqli;

        if(isset($description))
        {
            $sql = $mysqli->prepare("INSERT INTO payment_card(card_number, cvv, card_expires, cardholder_name, description)
                                        VALUES(?, ?, ?, ?, ?)");
            $sql->bind_param('sisss', $card_number, $cvv, $card_expires, $cardholder_name, $description);
        }
        else
        {
            $sql = $mysqli->prepare("INSERT INTO payment_card(card_number, cvv, card_expires, cardholder_name)
                                        VALUES(?, ?, ?, ?)");
            $sql->bind_param('siss', $card_number, $cvv, $card_expires, $cardholder_name);
        }
            
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return -1;
        return $sql->insert_id;
    }

    function getCard($id_card)
    {
        global $mysqli;
        $sql = $mysqli->prepare("SELECT card_number, cvv, card_expires, cardholder_name, description FROM payment_card WHERE id_payment_card = ? LIMIT 1");
        $sql->bind_param('i', $id_card);
        $sql->execute();
        $sql = $sql->get_result();

        if($sql->num_rows == 0)
            return -1;
        return $sql->fetch_assoc(); 
    }

    function addUserToPaymentCard($username, $id_card)
    {
        global $mysqli;
        $sql = $mysqli->prepare("UPDATE payment_card SET user_username = ? WHERE id_payment_card = ?");
        $sql->bind_param('si', $username, $id_card);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function deletePaymentCard($id_card)
    {
        global $mysqli;
        $sql = $mysqli->prepare("DELETE FROM payment_card WHERE id_payment_card = ?");
        $sql->bind_param('i', $id_card);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function createTransaction($id_card, $id_order)
    {        
        global $mysqli;
        $sql = $mysqli->prepare("INSERT INTO transaction(payment_card_id_payment_card, order_idorder) VALUES(?, ?)");
        $sql->bind_param('ii', $id_card, $id_order);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return -1;
        return $sql->insert_id;
    }

    function updateTransactionFailed($id_transaction)
    {
        global $mysqli;
        $sql = $mysqli->prepare("UPDATE transaction SET failed = 1 WHERE idtransaction = ?");
        $sql->bind_param('i', $id_transaction);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function updateTransactionSuccess($id_transaction)
    {
        global $mysqli;
        $sql = $mysqli->prepare("UPDATE transaction SET processed = 1 WHERE idtransaction = ?");
        $sql->bind_param('i', $id_transaction);
        $sql->execute();
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }
?>