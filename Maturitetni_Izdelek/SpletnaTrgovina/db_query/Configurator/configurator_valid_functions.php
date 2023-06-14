<?php
    function checkValidNum($number)
    {
        if(is_numeric($number) == 0) return 0;
        return 1;
    }

    function checkValidName($name)
    {
        if(empty($name) == 1) return 0;
        if(strlen($name) > 60) return 0;
        if(preg_match('/^[a-zA-ZčćđšžČĆĐŠŽ -?*_#!,.]*$/', $name) == 0) return 0;
        return 1;
    }

    function checkValidDescription($description)
    {
        if(strlen($description) > 500) return 0;
        if(preg_match('/^[a-zA-ZčćđšžČĆĐŠŽ -?*_#!,.]*$/', $description) == 0) return 0;
        return 1;
    }
?>