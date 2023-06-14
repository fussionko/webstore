let localStorageConfig = window.localStorage;

const config_key = "config";

function save_config(data)
{
    localStorageConfig.setItem(config_key, JSON.stringify(data));
}

function get_config()
{
    return localStorageConfig.getItem(config_key);
}

function getConfigData()
{
    let data = {};
    data["heat"] = main_config["heat"];
    data["power_usage"] = main_config["power_usage"];
    data["price"] = main_config["price"];
    if(main_config["config_data"] === null)
        data["data"] = null;
    else
    { 
        data["data"] = {};
        for(const id_group in main_config["config_data"])
        {
            data["data"][id_group] = [];
            if(Array.isArray(main_config["config_data"][id_group]))
                for(let i = 0; i < main_config["config_data"][id_group].length; i++)
                    data["data"][id_group][i] = main_config["config_data"][id_group][i]["id"];
            else data["data"][id_group] = main_config["config_data"][id_group]["id"];
        }      
    }

    return data;
}

function get_raw_component_data(id_component, id_group)
{
    let temp = 0;
    $.ajax({
        url: "../db_query/Configurator/configurator_get_component_data.php",
        type: "POST",
        data: {"id_component": id_component},
        async: false,
        success: function(response) {
            response = JSON.parse(response);
            if(checkCompatibility(id_group, response, id_component) == 1)
                return temp = 1;
            return temp = 0;
        }
    });
    return temp;
}

function loadConfigData(data)
{
    main_config["heat"] = data["heat"];
    main_config["power_usage"] = data["power_usage"];
    main_config["price"] = data["price"];
    // main_config["config_data"] = null;

    if(data["data"] === null)
    {
        main_config["config_data"] = null;
        return;
    }

    configurator_data = JSON.parse(JSON.stringify(configurator_data_reference));
    main_config["config_data"] = configurator_data;

    for(const id_group in data["data"])
    {
        if(Array.isArray(data["data"][id_group]))
        {
            for(let i = 0; i < data["data"][id_group].length; i++)
            {
                if(data["data"][id_group][i] === null)
                    main_config["config_data"][id_group][i] = configurator_data_reference[id_group][i];
                else if(get_raw_component_data(data["data"][id_group][i], id_group) == 0)
                    main_config["config_data"][id_group][i] = configurator_data_reference[id_group][i];
            }
            continue;
        }
        if(data["data"][id_group] === null)
            main_config["config_data"][id_group] = configurator_data_reference[id_group];
        else if(get_raw_component_data(data["data"][id_group], id_group) == 0)
            main_config["config_data"][id_group] = configurator_data_reference[id_group];
    }

    if(main_config["heat"] === null || main_config["heat"] == 0)
        main_config["heat"] = calculateHeat();
    if(main_config["power_usage"] === null || main_config["power_usage"] == 0)
        main_config["power_usage"] = calculatePowerUsage();
    if(main_config["price"] === null || main_config["price"] == 0)
        main_config["price"] = calculatePrice();
}

function load_from_main_config()
{
    updateDisplayBuildData();
    for(const id_group in main_config["config_data"])
    {
        let parent = $(".config-item-id").filter(function(){
            return $(this).text() === id_group;
        }).parent();

        if(Array.isArray(main_config["config_data"][id_group]))
        {
            if(!checkNull(main_config["config_data"][id_group][0]))
                removeConfigItemMultiple(id_group);
        }
            
        else if(checkNull(main_config["config_data"][id_group]) == 0)
        {
            loadConfigItemEssential(parent, id_group, main_config["config_data"][id_group]["id"], 1);
        }
       
            
    }
}

function save_build()
{
    let send_json = {};
    send_json["data"] = JSON.parse(get_config());
    send_json["name"] = $("#build-name").val();
    send_json["description"] = $("#build-description").val();
    send_json["email"] = $("#build-email").val();
    send_json["public"] =  $("#build-checkbox-public").is(":checked");

    clearErrors();
    $.ajax({
        url: "../db_query/Configurator/configurator_save_build.php",
        type: "POST",
        data: send_json,
        success: function(response){
            if(response != 1)
            {
                errorHandle(JSON.parse(response));
                return;
            }
            $("#container-middle").load("../templates/Configurator/configurator_save_success.php");
        }
    });
}

function errorHandle(data)
{
    for(let i = 0; i < data.length; i++)
    {
        if(data[i] == "build_name")
            $("#build-name").addClass("invalid");
        else if(data[i] == "build_email")
            $("#build-email").addClass("invalid");
        else if(data[i] == "build_description")
            $("#build-description").addClass("invalid");
        else 
            $("#container-main").addClass("invalid");
    }
}

function clearErrors()
{
    $("input").removeClass("invalid");
}

function add_components_to_build()
{
    $.ajax({
        url: "../db_query/Configurator/configurator_save_components_to_build.php",
        type: "POST",
        data: {"id_build" : id_build, "id_components" : id_components},
        success: function(response){
            if(response == -1)
            {
                return;
            }
        }
    });
}

function update_data_build(data, id_build)
{

    $.ajax({
        url: "../db_query/Configurator/configurator_update_build_data.php",
        type: "POST",
        data: {"id_build": id_build, "data" : data},
        success: function(response){
            if(response == -1)
            {
                //napaka z id_buildom
                return;
            }
            else if(response == 0)
            {
                //napaka z data
                return;
            }
            else if(response == 1)
            {
                //ured
            }
        }
    });
}

function update_build_name(name, id_build)
{
    $.ajax({
        url: "../db_query/Configurator/configurator_update_build_name.php",
        type: "POST",
        data: {"id_build" : id_build, "name" : name},
        success: function(response){
            if(response == -1)
            {
                return;
            }
        }
    });
}

function update_description_name(description, id_build)
{
    $.ajax({
        url: "../db_query/Configurator/configurator_update_build_description.php",
        type: "POST",
        data: {"id_build" : id_build, "description" : description},
        success: function(response){
            if(response == -1)
            {
                //napaka z id_buildom
                return;
            }
            else if(response == 0)
            {
                //napaka z description
                return;
            }
            else if(response == 1)
            {
                //ured
            }
        }
    });
}

function checkLogin()
{
    let temp = $.ajax({
        url: "../db_query/Account/account_check_login.php",
        type: "POST",
        async: false,
        success: function(response){
            if(response == 1) return 1;
            return 0;
        }
    });

    if(temp.responseText == 0) return 0;
    else if(temp.responseText == 1) return 1;
    return undefined;
}