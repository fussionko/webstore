<?php
    require_once("../connect_db.php");

    function getComponentData($id_component)
    {
        global $mysqli;
        try{
            $components = $mysqli->prepare("SELECT c.idcomponent, a.idcomponent_attribute, c_a.value, c.price, c.image_location FROM Component c 
                                            INNER JOIN component_has_component_attribute c_a ON (c_a.component_idcomponent = c.idcomponent AND c_a.active = 1)
                                            INNER JOIN component_attribute a ON (c_a.idcomponent_attribute = a.idcomponent_attribute AND a.active = 1)
                                             WHERE c.idcomponent = ? AND c.active = 1");
            if($components===false)
                throw new Exception($mysqli->error);

            $components->bind_param("s", $id_component);
            if($components===false)
                throw new Exception($mysqli->error);
            
            $components->execute();
            $components = $components->get_result();

        }
        catch(Exception $e) {
            return($e->getMessage());
        }

        $rows = [];
        while ($row = $components->fetch_assoc()) 
        {
            $rows["price"] = $row["price"];
            $rows["image_location"] = $row["image_location"];
            $rows["attributes"][$row["idcomponent_attribute"]][] = $row["value"];
        }
            
        return $rows; 
    }

    function getComponents($id_group)
    {
        global $mysqli;
        try{
            $components = $mysqli->prepare("SELECT c.itemName, c.idcomponent, a.idcomponent_attribute, c_a.value FROM Component c 
                                            INNER JOIN component_has_component_attribute c_a ON (c_a.component_idcomponent = c.idcomponent AND c_a.active = 1)
                                            INNER JOIN component_attribute a ON (c_a.idcomponent_attribute = a.idcomponent_attribute AND a.active = 1)
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
        
        $rows = [];
        while ($row = $components->fetch_assoc()) 
        {
            $id_component = $row["idcomponent"];
            unset($row["idcomponent"]);
            $rows[$id_component]["itemName"] = $row["itemName"];
            $rows[$id_component]["attributes"][$row["idcomponent_attribute"]][] = $row["value"];
        }
            
        return $rows; 
    }

    function getComponentImage($id_component)
    {
        global $mysqli;
        try{
            $sql = $mysqli->prepare("SELECT image_location FROM Component WHERE idcomponent = ? AND active = 1");
            if($sql===false)
                throw new Exception($mysqli->error);

            $sql->bind_param("i", $id_component);
            if($sql===false)
                throw new Exception($mysqli->error);
            
            $sql->execute();
            $sql = $sql->get_result();

        }
        catch(Exception $e) {
            return($e->getMessage());
        }

        $sql = $sql->fetch_assoc();
        return $sql["image_location"]; 
    }

    function add_component_to_build($id_build, $id_component)
    {
        global $mysqli;
        try{
            $sql = $mysqli->prepare("INSERT INTO component_in_build(build_idbuild, component_idcomponent) VALUES (?, ?)");
            if($sql===false)
                throw new Exception($mysqli->error);
            $sql->bind_param("ii", $id_build, $id_component);
            if($sql===false)
                throw new Exception($mysqli->error);
            $sql->execute();
        }
        catch(Exception $e) {
            return($e->getMessage());
        }
        
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function remove_component_from_build($id_build, $id_component)
    {
        global $mysqli;
        try{
            $sql = $mysqli->prepare("DELETE FROM component_in_build WHERE build_idbuild = ? AND component_idcomponent = ?");
            if($sql===false)
                throw new Exception($mysqli->error);
            $sql->bind_param("ii", $id_build, $id_component);
            if($sql===false)
                throw new Exception($mysqli->error);
            $sql->execute();
        }
        catch(Exception $e) {
            return($e->getMessage());
        }
        
        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function create_build()
    {
        global $mysqli;
        try{
            $sql = $mysqli->query("INSERT INTO build VALUES ()");
            if($sql===false)
                throw new Exception($mysqli->error);
        }
        catch(Exception $e) {
            return($e->getMessage());
        }
        
        if($mysqli->insert_id == -1)
            return -1;
        return $mysqli->insert_id;
    }

    function add_user_to_build($user, $id_build)
    {
        global $mysqli;
        try{
            $sql = $mysqli->prepare("UPDATE build SET user_username = ? WHERE idbuild = ?");
            if($sql===false)
                throw new Exception($mysqli->error);
            $sql->bind_param("si", $user, $id_build);
            if($sql===false)
                throw new Exception($mysqli->error);
            $sql->execute();
        }
        catch(Exception $e) {
            return($e->getMessage());
        }

        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function update_name_in_build($name, $id_build)
    {
        global $mysqli;
        try{
            $sql = $mysqli->prepare("UPDATE build SET name = ? WHERE idbuild = ?");
            if($sql===false)
                throw new Exception($mysqli->error);
            $sql->bind_param("si", $name, $id_build);
            if($sql===false)
                throw new Exception($mysqli->error);
            $sql->execute();
        }
        catch(Exception $e) {
            return($e->getMessage());
        }

        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function update_public_in_build($public, $id_build)
    {
        global $mysqli;

        if($public == "true")
            $public = 1;
        else if($public == "false")
            $public = 0;
        else return 0;

        try{
            $sql = $mysqli->prepare("UPDATE build SET public = ? WHERE idbuild = ?");
            if($sql===false)
                throw new Exception($mysqli->error);
            $sql->bind_param("ii", $public, $id_build);
            if($sql===false)
                throw new Exception($mysqli->error);
            $sql->execute();
        }
        catch(Exception $e) {
            return($e->getMessage());
        }

        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }


    function update_data_in_build($id_build, $sum_price, $power_usage, $heat)
    {
        global $mysqli;
        try{
            $sql = $mysqli->prepare("UPDATE build SET sum_price = ?, power_usage = ?, heat = ? WHERE idbuild = ?");
            if($sql===false)
                throw new Exception($mysqli->error);
            $sql->bind_param("diii", $sum_price, $power_usage, $heat, $id_build);
            if($sql===false)
                throw new Exception($mysqli->error);
            $sql->execute();
        }
        catch(Exception $e) {
            return($e->getMessage());
        }

        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function update_description_in_build($description, $id_build)
    {
        global $mysqli;
        try{
            $sql = $mysqli->prepare("UPDATE build SET description = ? WHERE idbuild = ?");
            if($sql===false)
                throw new Exception($mysqli->error);
            $sql->bind_param("si", $description, $id_build);
            if($sql===false)
                throw new Exception($mysqli->error);
            $sql->execute();
        }
        catch(Exception $e) {
            return($e->getMessage());
        }

        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }

    function update_email_in_build($email, $id_build)
    {
        global $mysqli;
        try{
            $sql = $mysqli->prepare("UPDATE build SET email = ? WHERE idbuild = ?");
            if($sql===false)
                throw new Exception($mysqli->error);
            $sql->bind_param("si", $email, $id_build);
            if($sql===false)
                throw new Exception($mysqli->error);
            $sql->execute();
        }
        catch(Exception $e) {
            return($e->getMessage());
        }

        if($mysqli->affected_rows == -1)
            return 0;
        return 1;
    }
?>