let configurator_check;


function fill_configurator_check(data)
{
    if(data === undefined)
        configurator_check = JSON.parse(JSON.stringify(main_config["config_data"]));
    else configurator_check = JSON.parse(JSON.stringify(data));
    let setup = setup_configurator_check();
    if(setup != 1)
    {
        alert("critical error");
        console.log("MAIN ERROR");
        console.log("Configurator_check", JSON.parse(JSON.stringify(configurator_check)));
        console.log("main_config", JSON.parse(JSON.stringify(main_config)));
        console.log(setup);
        return -1;
    } 
    return 1;
}

function safety_check()
{
    configurator_check = JSON.parse(JSON.stringify(main_config["config_data"]));
    if(setup_configurator_check() != 1) return -1;
    return 1;
}

function reduce_From_configurator_check(id_group, key, search, index)
{
    if(configurator_check === undefined) return -1;
    
    search = translator_moab(search);

    if(index === undefined)
    {
        if(configurator_check[id_group][key] === null) return 1;
        if(configurator_check[id_group][key][search] === undefined) return 1;
        if(parseInt(configurator_check[id_group][key][search]) - 1 < 0) return 0;

        configurator_check[id_group][key][search] = String(parseInt(configurator_check[id_group][key][search]) - 1);
    }     
    else
    {
        if(configurator_check[id_group][index][key] === null) return 1;
        if(parseInt(configurator_check[id_group][index][key][search]) - 1 < 0) return 0;
        configurator_check[id_group][index][key][search] = String(parseInt(configurator_check[id_group][index][key][search]) - 1);
    }       
    return 1;
}

function check_add(id_group, key, search)
{
    if(configurator_check === undefined) return -1;
    if(configurator_check[id_group][key] === null) return 1;
    if(configurator_check[id_group][key][search] == 0) return 0;
    return 1;
}

function check_amount_add(id_group, key, search, amount)
{    
    if(configurator_check === undefined) return -1;
    if(configurator_check[id_group][key] === null) return 1;
    if(amount === undefined)
        amount = -1;
    else if(parseInt(configurator_check[id_group][key][search]) - parseInt(amount) + 1 == 0) return 0;
    return 1;
}

function translator_moab(input)
{
    switch(input)
    {
        case "3pin": return "sys_fan_header_3pin";
        case "4pin": return "sys_fan_header_4pin";
        case "sys_fan_header_3pin": return "3pin";
        case "sys_fan_header_4pin": return "4pin";
        default: return input;
    }
}

