<?php
    require_once("payment_functions.php");
    
    function process_payment()
    {
        $id_transaction = createTransaction($_POST["id_card"], $_POST["id_order"]);
        if($id_transaction == -1) return -1;

        // KODA KI OPRAVI TRANSAKCIJO
        $success = 1;
        if($success == 1)
        {
            updateTransactionSuccess($id_transaction);
            return 1;
        }
        else if ($success == 0)
        {
            updateTransactionFailed($id_transaction);
            return 0;
        } 
    }

    
    
    $temp = process_payment();
    if($temp == -1) echo -1;
    if($temp == 1) echo 1;
    else if($temp == 0) echo 0;
?>