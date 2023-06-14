<?php
    $configurator_data = [
        //Procesor
        "cpu" => [
            "proizvajalec",
            "podnozje",
            "max_hitrost",
            "ram_tip",
            "ram_max_hitrost",
            "ram_max_velikost_gb",
            "tdp",
            "cena"
        ],

        //Matična plošča
        "moab" => [
            "cpu_proizvajalec",
            "cpu_max_hitrost",
            "podnozje",
            "ram_tip",
            "ram_max_hitrost",
            "ram_min_velikost_gb",
            "ram_max_velikost_gb",
            "ram_max_st_plosc",
            "ram_max_velikost_gb_plosce",
            "pcle", // [tip_konektorja => st_teh_povezav, ]
            "disk_konektor", // [tip_konektorja => st_teh_povezav, ]
            "bp_usb", //[tip_usbja => st_vhodov, ]
            "fp_usb", //[tip_usbja => st_vhodov, ]
            "fp_ah", //[tip_usbja => st_vhodov, ]
            "pin_konektorji", //[tip_vhoda => st_vhodov, ]
            "tip",
            "cena",
        ],

        //RAM
        "ram" => [
            "tip",
            "max_hitrost",
            "st_plosc",
            "velikost_gb_plosce",
            "velikost_gb",
            "tdp",
            "cena"
        ],    

        //Grafična kartica
        "gpu" => [
            "konektor",
            "tdp",
            "sli",
            "dolzina",
            "sirina",
            "visina",
            "psu_konektor",
            "cena"
        ],

        //Shramba  Mozno dodajanje vec objektov
        "storage" => [
            "konektor",
            "format",
            "tip",
            "tdp",
            "cena"
        ],

        //Procesorsko hlajenje
        "cpu_cool" => [
            "tip",
            "tdp",
            "fans",
            "podnozje_amd", // []
            "podnozje_intel",
            "konektor",
            "cena"
        ],

        //Sistemsko hlajenje Array objektov
        "sys_cool" => [
            "velikost_fan",
            "konektor",
            "tdp",
            "cena"
        ],

        //Napajalnik
        "psu" => [
            "watt",
            "ucinkovitost",
            "tip",
            "konektor",
            "cena"
        ],

        //Ohišje
        "case" => [
            "tip",
            "moab_tip",
            "psu_tip",
            "usb", //[tip_usbja => st_vhodov, ]
            "ah", //[tip_usbja => st_vhodov, ]
            "sys_fan", //[velikost: st]
            "diski", //[tip_diska => st]
            // "gpu_dolzina",
            // "gpu_sirina",
            // "gpu_visina",
            "cena"
        ]
    ];

    function checkCorrectAttribute($attribute_name, $internal_category)
    {
        global $configurator_data;
        return in_array($attribute_name, $configurator_data[$internal_category]);
    }   
?>