function setup_configurator_check()
{ 
    let usage = 0;
    setup_psu_starting_watt();
    for(let i = 0; i < configurator_check["gpu"].length; i++)
    {
        if(configurator_check["gpu"][i]["konektor"] === null && configurator_check["gpu"].length === 1) break;
        if(reduce_From_configurator_check("moab", "pcle", configurator_check["gpu"][i]["konektor"]) != 1) return -1;
        if(reduce_From_configurator_check("psu", "konektor", configurator_check["gpu"][i]["psu_konektor"]) != 1) return -2;
        usage += parseFloat(configurator_check["gpu"][i]["tdp"]);
        if(reduce_power_usage(configurator_check["gpu"][i]["tdp"]) != 1) return -3;
    }
    for(let i = 0; i < configurator_check["storage"].length; i++)
    {
        if(configurator_check["storage"][i]["konektor"] === null && configurator_check["storage"].length === 1) break;
        if(reduce_From_configurator_check("moab", "disk_konektor", configurator_check["storage"][i]["konektor"]) != 1) return -3;
        if(reduce_From_configurator_check("case", "diski", configurator_check["storage"][i]["format"]) != 1) return -4;
        usage += parseFloat(configurator_check["storage"][i]["tdp"])
        if(reduce_power_usage(configurator_check["storage"][i]["tdp"]) != 1) return -5;
    }

    if(configurator_check["case"]["usb"] !== null)
    {
        for(let i = 0; i < configurator_check["case"]["usb"].length; i++)
            if(reduce_From_configurator_check("moab", "fp_usb", configurator_check["case"]["usb"][i]) != 1) return -6;
    }
    if(configurator_check["case"]["ah"] !== null)
    {
        for(let i = 0; i < configurator_check["case"]["ah"].length; i++)
            if(reduce_From_configurator_check("moab", "fp_ah", configurator_check["case"]["ah"][i]) != 1) return -7;
    }

    if(configurator_check["cpu_cool"]["konektor"] !== null)
    {
        for(const type in configurator_check["cpu_cool"]["konektor"])
            if(reduce_From_configurator_check("moab", "pin_konektorji", type) != 1) return -8;

        for(const type in configurator_check["cpu_cool"]["fans"])
        {
            for(let i = 0; i < parseInt(configurator_check["cpu_cool"]["fans"][type]); i++)
                if(reduce_From_configurator_check("case", "sys_fan", type) != 1) return -9;
        }
        usage += parseFloat(configurator_check["cpu_cool"]["tdp"])
        if(reduce_power_usage(configurator_check["cpu_cool"]["tdp"]) != 1) return -10;
    }

    for(let i = 0; i < configurator_check["sys_cool"].length; i++)
    {
        if(configurator_check["sys_cool"][i]["konektor"] === null && configurator_check["sys_cool"].length === 1) break;
        usage += parseFloat(configurator_check["sys_cool"][i]["tdp"])
        if(reduce_power_usage(configurator_check["sys_cool"][i]["tdp"]) != 1) return -11;
        if(reduce_From_configurator_check("moab", "pin_konektorji", configurator_check["sys_cool"][i]["konektor"]) != 1) return -12;
        if(reduce_From_configurator_check("case", "sys_fan", configurator_check["sys_cool"][i]["velikost_fan"]) != 1) return -13;
    }

    usage += parseFloat(configurator_check["cpu"]["tdp"])
    usage += parseFloat(configurator_check["ram"]["tdp"])
    if(reduce_power_usage(configurator_check["cpu"]["tdp"]) != 1) return -14;
    if(reduce_power_usage(configurator_check["ram"]["tdp"]) != 1) return -16;

    // console.log(configurator_check["psu"]["watt"], main_config["config_data"]["psu"]["watt"]);
    // console.log("USAGE: ", usage);
    return 1;
}

function check_zero(id_group, key, search, index)
{
    if(index === undefined)
    {
        if(parseInt(configurator_check[id_group][key][search]) <= 0) return 1;
    }
    else if(parseInt(configurator_check[id_group][index][key][search]) <= 0) return 1;

    return 0;
}

function check_part_count(type, id_group, check_search)
{
    if(configurator_check === undefined) return 1;
    if(configurator_check[id_group][0][check_search] === null) return 1;

    let temp = JSON.parse(JSON.stringify(type));
    if(check_search == "psu_konektor")
    {
        for(let i = 0; i < configurator_check[id_group].length; i++)
        {
            if(configurator_check[id_group][i][check_search] === undefined) continue;
            if(!temp.hasOwnProperty(configurator_check[id_group][i][check_search])) return 0;
            if(parseInt(temp[configurator_check[id_group][i][check_search]]) - 1 < 0) return 0;
            temp[configurator_check[id_group][i][check_search]] = parseInt(temp[configurator_check[id_group][i][check_search]]) - 1;
        }
    }
    else 
    {
        for(let i = 0; i < configurator_check[id_group].length; i++)
        {
            if(!temp.hasOwnProperty(configurator_check[id_group][i][check_search])) return 0;
            if(parseInt(temp[configurator_check[id_group][i][check_search]]) - 1 < 0) return 0;
            temp[configurator_check[id_group][i][check_search]] = parseInt(temp[configurator_check[id_group][i][check_search]]) - 1;
        }
    }


    return 1;
}

