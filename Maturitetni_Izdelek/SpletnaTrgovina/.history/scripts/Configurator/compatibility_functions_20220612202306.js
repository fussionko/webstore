function compareSame(comp_data_property, saved_data_property)
{
    if(saved_data_property == null) return 1;
    if(saved_data_property == comp_data_property)
        return 1;
    return 0;
}

function compareLower(comp_data_property, saved_data_property)
{

    if(saved_data_property == null) return 1;
    saved_data_property = parseInt(saved_data_property);
    comp_data_property = parseInt(comp_data_property);
    if(saved_data_property >= comp_data_property)
        return 1;
    return 0;
}

function compareHigher(comp_data_property, saved_data_property)
{
    if(saved_data_property == null) return 1;
    saved_data_property = parseInt(saved_data_property);
    comp_data_property = parseInt(comp_data_property);
    if(saved_data_property <= comp_data_property)
        return 1;
    return 0;
}

function compareSameLoop(compare_loop, saved_data_property)
{
    if(saved_data_property === null) return 1;
    for(const element in compare_loop)
        if(compareSame(saved_data_property, compare_loop[element])) return 1;
    return 0;
}

function checkSameType(comp_data, saved_data)
{
    if(comp_data === undefined) return 1;
    if(saved_data == null) return 1;
    if(!saved_data.hasOwnProperty(comp_data)) return 0;
    return 1;
}

function compare_item(comp, check)
{
    if(comp == check) return 1;
    return 0;
}

