let configurator_data_reference = {
    //Procesor
    "cpu" : {
        "proizvajalec" : null,
        "podnozje" : null,
        "max_hitrost" : null,
        "ram_tip" : null,
        "ram_max_hitrost" : null,
        "ram_max_velikost_gb" : null,
        "tdp" : null,
        "price" : null,
        "id" : null
    },

    //Matična plošča
    "moab" : {
        "cpu_proizvajalec" : null,
        "cpu_max_hitrost" : null,
        "podnozje" : null,
        "ram_tip" : null,
        "ram_max_hitrost" : null,
        "ram_min_velikost_gb" : null,
        "ram_max_velikost_gb" : null,
        "ram_max_st_plosc" : null,
        "ram_max_velikost_gb_plosce" : null,
        "pcle" : null, // {tip_konektorja : st_teh_povezav, }
        "disk_konektor" : null, // {tip_konektorja : st_teh_povezav, }
        "bp_usb" : null, //{tip_usbja : st_vhodov, }
        "fp_usb" : null, //{tip_usbja : st_vhodov, }
        "fp_ah" : null, //{tip_usbja : st_vhodov, }
        "pin_konektorji" : null, //{tip_vhoda : st_vhodov, }
        "tip" : null,
        "price" : null,
        "id" : null
    },

    //RAM
    "ram" : {
        "tip" : null,
        "max_hitrost" : null,
        "st_plosc" : null,
        "velikost_gb_plosce" : null,
        "velikost_gb" : null,
        "tdp" : null,
        "price" : null,
        "id" : null
    },    

    //Grafična kartica
    "gpu" : [{
        "konektor" : null,
        "tdp" : null,
        "sli" : null,
        "dolzina" : null,
        "sirina" : null,
        "visina" : null,
        "psu_konektor" : null,
        "price" : null,
        "id" : null
    }],
    
    //Shramba  Mozno dodajanje vec objektov
    "storage" : [{
        "konektor" : null,
        "format": null,
        "tip" : null,
        "tdp" : null,
        "price" : null,
        "id" : null
    }],

    //Procesorsko hlajenje
    "cpu_cool" : {
        "tip" : null,
        "tdp" : null,
        "fans" : null,
        "podnozje_amd" : [null], // []
        "podnozje_intel" : [null],
        "konektor" : null,
        "price" : null,
        "id" : null
    },

    //Sistemsko hlajenje Array objektov
    "sys_cool" : [{
        "velikost_fan" : null,
        "konektor" : null,
        "tdp" : null,
        "price" : null,
        "id" : null
    }],

    //Napajalnik
    "psu" : {
        "watt" : null,
        "ucinkovitost" : null,
        "konektor" : null,
        "tip" : null,
        "price" : null,
        "id" : null
    },
    
    //Ohišje
    "case" : {
        "tip" : null,
        "moab_tip" : [null],
        "psu_tip" : [null],
        "usb" : null, //{tip_usbja : st_vhodov, }
        "ah" : null, //{tip_usbja : st_vhodov, }
        "sys_fan" : null, //{velikost: st}
        "diski" : null, //{tip_diska : st}
        // "gpu_dolzina" : null,
        // "gpu_sirina" : null,
        // "gpu_visina" : null,
        "price" : null,
        "id" : null
    }
};

// Obvezni podatki
let configurator_data = JSON.parse(JSON.stringify(configurator_data_reference));



let configurator_data_multiple_list = ["gpu", "storage", "sys_cool"];


function checkExists(check, where)
{
    for(const index in where)
        if(index == check)
            return 1;
    return 0;
}

function checkNull(where)
{
    if(Array.isArray(where))
        where = where[0];
    for(const index in where)
    {
        if(Array.isArray(where[index]))
        {
            if(where[index][0] != null)
                return 0;
            continue;
        }
        if(where[index] != null)
            return 0;
    }
    return 1;
}

function addConfiguratorData(data, id_group, display_index)
{
    const conf_data = configurator_data[id_group];

    // ce je id_group lahko array
    if(Array.isArray(conf_data))
    {
        if(display_index !== undefined)
        {
            configurator_data[id_group][display_index] = data;
            return 1;
        }
        for(let index in data)
        {
            if(!checkExists(index, conf_data[0]))
            {
                index = null;
            } 
    
            //Preveri ce je conf_data[index] lahko array
            if(Array.isArray(conf_data[0][index]))
                //Preveri ce je data[index] v obliki array
                if(!Array.isArray(data[index])) return "DATA[index] IS NOT ARRAY";
        }

        let index_pos = conf_data.length;
        if(checkNull(conf_data[0])) index_pos = 0;

        configurator_data[id_group][index_pos] = data;

        return 1;
    }

    // ce ni array
    for(const index in data)
    {
        if(!checkExists(index, conf_data))
        {
            return "INDEX DOES NOT EXIST";
        } 

        //Preveri ce je conf_data[index] lahko array
        if(Array.isArray(conf_data[index]))
            //Preveri ce je data[index] v obliki array
            {
                if(!Array.isArray(data[index])) return "DATA[index] IS NOT ARRAY";    
            }
            
    }

    configurator_data[id_group] = data;

    return 1;
}

function setNull(where)
{
    for(const index in where)
    {
        if(Array.isArray(where[index]))
            where[index] = [null];
        else
            where[index] = null;
    }
}

function removeConfiguratorData(id_group, index_of)
{
    const conf_data = configurator_data[id_group];

    if(Array.isArray(conf_data))
    {
        
        if(index_of === undefined)
            return "INDEX_OF NOT DEFINED"; 

        if(index_of >= conf_data.length)
            return "INDEX_OF OUT OF RANGE"; 

        // ce je samo en v arrayu ga nastavi na null / ce jih je vec jih odstrani
        if(conf_data.length == 1)
            setNull(configurator_data[id_group][0]);
        else
            configurator_data[id_group].splice(index_of, 1);

            fill_configurator_check();
        return 1;
    }

    setNull(configurator_data[id_group]);
    fill_configurator_check();
    return 1;
}


function changeConfiguratorData(id_group, index_of)
{
    delete configurator_data[id_group][index_of];

}


function getGroupId(text)
{
    switch(text)
    {
        case "Procesor": return "cpu";
        case "Matična plošča": return "moab";
        case "RAM": return "ram";
        case "Grafična kartica": return "gpu";
        case "Shramba": return "storage";
        case "Procesorsko hlajenje": return "cpu_cool";
        case "Sistemsko hlajenje": return "sys_cool";
        case "Napajalnik": return "psu";
        case "Ohišje": return "case";
        default: return null;
    }
}

function getGroupText(text)
{
    switch(text)
    {
        case "cpu": return "Procesor";
        case "moab": return "Matična plošča";
        case "ram": return "RAM";
        case "gpu": return "Grafična kartica";
        case "storage": return "Shramba";
        case "cpu_cool": return "Procesorsko hlajenje";
        case "sys_cool": return "Sistemsko hlajenje";
        case "psu": return "Napajalnik";
        case "case": return "Ohišje";
        default: return null;
    }
}

function check_group_is_array(id_group)
{
    return Array.isArray(configurator_data[id_group]);
}



