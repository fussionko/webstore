<?php
    header('Content-Type: text/html; charset=utf-8');
    
    function cpu($brand)
    {
        global $table_podnozje, $table_cpu_max_hitrost, $table_ram_tip,
                $table_ram_max_hitrost, $table_ram_max_velikost_gb, $table_cpu_tdp;
        $atr = [];
        
        $atr["proizvajalec"] = $brand;
        $atr["podnozje"] = $table_podnozje[$atr["proizvajalec"]][array_rand($table_podnozje[$atr["proizvajalec"]])];
        $atr["max_hitrost"] = rand($table_cpu_max_hitrost[0], $table_cpu_max_hitrost[1]);
        $atr["ram_tip"] = $table_ram_tip[array_rand($table_ram_tip)];
        $atr["ram_max_hitrost"] = $table_ram_max_hitrost[$atr["ram_tip"]][array_rand($table_ram_max_hitrost[$atr["ram_tip"]])];
        $atr["ram_max_velikost_gb"] = $table_ram_max_velikost_gb[array_rand($table_ram_max_velikost_gb)];
        do
        {
            $c = rand($table_cpu_tdp[0], $table_cpu_tdp[1]);
            if($c % $table_cpu_tdp["mod"] == 0)
            {
                $atr["tdp"] = $c;
                break;
            }
        } while(1==1);


        return $atr;   
    }

    function moab($brand)
    {
        global $table_podnozje, $table_cpu_max_hitrost, $table_ram_tip,
                $table_ram_max_hitrost, $table_ram_max_velikost_gb, $table_pcle,
                $table_ram_min_velikost_gb, $table_ram_max_st_plosc, $table_disk_konektor,
                $table_ram_max_velikost_gb_plosce, $table_usb, $table_pin_konektor,
                $table_moab_tip, $table_proizvajalci, $table_ah;
        $atr = [];
        
        $atr["znamka"] = $brand;
        $atr["cpu_proizvajalec"] = $table_proizvajalci["cpu"][array_rand($table_proizvajalci["cpu"])];
        $atr["podnozje"] = $table_podnozje[$atr["cpu_proizvajalec"]][array_rand($table_podnozje[$atr["cpu_proizvajalec"]])];
        $atr["cpu_max_hitrost"] = rand($table_cpu_max_hitrost[0], $table_cpu_max_hitrost[1]);
        $atr["ram_tip"] = $table_ram_tip[array_rand($table_ram_tip)];
        $atr["ram_max_hitrost"] = $table_ram_max_hitrost[$atr["ram_tip"]][array_rand($table_ram_max_hitrost[$atr["ram_tip"]])];
        $atr["ram_max_velikost_gb"] = $table_ram_max_velikost_gb[array_rand($table_ram_max_velikost_gb)];
        $atr["ram_min_velikost_gb"] = $table_ram_min_velikost_gb[array_rand($table_ram_min_velikost_gb)];
        do
        {
            $atr["ram_min_velikost_gb"] = $table_ram_min_velikost_gb[array_rand($table_ram_min_velikost_gb)];
            if($atr["ram_min_velikost_gb"] < $atr["ram_max_velikost_gb"])
                break;
        } while(1==1);
        do
        {
            $atr["ram_max_st_plosc"] = $table_ram_max_st_plosc[array_rand($table_ram_max_st_plosc)];
            $atr["ram_max_velikost_gb_plosce"] = $table_ram_max_velikost_gb_plosce[array_rand($table_ram_max_velikost_gb_plosce)];
            if($atr["ram_max_st_plosc"] * $atr["ram_max_velikost_gb_plosce"] == $atr["ram_max_velikost_gb"])
                break;
        } while(1==1);
        
        $atr["pcle"] = addArrayValues($table_pcle);
        $atr["disk_konektor"] = addArrayValues($table_disk_konektor);
        $atr["bp_usb"] = addArrayValues($table_usb);
        $atr["fp_usb"] = addArrayValues($table_usb);
        $atr["pin_konektorji"] = addArrayValues($table_pin_konektor);
        $atr["fp_ah"] = addArrayValues($table_ah);

        $atr["tip"] = $table_moab_tip[array_rand($table_moab_tip)];


        return $atr;
    }

    function ram($brand)
    {
        global $table_ram_tip, $table_ram_max_hitrost, $table_ram_max_velikost_gb_plosce,
                $table_ram_st_ploscic, $table_ram_tdp;

        $atr = [];

        $atr["znamka"] = $brand;
        $atr["tip"] = $table_ram_tip[array_rand($table_ram_tip)];
        $atr["max_hitrost"] = $table_ram_max_hitrost[$atr["tip"]][array_rand($table_ram_max_hitrost[$atr["tip"]])];
        $atr["velikost_gb_plosce"] = $table_ram_max_velikost_gb_plosce[array_rand($table_ram_max_velikost_gb_plosce)];
        $atr["st_plosc"] = $table_ram_st_ploscic[array_rand($table_ram_st_ploscic)];
        $atr["velikost_gb"] = $atr["velikost_gb_plosce"] * $atr["st_plosc"];

        $atr["tdp"] = $atr["velikost_gb"]/$table_ram_tdp[$atr["tip"]]["per"]*$table_ram_tdp[$atr["tip"]]["tdp"];



        return $atr;
    }

    function gpu($brand)
    {
        global $table_gpu_dolzina, $table_gpu_sirina, $table_gpu_visina, $table_gpu_hitrost, $table_gpu_cip,
                $table_gpu_poraba, $table_gpu_konektor, $table_pcle, $table_gpu_sli;

        $atr = [];

        $atr["znamka"] = $brand;
        do{
            $atr["proizvajalec"] = array_rand($table_gpu_cip);
            if($atr["proizvajalec"] == 'Nvidia' && $atr["znamka"] != 'AMD')
                break;
            if($atr["proizvajalec"] == 'AMD' && $atr["znamka"] != 'Nvidia')
                break;
        }while(1==1);
        
        $atr["dolzina"] = rand($table_gpu_dolzina[0], $table_gpu_dolzina[1]);
        $atr["sirina"] = rand($table_gpu_sirina[0], $table_gpu_sirina[1]);
        $atr["visina"] = rand($table_gpu_visina[0], $table_gpu_visina[1]);
        $atr["hitrost"] = rand($table_gpu_hitrost[0], $table_gpu_hitrost[1]);
        $atr["cip"] = $table_gpu_cip[$atr["proizvajalec"]][array_rand($table_gpu_cip[$atr["proizvajalec"]])];
        $atr["tdp"] = rand($table_gpu_poraba[0], $table_gpu_poraba[1]);
        if($atr["tdp"]>=100)
        {
            if($atr["tdp"] >= 150 && $atr["tdp"] < 200)
                $atr["psu_konektor"] = $table_gpu_konektor[0];
            else if($atr["tdp"] >= 200 && $atr["tdp"] < 250)
                $atr["psu_konektor"] = $table_gpu_konektor[1];
            else if($atr["tdp"] >= 250 && $atr["tdp"] < 300)
                $atr["psu_konektor"] = $table_gpu_konektor[2];
            else 
                $atr["psu_konektor"] = $table_gpu_konektor[2];
        }
        $atr["konektor"] = array_rand($table_pcle);
        $atr["sli"] = rand($table_gpu_sli[0], $table_gpu_sli[1]);

        return $atr;
    }

    function storage($brand)
    {
        global $table_disk_konektor, $table_storage_tip, $table_disk_form_factor, $table_disk_velikost,
                $table_storage_hitrost_branja_pisanja, $table_storage_tdp;

        $atr = [];
        $atr["znamka"] = $brand;
        $atr["konektor"] = array_rand($table_disk_konektor);
        $atr["tip"] = $table_storage_tip[array_rand($table_storage_tip)];
        if($atr["konektor"] != "sata6")
            $atr["format"] = $atr["konektor"];
        else
            $atr["format"] = $table_disk_form_factor[$atr["konektor"]][array_rand($table_disk_form_factor[$atr["konektor"]])];
        
        $atr["velikost"] = $table_disk_velikost[$atr["tip"]][array_rand($table_disk_velikost[$atr["tip"]])];
        $atr["branje/pisanje"] = $table_storage_hitrost_branja_pisanja[$atr["tip"]][array_rand($table_storage_hitrost_branja_pisanja[$atr["tip"]])];
        $atr["tdp"] = $table_storage_tdp[$atr["tip"]][array_rand($table_storage_tdp[$atr["tip"]])];
        return $atr;
    }

    function psu($brand)
    {
        global $table_psu_watt, $table_psu_ucinkovitost, $table_psu_tip, $table_psu_konektor;
        $atr = [];
        $atr["znamka"] = $brand;
        do
        {
            $atr["watt"] = rand($table_psu_watt[0], $table_psu_watt[1]);
            if($atr["watt"] % $table_psu_watt["mod"] == 0)
                break;
        } while(1==1);

        $atr["ucinkovitost"] = rand($table_psu_ucinkovitost[0]*100, $table_psu_ucinkovitost[1]*100)/100;
        $atr["tip"] = $table_psu_tip[array_rand($table_psu_tip)];
        $atr["konektor"] = addArrayValues($table_psu_konektor[$atr["tip"]]);
        return $atr;
    }

    function pc_case($brand)
    {
        global $table_case_tip, $table_case_moab, $table_psu_tip, $table_case_sys_fan, $table_case_disk,
                $table_gpu_visina, $table_gpu_sirina, $table_gpu_dolzina, $table_usb, $table_ah,
                $table_fan_size;

        $atr = [];
        $atr["znamka"] = $brand;
        $atr["tip"] = $table_case_tip[array_rand($table_case_tip)];
        $atr["moab_tip"] = $table_case_moab[$atr["tip"]];
        $atr["psu_tip"] = $atr["moab_tip"];
        $atr["st_sys_f"] = rand($table_case_sys_fan[$atr["tip"]][0], $table_case_sys_fan[$atr["tip"]][1]);
        $atr["sys_fan"] = [];
        $temp = [80 => 0, 120 => 0, 140 => 0, 180 => 0, 200 => 0];
        for($i = 0; $i < $atr["st_sys_f"]; $i++)
            $temp[$table_fan_size[array_rand($table_fan_size)]] += 1;

        foreach($temp as $name => $value)
        {
            if($value == 0) continue;
            $atr["sys_fan"][]  = $value.':'.$name;
        }

        $atr["diski"] = addArrayValues($table_case_disk[$atr["tip"]]);

        $atr["usb"] = [];
        foreach($table_usb as $name => $values)
        {
            $x = rand($values[0], $values[1]);
            if($x == 0) continue;
            $atr["usb"][]  .= $x.':'.$name;
        }

        $atr["ah"] = addArrayValues($table_ah);
        
        return $atr;
    }

    function sys_cool($brand)
    {        
        global $table_fan_size, $table_fan_konektor, $table_fan_tdp, $table_fan_hitrost, $table_fan_tdp;

        $atr = [];
        $atr["znamka"] = $brand;
        $atr["velikost_fan"] = $table_fan_size[array_rand($table_fan_size)];
        $atr["konektor"] = $table_fan_konektor[array_rand($table_fan_konektor)];
        $atr["tdp"] = rand($table_fan_tdp[$atr["velikost_fan"]][0]*100, $table_fan_tdp[$atr["velikost_fan"]][1]*100)/100;
        do{
            $atr["hitrost"] = rand($table_fan_hitrost[0], $table_fan_hitrost[1]);
            if($atr["hitrost"] % $table_fan_hitrost["mod"] == 0)
                break;
        }while(1==1);
        

        return $atr;
    }

    function cpu_cool($brand)
    {
        global $table_cpu_cool_type, $table_podnozje, $table_fan_konektor, $table_sys_cool_st_fan,
                $table_fan_size;

        $atr = [];
        $atr["znamka"] = $brand;
        $atr["tip"] = '';
        if(rand(0, 10)/10 < $table_cpu_cool_type["water"])
            $atr["tip"] = "vodno_hlajenje";
        else
            $atr["tip"] = "zracno_hlajenje";

        $atr["podnozje"] = [];
        foreach(array_rand($table_podnozje["Intel"], rand(2, count($table_podnozje["Intel"]))) as $key)
            $atr["podnozje_amd"][] = $table_podnozje["Intel"][$key];
        foreach(array_rand($table_podnozje["AMD"], rand(2, count($table_podnozje["AMD"]))) as $key)
            $atr["podnozje_intel"][] = $table_podnozje["AMD"][$key];
        $atr["st_fan"] = rand($table_sys_cool_st_fan[0], $table_sys_cool_st_fan[1]);
        $atr["konektor"] = [];
        $atr["konektor"][] = '1:'.$table_fan_konektor[array_rand($table_fan_konektor)];
        $atr["konektor"][] = "1:cpu_fan_header";
        if($atr["tip"] == "vodno_hlajenje")
            $atr["konektor"][] = '1:water_cooling_cpu_fan_header';

        $atr["fans"] = [];
        $temp = [80 => 0, 120 => 0, 140 => 0, 180 => 0, 200 => 0];
        for($i = 0; $i < $atr["st_fan"]; $i++)
            $temp[$table_fan_size[array_rand($table_fan_size)]] += 1;

        foreach($temp as $name => $value)
        {
            if($value == 0) continue;
            $atr["fans"][]  = $value.':'.$name;
        }
        $atr["tdp"] = 3*$atr["st_fan"];
        if($atr["tip"] == "vodno_hlajenje")
            $atr["tdp"] += 10;

        return $atr;
    }

    function calculatePrice($internal_category)
    {
        global $table_cene;
        return rand($table_cene[$internal_category][0], $table_cene[$internal_category][1]);
    }

    function addArrayValues($table)
    {
        $result = [];
        foreach($table as $name => $values)
        {
            $rand = rand($values[0], $values[1]);
            if($rand == 0) continue;
            $result[] = $rand.':'.$name;
        }
            
        return $result;
    }

    function getAttributes($category, $brand)
    {
        switch($category)
        {
            case "cpu" : $attributes = cpu($brand); break;
            case "moab" : $attributes = moab($brand); break;
            case "ram" : $attributes = ram($brand); break;
            case "gpu" : $attributes = gpu($brand); break;
            case "psu" : $attributes = psu($brand); break;
            case "storage" : $attributes = storage($brand); break; 
            case "cpu_cool" : $attributes = cpu_cool($brand); break;
            case "sys_cool" : $attributes = sys_cool($brand); break;
            case "case" : $attributes = pc_case($brand); break;
        }
        return $attributes;
    }
    
    function translateCategory($internal_category)
    {
        global $table_categories_translate;
        return $table_categories_translate[$internal_category];
    }

    function deleteFileImages($paths)
    {
        foreach($paths as $path)
            if(unlink($path) == 0) return $path;
        return 1;
    }

    function addImage($name, $num_of_images)
    {
        $google_image = new get_google_image_class;
        $google_image->destination = '../images/product_image'; 
        $google_image->limit = $num_of_images; 
        $google_image->display = false;
        $google_image->GetImage($name);
        return $google_image->paths;
    }
?>