let configurator_data_functions = {
    "cpu" : function(compare_data, display_index, search) 
        {
            //Moab
            if(!compareSame(compare_data["proizvajalec"], main_config["config_data"]["moab"]["cpu_proizvajalec"])) return 101;
            if(!compareSame(compare_data["podnozje"], main_config["config_data"]["moab"]["podnozje"])) return 102;
            if(!compareSame(compare_data["ram_tip"], main_config["config_data"]["moab"]["ram_tip"])) return 103;
            if(!compareHigher(compare_data["ram_max_hitrost"], main_config["config_data"]["moab"]["ram_max_hitrost"])) return 104;
            if(!compareHigher(compare_data["ram_max_velikost_gb"], main_config["config_data"]["moab"]["ram_max_velikost_gb"])) return 105;

            //Ram
            if(!compareSame(compare_data["ram_tip"], main_config["config_data"]["ram"]["tip"])) return 105;
            if(!compareHigher(compare_data["ram_max_hitrost"], main_config["config_data"]["ram"]["max_hitrost"])) return 107;
            if(!compareHigher(compare_data["ram_max_velikost_gb"], main_config["config_data"]["ram"]["velikost_gb"])) return 108;

            //Cpu_cool
            if(compare_data["proizvajalec"] == "amd")
                if(!compareSameLoop(main_config["config_data"]["cpu_cool"]["podnozje_amd"], compare_data["podnozje"])) return 109;   
            else if(compare_data["proizvajalec"] == "intel")
                if(!compareSameLoop(main_config["config_data"]["cpu_cool"]["podnozje_intel"], compare_data["podnozje"])) return 110;   

            //poraba
            if(!compare_safe_power_usage(compare_data["tdp"], "cpu")) return 111;

            //cpu - heat -- sys_cool

            return 1;
        },
    
    "moab" : function(compare_data, display_index, search)
        {
            //Processor
            if(!compareSame(compare_data["cpu_proizvajalec"], main_config["config_data"]["cpu"]["proizvajalec"])) return 201;
            if(!compareSame(compare_data["podnozje"], main_config["config_data"]["cpu"]["podnozje"])) return 202;
            if(!compareSame(compare_data["ram_tip"], main_config["config_data"]["cpu"]["ram_tip"])) return 203;
            if(!compareLower(compare_data["ram_max_hitrost"], main_config["config_data"]["cpu"]["ram_max_hitrost"])) return 204;
            if(!compareLower(compare_data["ram_max_velikost_gb"], main_config["config_data"]["cpu"]["ram_max_velikost_gb"])) return 205;

            
            //Ram
            if(!compareSame(compare_data["ram_tip"], main_config["config_data"]["ram"]["tip"])) return 206;
            if(!compareHigher(compare_data["ram_max_hitrost"], main_config["config_data"]["ram"]["max_hitrost"])) return 207;
            if(!compareHigher(compare_data["ram_max_velikost_gb"], main_config["config_data"]["ram"]["velikost_gb"])) return 208;
            if(!compareLower(compare_data["ram_min_velikost_gb"], main_config["config_data"]["ram"]["velikost_gb"])) return 209;
            if(!compareHigher(compare_data["ram_max_st_plosc"], main_config["config_data"]["ram"]["st_plosc"])) return 210;
            if(!compareHigher(compare_data["ram_max_velikost_gb_plosce"], main_config["config_data"]["ram"]["velikost_gb_plosce"])) return 211;
            
            //Gpu
            if(!check_part_count(compare_data["pcle"], "gpu", "konektor")) return 212;

            //Storage
            if(!check_part_count(compare_data["disk_konektor"], "storage", "konektor")) return 213;

           
            let check_in_podnozje = 0;
            if(compareSameLoop(main_config["config_data"]["cpu_cool"]["podnozje_amd"], compare_data["podnozje"])) 
                check_in_podnozje = 1;

            if(compareSameLoop(main_config["config_data"]["cpu_cool"]["podnozje_intel"], compare_data["podnozje"])) check_in_podnozje = 1;
            if(check_in_podnozje == 0) return 215;
                
            //sys_cool cpu_cool
            if(!custom_moab_fan_eval(compare_data["pin_konektorji"])) return 217;
                
            //Case
            if(!compareSameLoop(main_config["config_data"]["case"]["moab_tip"], compare_data["tip"])) return 218;

            return 1;
        },

    "ram" : function(compare_data, display_index, search)
        {
            //cpu
            if(!compareSame(compare_data["tip"], main_config["config_data"]["cpu"]["ram_tip"])) return 301;
            if(!compareLower(compare_data["max_hitrost"], main_config["config_data"]["cpu"]["ram_max_hitrost"])) return 302;
            if(!compareLower(compare_data["velikost_gb"], main_config["config_data"]["cpu"]["ram_max_velikost_gb"])) return 303;

            //moab
            if(!compareSame(compare_data["tip"], main_config["config_data"]["moab"]["ram_tip"])) return 304;
            if(!compareLower(compare_data["max_hitrost"], main_config["config_data"]["moab"]["ram_max_hitrost"])) return 305;
            if(!compareLower(compare_data["velikost_gb"], main_config["config_data"]["moab"]["ram_max_velikost_gb"])) return 306;
            if(!compareHigher(compare_data["velikost_gb"], main_config["config_data"]["moab"]["ram_min_velikost_gb"])) return 307;
            if(!compareLower(compare_data["velikost_gb_plosce"], main_config["config_data"]["moab"]["ram_max_velikost_gb_plosce"])) return 308;
            if(!compareLower(compare_data["st_plosc"], main_config["config_data"]["moab"]["ram_max_st_plosc"])) return 309;
            //poraba
            if(!compare_safe_power_usage(compare_data["tdp"], "ram")) return 310;
            return 1;
        },
    
    "gpu" : function(compare_data, display_index, search)
        {
            //moab
            if(!checkSameType(compare_data["konektor"], main_config["config_data"]["moab"]["pcle"])) return 401;
            if(search == 1)
                if(display_index >= main_config["config_data"]["gpu"].length)
                    if(!check_add("moab", "pcle", compare_data["konektor"])) return 402;
  
            if(!check_num_allowed(compare_data["konektor"], "moab", "pcle"))
                if(!compare_item(compare_data["konektor"], configurator_check["gpu"][display_index]["konektor"])) return 403;


            // //case
            // if(!compareLower(compare_data["dolzina"], main_config["config_data"]["case"]["gpu_dolzina"])) return 411;
            // if(!compareLower(compare_data["sirina"], main_config["config_data"]["case"]["gpu_sirina"])) return 412;
            // if(!compareLower(compare_data["visina"], main_config["config_data"]["case"]["gpu_visina"])) return 413;

            //psu
            if(!checkSameType(compare_data["psu_konektor"], main_config["config_data"]["psu"]["konektor"])) return 414;
            if(search == 1)
                if(display_index >= main_config["config_data"]["gpu"].length)
                    if(!check_add("psu", "konektor", compare_data["psu_konektor"])) return 415;

            if(!check_num_allowed(compare_data["psu_konektor"], "psu", "konektor"))
                if(!compare_item(compare_data["psu_konektor"], configurator_check["gpu"][display_index]["psu_konektor"])) return 416;

            //poraba
            if(!compare_safe_power_usage(compare_data["tdp"], "gpu", display_index)) return 417;
            return 1;
        },
        
    "storage" : function(compare_data, display_index, search)
        {
            //moab
            if(!checkSameType(compare_data["konektor"], main_config["config_data"]["moab"]["disk_konektor"])) return 501;
            if(search == 1)
                if(display_index >= main_config["config_data"]["storage"].length)
                    if(!check_add("moab", "disk_konektor", compare_data["konektor"])) return 502;

            if(!check_num_allowed(compare_data["konektor"], "moab", "disk_konektor"))
                if(!compare_item(compare_data["konektor"], configurator_check["storage"][display_index]["konektor"])) return 503;



            //case
            if(configurator_storage_case.includes(compare_data["format"]))
            {
                if(!checkSameType(compare_data["format"], main_config["config_data"]["case"]["diski"])) return 505;
                if(!check_num_allowed(compare_data["format"], "case", "diski"))
                    if(!compare_item(compare_data["format"], configurator_check["storage"][display_index]["format"])) return 507;
            }
                
            if(search == 1)
                if(display_index >= main_config["config_data"]["storage"].length)
                    if(!check_add("case", "diski", compare_data["format"])) return 506;
       
            //poraba
            if(!compare_safe_power_usage(compare_data["tdp"], "storage", display_index)) return 504;


            return 1;
        },

    "cpu_cool" : function(compare_data, display_index, search)
        {
            //cpu - heat

            //moab
            for(const konektor in compare_data["konektor"])
                if(!checkSameType(translator_moab(konektor), main_config["config_data"]["moab"]["pin_konektorji"])) return 601;
            if(search == 1)
                if(!custom_cpu_cool(compare_data["konektor"], "cpu_cool", "moab", "konektor", "pin_konektorji")) return 604;
                
            //case
            for(const fan in compare_data["fans"])
                if(!checkSameType(fan, main_config["config_data"]["case"]["sys_fan"])) return 605;
            if(search == 1)
                if(!custom_cpu_cool(compare_data["fans"], "cpu_cool", "case", "fans", "sys_fan")) return 606;

            //poraba
            if(!compare_safe_power_usage(compare_data["tdp"], "cpu_cool")) return 607;
            return 1;
    
        },

    "sys_cool" : function(compare_data, display_index, search)
        {
            //moab
            if(!checkSameType(translator_moab(compare_data["konektor"]), main_config["config_data"]["moab"]["pin_konektorji"])) return 701;
            if(search == 1)
                if(display_index >= main_config["config_data"]["sys_cool"].length)
                    if(!check_add("moab", "pin_konektorji", translator_moab(compare_data["konektor"]))) return 702;

            if(!check_num_allowed(translator_moab(compare_data["konektor"]), "moab", "pin_konektorji"))
                if(!compare_item(compare_data["konektor"], configurator_check["sys_cool"][display_index]["konektor"])) return 703;
 
            //case 
            if(!checkSameType(compare_data["velikost_fan"], main_config["config_data"]["case"]["sys_fan"])) return 705;
            if(search == 1)
                if(display_index >= main_config["config_data"]["sys_cool"].length)
                    if(!check_add("case", "sys_fan", compare_data["velikost_fan"])) return 706;
            
            //poraba
            if(!compare_safe_power_usage(compare_data["tdp"], "sys_cool", display_index)) return 704;

            return 1;
        },

    "psu" : function(compare_data)
        {
            //case
            if(!compareSameLoop(main_config["config_data"]["case"]["psu_tip"], compare_data["tip"])) return 801;

            //poraba
            if(!check_psu_power(compare_data["watt"], compare_data["ucinkovitost"])) return 802;
            
            //gpu
            if(!check_part_count(compare_data["konektor"], "gpu", "psu_konektor")) return 803;

            return 1;
        },

    "case" : function(compare_data)
        {
            //moab
            if(!compareSameLoop(compare_data["moab_tip"], main_config["config_data"]["moab"]["tip"], )) return 901;

            //cpu_cool sys_cool
            if(!custom_case_fan_eval(compare_data["sys_fan"])) return 902;
      
            //psu
            if(!compareSameLoop(compare_data["psu_tip"], main_config["config_data"]["psu"]["tip"])) return 902;

            //storage
            if(!check_part_count_special(compare_data["diski"], "storage", "konektor", configurator_storage_case)) return 906;
            
            return 1;
        },
};