function custom_moab_fan_eval(type)
{
    if(configurator_check === undefined) return 1;
    if(configurator_check["sys_cool"][0]["konektor"] === null) return 1;
   
    let temp = JSON.parse(JSON.stringify(type));

    for(let i = 0; i < configurator_check["sys_cool"].length; i++)
    {
        let translated_search = translator_moab(configurator_check["sys_cool"][i]["konektor"]);
        if(!temp.hasOwnProperty(translated_search)) return 0;
        if(parseInt(temp[translated_search]) - 1 < 0) return 0;
        temp[translated_search] = parseInt(temp[translated_search]) - 1;
    }

    if(configurator_check["cpu_cool"]["konektor"] === null) return 1;

    for(const type in configurator_check["cpu_cool"]["konektor"])
    {
        let translated_search = translator_moab(type);
        if(!temp.hasOwnProperty(translated_search)) return 0;
        if(parseInt(temp[translated_search]) - parseInt(configurator_check["cpu_cool"]["konektor"][type]) < 0) return 0;
        temp[translated_search] = parseInt(temp[translated_search]) - parseInt(configurator_check["cpu_cool"]["konektor"][type]);
    }

    return 1;
}


function custom_case_fan_eval(type)
{
    if(configurator_check === undefined) return 1;
    if(configurator_check["sys_cool"][0]["velikost_fan"] === null) return 1;
   
    let temp = JSON.parse(JSON.stringify(type));

    for(let i = 0; i < configurator_check["sys_cool"].length; i++)
    {

        if(!temp.hasOwnProperty(configurator_check["sys_cool"][i]["velikost_fan"])) return 0;
        if(parseInt(temp[configurator_check["sys_cool"][i]["velikost_fan"]]) - 1 < 0) return 0;
        temp[parseInt(configurator_check["sys_cool"][i]["velikost_fan"])] = parseInt(temp[configurator_check["sys_cool"][i]["velikost_fan"]]) - 1;
    }

    if(configurator_check["cpu_cool"]["fans"] === null) return 1;

    for(const type in configurator_check["cpu_cool"]["fans"])
    {
        if(!temp.hasOwnProperty(type)) return 0;
        if(parseInt(temp[type]) - parseInt(configurator_check["cpu_cool"]["fans"][type]) < 0) return 0;
        temp[type] = parseInt(temp[type]) - parseInt(configurator_check["cpu_cool"]["fans"][type]);
    }

    return 1;
}

function custom_moab_eval(type, type2)
{
    let check_data = {};
    for(const pin in type)
        if(configurator_sys_cool.includes(translator_moab(pin)) == 1) 
            check_data[translator_moab(pin)] = type[pin];

    for(const pin in type2)
        if(configurator_sys_cool.includes(translator_moab(pin)) == 1) 
            check_data[translator_moab(pin)] = type2[pin];
    
    if(!check_part_count(check_data, "sys_cool", "konektor")) return 0;
}

function custom_cpu_cool(type, comp_id_group, check_id_group, comp_attr, check_atr)
{
    if(configurator_check === undefined) return -1;
    if(configurator_check[check_id_group][check_atr] === null) return 1;

    for(const fan in type)
    {     
        let prev_amount = 0;
        if(configurator_check[comp_id_group][comp_attr] !== null)
            if(configurator_check[comp_id_group][comp_attr].hasOwnProperty(fan))
                prev_amount = parseInt(configurator_check[comp_id_group][comp_attr][fan]);
        if(parseInt(configurator_check[check_id_group][check_atr][fan]) - parseInt(type[fan]) + prev_amount < 0) return 0;
    }
    return 1;
}

function check_part_count_special(type, id_group, check_search, compare)
{
    if(configurator_check === undefined) return 1;
    if(configurator_check[id_group][0][check_search] === null) return 1;

    let temp = JSON.parse(JSON.stringify(type));

    for(let i = 0; i < configurator_check[id_group].length; i++)
    {
        if(!compare.includes(configurator_check[id_group][i][check_search])) continue;
        if(!temp.hasOwnProperty(configurator_check[id_group][i][check_search]))
            return 0;
    
        if(parseInt(temp[configurator_check[id_group][i][check_search]]) - 1 < 0)
            return 0;
    
        temp[configurator_check[id_group][i][check_search]] = parseInt(temp[configurator_check[id_group][i][check_search]]) - 1;
    }
    return 1;
}

