<?php
    require_once("../db_query/querys.php");

    $result_category = getCategory("all");
    
    if(count($result_category) > 0)
        loadCategory($result_category);
    else
        echo '<li>'.'Ni kategorij'.'</li>';
    

    // Rekurzivno nalozi vse kategorije
    function loadCategory($category)
    {
        foreach($category as $cat)
        {
            echo '<li class="category"><span class="category-name">'.$cat["name_category"].'</span>';
            $subCategory = getCategory($cat["name_category"]);
            if(count($subCategory) > 0)
            {
                echo '<ul class="sub">';
                if(is_array($subCategory))
                    loadCategory($subCategory);
                echo '</ul>';
            }
            echo '</li>';
        }
    }
?>