let main_config = {
    "heat" : null,
    "power_usage" : null,
    "price" : null,
    "config_data" : null
};

function updateMainConfig()
{
    main_config["config_data"] = configurator_data;
    main_config["heat"] = calculateHeat();
    main_config["power_usage"] = calculatePowerUsage();
    main_config["price"] = calculatePrice();

}

function transformData(data, id)
{
    let usable_data = {};
    for(const key in data)
    {
        if(data[key].length > 1)
        {
            let obj;
            if(data[key][0].includes(":"))
            {
                obj = {};
                for(let i = 0; i < data[key].length; i++)
                {
                    let split = data[key][i].split(":");
                    obj[split[1]] = split[0]; 
                }
            } 
            else obj = data[key];
            
            usable_data[key] = obj;
        }
        else
        {
            if(data[key][0].includes(":"))
            {
                let obj = {};
                let split = data[key][0].split(":");
                obj[split[1]] = split[0];
                usable_data[key] = obj;
            }
            else usable_data[key] = data[key][0];
        } 
    }
    usable_data["id"] = id;
    return usable_data;
}


function checkCompatibilityDisplayAll(id_group, data, id_component, display_index)
{
    data = transformData(data, id_component);
    let ch = configurator_data_functions[id_group](data, display_index, 1);
    if(ch === 1) return 1;
    return 0;
}