function check_num_allowed(type, id_group, key)
{
    if(type === undefined) return 1;
    if(configurator_check === undefined) return 1; 
    if(configurator_check[id_group][key] === null) return 1;
    if(parseInt(configurator_check[id_group][key][type]) > 0) return 1;
    return 0;
}

function compare_safe_power_usage(input_tdp, id_group, index)
{
    if(configurator_check == undefined) return 1;
    if(configurator_check["psu"]["watt"] == null) return 1;

    let temp_watt = 0;

    if(index == undefined) temp_watt = configurator_check[id_group]["tdp"] === null ? 0 : configurator_check[id_group]["tdp"];
    else temp_watt = configurator_check[id_group][index] === undefined || configurator_check[id_group][index]["tdp"] === null ? 0 : configurator_check[id_group][index]["tdp"];

    temp_watt = parseFloat(temp_watt);
    if(parseFloat(configurator_check["psu"]["watt"]) + temp_watt - parseFloat(input_tdp) >= 0) return 1;
    return 0;
}

function reduce_power_usage(input_tdp)
{
    if(input_tdp === undefined || input_tdp === null) return 1;
    if(configurator_check["psu"]["watt"] == null) return 1;
    if(parseFloat(configurator_check["psu"]["watt"]) - parseFloat(input_tdp) < 0) return 0;
    configurator_check["psu"]["watt"] = String(parseFloat(configurator_check["psu"]["watt"]) - parseFloat(input_tdp));
    return 1;
}

let psu_offset_watt = 0.1;

function watt_formula(input_watt, efficiency)
{
    efficiency = parseFloat(efficiency);
    input_watt = parseFloat(input_watt) * ((1 - efficiency) / 2 + efficiency);
    return input_watt - (input_watt * psu_offset_watt);
}

function setup_psu_starting_watt()
{
    if(configurator_check["psu"]["watt"] == null) return 1;
    configurator_check["psu"]["watt"] = String(watt_formula(main_config["config_data"]["psu"]["watt"], main_config["config_data"]["psu"]["ucinkovitost"]));
    return 1;
}

function check_psu_power(compare_watt, compare_efficency, c)
{
    if(main_config["power_usage"] == null || compare_watt === null) return 1;
    if(watt_formula(compare_watt, compare_efficency) >= main_config["power_usage"]) return 1;
    return 0;
}

function calculatePowerUsage()
{
    let combined = 0;
    for(const i in main_config["config_data"])
    {
        if(Array.isArray(main_config["config_data"][i]))
        {
            for(const j in main_config["config_data"][i])
            {
                if(!main_config["config_data"][i][j].hasOwnProperty("tdp")) continue;
                if(main_config["config_data"][i][j]["tdp"] != null) 
                    combined += parseInt(main_config["config_data"][i][j]["tdp"]);
            }
            continue;
        }
        if(!main_config["config_data"][i].hasOwnProperty("tdp")) continue;
        if(main_config["config_data"][i]["tdp"] == null) continue;
            combined += parseInt(main_config["config_data"][i]["tdp"]);
    }
    return combined+(combined/100)*psu_offset_watt;
}

function calculatePrice()
{
    let price = 0;
    for(const i in main_config["config_data"])
    {
        if(Array.isArray(main_config["config_data"][i]))
        {
            for(const j in main_config["config_data"][i])
                if(main_config["config_data"][i][j]["price"] != null)
                    price += parseFloat(main_config["config_data"][i][j]["price"]);
            continue;
        }
        if(main_config["config_data"][i]["price"] != null)
            price += parseFloat(main_config["config_data"][i]["price"]);        
    }
    return price;
}

