<?php
    require_once("configurator_functions.php");
    require_once("configurator_valid_functions.php");
    require_once("../Account/account_functions.php");
    require_once("../Account/account_login_functions.php");


    function insert_to_build($id_build, $data)
    {
        foreach($data as $id)
        {
            
            if(is_array($id) == 1)
            {
                foreach($id as $array_id)
                {
                    if(strlen($array_id) == 0) continue;
                    if(checkValidNum($array_id) == 0) return -1;
                    $t = add_component_to_build($id_build, $array_id);
                    if($t == 0) return -2;
                }
                continue;
            }
            if(strlen($id) == 0) continue;
            if(checkValidNum($id) == 0) return -3;
            if(add_component_to_build($id_build, $id) == 0) return -4;
        }
        return 1;
    }

    function main_save_build()
    {
        if(checkValidName($_POST["name"]) == 0) $errors[] = "build_name";
        if(checkUserLogin() == 0)
            if(validateEmail($_POST["email"]) == 0) $errors[] = "build_email";
        if(empty($_POST["description"]) == 0)
            if(checkValidDescription($_POST["description"]) == 0) $errors[] = "build_description";

        if(empty($errors) == 0) return $errors;

        $id_build = create_build();
        if($id_build == -1) return "main";

        $description = $_POST["description"];
        if(strlen($description) == 0) $description = NULL;

        if(update_description_in_build($description, $id_build) == 0) return "main 1";
        if(checkUserLogin() == 0)
        {
            if(update_email_in_build($_POST["email"], $id_build) == 0) return "main 2";
        }
        else 
        {
            $username = getLoginUsername();
            if(add_user_to_build($username, $id_build) == 0) return "main 3";
        }

        if(update_public_in_build($_POST["public"], $id_build) == 0) return "main 4";



        if(checkValidNum($_POST["data"]["price"]) == 0) return "main 5";
        if(checkValidNum($_POST["data"]["power_usage"]) == 0) return "main 6";
        if(checkValidNum($_POST["data"]["heat"]) == 0) return "main 7";

        if(update_data_in_build($id_build, $_POST["data"]["price"], $_POST["data"]["power_usage"], $_POST["data"]["heat"]) == 0) return "main 8";

        $temp = insert_to_build($id_build, $_POST["data"]["data"]);
        if($temp != 1) return "main9";

        return 1;
    }

    echo json_encode(main_save_build());
?>