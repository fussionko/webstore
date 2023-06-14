<?php
    header('Content-Type: text/html; charset=utf-8');

    require_once("random_generator_tables.php");
    require_once("category_functions.php");
    require_once("insert_data_functions.php");
    require_once("configurator_filter.php");
    require_once('get_google_image.php'); // Nalozeno iz PHPClasses

    set_time_limit(0);
    
    function productAttributeToValue($attributes, $idItem)
    {
        foreach($attributes as $attribute_name => $attribute_value)
        {
            insertAttribute($attribute_name);

            if(is_array($attribute_value))
            {
                foreach($attribute_value as $value)
                    if(insertProductAttributeValue($attribute_name, $idItem, $value) == 0) return 0;
            }
            else if(insertProductAttributeValue($attribute_name, $idItem, $attribute_value) == 0) return 0;
        }
        return 1;
    }

    function componentAttributeToValue($component_attribute, $idItem, $internal_category)
    {
        foreach($component_attribute as $attribute_name => $attribute_value)
        {
            if(checkCorrectAttribute($attribute_name, $internal_category) == 0) continue; // filter ce se rabm ga doda
            insertComponentAttribute($attribute_name);
            if(is_array($attribute_value))
            {
                foreach($attribute_value as $value)
                    if(insertComponentAttributeValue($attribute_name, $idItem, $value) == 0) return 0;
            }
            else if(insertComponentAttributeValue($attribute_name, $idItem, $attribute_value) == 0) return 0;
        }
        return 1;
    }

    function search_categories($category, $parent_category)
    {
        global $set_categories_table;
        for($i = 0; $i < count($set_categories_table); $i++)
        {
            if($set_categories_table[$i]["name_category"] == $category)
                if($set_categories_table[$i]["name_parent_category"] == $parent_category)
                    return 1;
        }

        return 0;
    }
    
    function generateProduct()
    {
        global $table_categories, $table_proizvajalci, $table_names, $set_categories_table;

        $internal_category = $table_categories[array_rand($table_categories, 1)];
        $category = translateCategory($internal_category);

        $brand = $table_proizvajalci[$internal_category][array_rand($table_proizvajalci[$internal_category])];
        $itemName = $table_names[$internal_category][$brand][array_rand($table_names[$internal_category][$brand])]; //funkcija ki doda ime
        $attributes = getAttributes($internal_category, $brand);
        $price = calculatePrice($internal_category);
        $image_paths = addImage($itemName, rand(1, 4));

        $temp = [$internal_category, $category, $brand, $itemName, $attributes, $price, $image_paths];

        if(!search_categories($category, 'x'))
        {
            $t = insertCategory($category, 'x');
            if($t != 1)
            {
                print_r($t);
                echo '   ';
                print_r($brand);
                echo '   ';
                print_r($category);
                echo '   ';
                print_r($set_categories_table);
                print_r($temp);
                return "ERROR => insertCategory - main: ".$category.', '.$internal_category;
            }
                
            array_push($set_categories_table, ["name_category" => $category, "name_parent_category" => 'x']);
        }

        if(!search_categories($brand, $category))
        {
            $t = insertCategory($brand, $category);
            if($t != 1)
            {
                print_r($t);
                echo '   ';
                print_r($brand);
                echo '   ';
                print_r($category);
                echo '   ';
                print_r($set_categories_table);
                print_r($temp);
                return "ERROR => insertCategory - sub ";
            }
               
            array_push($set_categories_table, ["name_category" => $brand, "name_parent_category" => $category]);
        }
      

        $idItem = insertProduct($itemName, $brand, $category);
        if($idItem == -1)
        {
            if(deleteFileImages($image_paths) != 1) return "ERROR => insertProduct - deleteFileImage: ";
            return "ERROR => insertProduct";
        } 
        if(productAttributeToValue($attributes, $idItem) == 0)
        {
            if(deleteFileImages($image_paths) != 1) return "ERROR => productAttributeToValue - deleteFileImage: ";
            removeProduct($idItem, $brand, $category);
            return "ERROR => productAttributeToValue";
        }
        if(insertPriceToProduct($idItem, $price) != 1)
        {
            if(deleteFileImages($image_paths) != 1) return "ERROR => insertPriceToProduct - deleteFileImage: ";
            removeProduct($idItem, $brand, $category);
            return "ERROR => insertPriceToProduct";
        }

        //-------------------------------------------------------------------------------------------

        $idComponent = insertComponent($itemName, $internal_category, $price, $idItem);
        if($idComponent == -1)
        {
            if(deleteFileImages($image_paths) != 1) return "ERROR => insertComponent - deleteFileImage: ";
            removeProduct($idItem, $brand, $category);
            return "ERROR => insertComponent";
        }
        if(componentAttributeToValue($attributes, $idComponent, $internal_category) == 0)
        {
            removeProduct($idItem, $brand, $category);
            removeComponent($idComponent, $internal_category);
            return "ERROR => componentAttributeToValue";
        }

        //-------------------------------------------------------------------------------------------

        if(insertImageToComponent($idComponent, $internal_category, $image_paths[1]) != 1)
        {
            if(deleteFileImages($image_paths) != 1) return "ERROR => insertImageToComponent - deleteFileImage: ";
            removeProduct($idItem, $brand, $category);
            removeComponent($idComponent, $internal_category);
            return "ERROR => insertImageToComponent";
        }
        if(insertImageToProduct($itemName, $idItem, $image_paths) != 1)
        {
            if(deleteFileImages($image_paths) != 1) return "ERROR => insertImageToProduct - deleteFileImage: ";
            removeProduct($idItem, $brand, $category);
            removeComponent($idComponent, $internal_category);
            return "ERROR => insertImageToProduct";
        }

        //-------------------------------------------------------------------------------------------
        return 1;
    }

    if(!isset($_POST["num_of_generations"]))
    {
        echo 0;
        return;
    }

    $set_categories_table = getAllCategories();
    if($set_categories_table == -1)
        $set_categories_table = [];

    for($i = 0; $i < $_POST["num_of_generations"]; $i++)
    {
        $product_result = generateProduct();
        if($product_result != 1)
        {
            echo json_encode(["error_msg" => $product_result, "i_item" => $i]);
            return;
        }
    }
    echo 1;
?>