function calculateHeat()
{
    let tdp = 0;
    for(const i in main_config["config_data"])
    {
        if(Array.isArray(main_config["config_data"][i]))
        {
            for(const j in main_config["config_data"][i])
            {
                if(!main_config["config_data"][i][j].hasOwnProperty("tdp")) continue;
                if(main_config["config_data"][i][j]["tdp"] != null) 
                    tdp += parseInt(main_config["config_data"][i][j]["tdp"]);
            }
            continue;
        }
        if(!main_config["config_data"][i].hasOwnProperty("tdp")) continue;
        if(main_config["config_data"][i]["tdp"] == null) continue;
            tdp += parseInt(main_config["config_data"][i]["tdp"]);
    }
    return tdp;
}

const max_gpu = 4, max_storage = 10, max_sys_cool = 10;

function check_display_new_group(id_group)
{
    let check1 = 0, check2 = 0;
    switch(id_group)
    {
        case "gpu":
            if(configurator_check["gpu"].length >= max_gpu) return 0;
            if(configurator_check["moab"]["pcle"] === null) check1 = 1;
            else 
                for(const type in configurator_check["moab"]["pcle"])
                    if(check_zero("moab", "pcle", type) == 0)
                    {
                        check1 = 1;
                        break;
                    } 
            
     
    	    if(configurator_check["psu"]["konektor"] === null) check2 = 1;
            else 
                for(const type in configurator_check["psu"]["konektor"])
                    if(check_zero("psu", "konektor", type) == 0) 
                    {
                        check2 = 1;
                        break;
                    }
    
            if(check1 == 1 && check2 == 1) return 1;
            return 0;

        case "sys_cool":
            if(configurator_check["sys_cool"].length >= max_sys_cool) return 0;
            if(configurator_check["case"]["sys_fan"] === null) check1 = 1;
            else
                for(const type in configurator_check["case"]["sys_fan"])
                    if(check_zero("case", "sys_fan", type) == 0) 
                    {
                        check1 = 1;
                        break;
                    }
            if(configurator_check["moab"]["pin_konektorji"] === null) check2 = 1;
            else 
                for(const type in configurator_sys_cool)
                    if(check_zero("moab", "pin_konektorji", translator_moab(configurator_sys_cool[type])) == 0) 
                    {
                        check2 = 1;
                        break;
                    }
            
            if(check1 == 1 && check2 == 1) return 1;
            return 0;

        case "storage":
            if(configurator_check["storage"].length >= max_storage) return 0;
            if(configurator_check["case"]["diski"] === null) check1 = 1;
            else 
                for(const type in configurator_check["case"]["diski"])
                    if(check_zero("case", "diski", type) == 0) 
                    {
                        check1 = 1;
                        break;
                    }
            if(configurator_check["moab"]["disk_konektor"] === null) check2 = 1;
                for(const type in configurator_check["moab"]["disk_konektor"])
                    if(check_zero("moab", "disk_konektor", type) == 0) 
                    {
                        check2 = 1;
                        break;
                    }
               
            if(check1 == 1 && check2 == 1) return 1;
            return 0;

        default: return 0;
    }
}

const configurator_sys_cool = ["3pin", "4pin"];
const configurator_storage_case = ["2.5", "3.5"];

const configurator_connect_tree = {
    "cpu" : ["moab", "ram", "cpu_cool", "psu"],
    "moab" : ["cpu", "ram", "gpu", "storage", "cpu_cool", "sys_cool", "case", "psu"],
    "ram" : ["cpu", "moab", "psu"],    
    "gpu" : ["moab", "case", "psu"],
    "storage" : ["moab", "case", "psu"],
    "cpu_cool" : ["cpu", "moab", "sys_cool", "case", "psu"], 
    "sys_cool" : ["moab", "cpu_cool", "case", "psu"],
    "psu" : ["case", "cpu", "moab", "ram", "gpu", "storage", "sys_cool"],
    "case" : ["moab", "gpu", "storage", "sys_cool", "cpu_cool", "psu"],
    "watt" : ["cpu", "gpu", "ram", "storage", "sys_cool", "cpu_cool", "psu"]
}   