// ko je izdelek izbran
function checkCompatibility(id_group, data, id_component, display_index)
{
    let new_data = transformData(data["attributes"], id_component);
    new_data["price"] = data["price"];

    let compatibility = configurator_data_functions[id_group](new_data, display_index, 0);

    if(compatibility == 1)
    {
        addConfiguratorData(new_data, id_group, display_index);
        updateMainConfig();

        showHeat(main_config["heat"]);
        showPowerUsage(main_config["power_usage"]);
        showPrice(main_config["price"]);

        if(fill_configurator_check() != 1)
        {
            removeConfiguratorData(id_group, display_index);
            updateMainConfig();

            showHeat(main_config["heat"]);
            showPowerUsage(main_config["power_usage"]);
            showPrice(main_config["price"]);
        }
        request_data_refresh(id_group);
        return 1;
    }
    else
        console.log("error", compatibility);
    return 0;
}

function request_data_refresh(id_group)
{
    refreshSelectedData(id_group);
    for(let i = 0; i < configurator_connect_tree[id_group].length; i++)
    {
        refreshSelectedData(configurator_connect_tree[id_group][i]);
        if(!checkMultipleSameConfigItem(configurator_connect_tree[id_group][i])) continue;
        let main_search = $(".config-item-id").filter(function() {
            return $(this).text().replace(/[0-9]/g, '') === configurator_connect_tree[id_group][i];
        });

        if(check_display_new_group(configurator_connect_tree[id_group][i]) == 1)
        {
            if(main_search.last().parent().find(".select-component").length > 0 && main_search.last().parent().find(".select-component").selected !== undefined)
                addNewConfigItem(configurator_connect_tree[id_group][i], main_search.last().parent());  
        }
        else if(main_search.length > 1 && main_search.last().parent().find(".select-component").length == 0) 
            removeConfigItem(configurator_connect_tree[id_group][i]);

            
    }  
}

function removeConfiguratorItem(id_group, index_of)
{
    removeConfiguratorData(id_group, index_of)
    updateMainConfig();
    fill_configurator_check();
    updateDisplayBuildData();
}

function updateDisplayBuildData()
{
    showHeat(main_config["heat"]);
    showPowerUsage(main_config["power_usage"]);
    showPrice(main_config["price"]);
}

function checkMultipleSameConfigItem(id_group)
{
    if(Array.isArray(main_config["config_data"][id_group]))
        return 1;
    return 0;
}

function getNumOfConfigItems(id_group)
{
    if(Array.isArray(main_config["config_data"][id_group]))
        return main_config["config_data"][id_group].length;
    return -1;
}

function getConfigItemMultipleIds(id_group)
{
    let ids = [];
    if(Array.isArray(main_config["config_data"][id_group]))
        for(let i = 0; i < main_config["config_data"][id_group].length; i++)
            ids.push(main_config["config_data"][id_group][i]["id"]);
    return ids;
}
