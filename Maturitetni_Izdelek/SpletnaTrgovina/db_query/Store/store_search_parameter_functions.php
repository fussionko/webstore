<?php
    function sort_alphabet($a, $b)
    {
        if(strtoupper($a[1]["itemName"]) < strtoupper($b[1]["itemName"])) return -1;
        if(strtoupper($a[1]["itemName"]) > strtoupper($b[1]["itemName"])) return 1;
        return 0;
    }

    function sort_price_low($a, $b)
    {
        if($a[1]["price"] < $b[1]["price"]) return -1;
        if($a[1]["price"] > $b[1]["price"]) return 1;
        return 0;
    }

    function sort_price_high($a, $b)
    {
        if($a[1]["price"] < $b[1]["price"]) return 1;
        if($a[1]["price"] > $b[1]["price"]) return -1;
        return 0;
    }

    function sortSearchParams($sort_table, $param)
    {
        if($param == "abc")
        {
            if(!uasort($sort_table, "sort_alphabet")) return 0;
        }
        else if($param == "prclow")
        {
            if(!uasort($sort_table, "sort_price_low")) return 0;
        }
        else if($param == "prchigh")
        {
            if(!uasort($sort_table, "sort_price_high")) return 0;
        }
        return array_values($sort_table);
    }
?>