<?php
    header('charset=utf-8');
    require_once("../connect_db.php");

    function getComponents($id_group)
    {
        global $mysqli;

        try{
            $components = $mysqli->prepare("SELECT c.itemName, a.idcomponent_attribute, c_a.value FROM Component c 
                                            INNER JOIN component_has_component_attribute c_a ON (c_a.component_idcomponent = c.idcomponent AND c_a.active = 1)
                                            INNER JOIN component_attribute a ON (c_a.component_attributes_idcomponent_attribute = a.idcomponent_attribute AND a.active = 1)
                                                WHERE c.id_group = ? AND c.active = 1");
            if($components===false)
                throw new Exception($mysqli->error);

            $components->bind_param("s", $id_group);
            if($components===false)
                throw new Exception($mysqli->error);
            
            $components->execute();
            $components = $components->get_result();

        }
        catch(Exception $e) {
            return($e->getMessage());
        }
        
        while ($row = $components->fetch_assoc()) 
            $rows[] = $row;
        return $rows; 
    }

    echo json_encode(getComponents($_POST["id_group"]));

?>