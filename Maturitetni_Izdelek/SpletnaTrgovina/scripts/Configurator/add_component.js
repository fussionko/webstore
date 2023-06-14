function addConfigItemInsert(where)
{
    let add = document.createElement("img");
    add.src = "../images/site_image/add_item.jpeg";
    add.classList.add("config-add-item");
    where.appendChild(add);    

    let container_select_component = document.createElement("div");
    container_select_component.classList.add("container-select-component");
    container_select_component.style.display = "none";
    where.appendChild(container_select_component);    

    let price = document.createElement("div");
    price.classList.add("container-component-price");
    // price.style.display = "none";
    where.appendChild(price);   

    add = document.createElement("img");
    add.src = "../images/site_image/close.jpeg";
    add.classList.add("config-item-close");
    where.appendChild(add);   
}

function setupConfigItems()
{
    let items = document.getElementsByClassName("config-item");
    Array.prototype.forEach.call(items, element =>{
        addConfigItemInsert(element);   
    });
}

function addConfigItemShow(element)
{
    element.addEventListener("click", function() {
        let close = this.parentElement.querySelector('.config-item-close');
        
        if(window.getComputedStyle(close).display== "none")
        {
            close.style.display = "block";
            this.style.display = "none";
        }

        let send_id_group;

        let this_parent = $(this).parent();
        let id_text = this_parent.find(".config-item-text").text();
        if(id_text.includes("#"))
            id_text = id_text.split("#")[0].slice(0, -1);
        send_id_group = getGroupId(id_text);
        
        if(send_id_group === null) return 0;

        // ajax request na server dobi vse komponente tega tipa
        createDroplist(send_id_group, this_parent, 0);
    });
}

function addConfigItemClose(element)
{
    element.addEventListener("click", function() {
        let show = this.parentElement.querySelector('.config-add-item');

        let this_parent = $(this).parent();

        clearSelectDropList(this_parent)

        if(window.getComputedStyle(show).display == "none")
        {
            show.style.display = "block";
            this.style.display = "none";
        }

        let id_text = this_parent.find(".config-item-text").text();
        let index_of = 0;
        if(id_text.includes("#"))
        {
            let id_split = id_text.split("#");
            id_text = id_split[0].slice(0, -1);
            index_of = id_split[1]-1;
        }
            
        let id_group = getGroupId(id_text);
    
        removeConfiguratorItem(id_group, index_of);

        if(checkMultipleSameConfigItem(id_group) == 1)
        {
            if(getNumOfConfigItems(id_group) > 1 || checkNull(configurator_data[id_group]) == 0)
                removeConfigItemMultiple(id_group);
            else 
            {
                removeConfigItem(id_group);
                removePrevImg(this_parent);
                removePrevComponentPrice(this_parent);
            }    
        }
        else 
        {
            removePrevImg(this_parent);
            removePrevComponentPrice(this_parent);
        }
       
        updateAllShowData()
        updateDisplayBuildData();
        save_config(getConfigData());
        request_data_refresh(id_group);
    });
}

function setupShow_RemoveButtons()
{
    let addListenerShow = document.getElementsByClassName("config-add-item");
    for(let i = 0; i < addListenerShow.length; i++)
        addConfigItemShow(addListenerShow[i]);

    let addListenerClose = document.getElementsByClassName("config-item-close");
    for(let i = 0; i < addListenerClose.length; i++)
        addConfigItemClose(addListenerClose[i]);
}

function createDroplist(send_id_group, parent, id_selected)
{
    send_id_group = send_id_group.replace(/[0-9]/g, '');
    clearSelectDropList(parent)
    $.ajax({
        url: "../db_query/Configurator/configurator_get_components.php",
        type: "POST",
        data: {"id_group": send_id_group},
        async: false,
        success: function(response)
        {
            displayDroplist(parent, JSON.parse(response), send_id_group) 
            if(id_selected !== undefined)
                $(parent).find(".select-component").val(id_selected).change();
        }
    });
}

function clearSelectDropList(parent)
{

    let parent_div = parent.find(".container-select-component");
    parent_div.css("display", "none");
    parent_div.children().remove();
}

function displayDroplist(parent, data, id_group) 
{
    let parent_div = parent.find(".container-select-component");
    parent_div.css("display", "block");

    let select = document.createElement("select");
    select.classList.add("select-component");
    select.style.position = "relative";

    let option = document.createElement("option");
    option.value = 'null';
    option.innerText = ' ';
    select.append(option);

    console.log(parent, data, id_group);

    let display_index = -1;
    if(check_group_is_array(id_group))
    {
        display_index = parent.find(".config-item-id").text().replace(/\D/g,'');
        if(display_index == '') display_index = 0;
        else display_index = parseInt(display_index)-1;
    }

    id_group = id_group.replace(/[0-9]/g, '');

    for(const component in data)
    {
        if(checkCompatibilityDisplayAll(id_group, data[component]["attributes"], component, display_index) == 0) continue;

        let option = document.createElement("option");
        option.value = component;
        option.innerText = data[component]["itemName"];
        select.append(option);
    }  
    select.addEventListener("change", function(){
        let index;
        if(this.value == "null")
        {
            if(checkMultipleSameConfigItem(id_group))
            {
                index = getDisplayIndex(parent);
                removeConfiguratorItem(id_group, index);
            }
            else removeConfiguratorItem(id_group);
            updateAllShowData();
            updateDisplayBuildData();
            return;
        }

        if(checkMultipleSameConfigItem(id_group))
        {
            index = getDisplayIndex(parent);
            changeConfiguratorData(id_group, index);
        }

        if(index === undefined) getComponentData(this.value, id_group, parent);
        else getComponentData(this.value, id_group, parent, index);
        save_config(getConfigData());
    });
    parent_div.append(select);
}

function getDisplayIndex(parent)
{
    let index = parent.find(".config-item-text").text();
    if(index.includes('#'))
        index = index.split('#')[1]-1;
    else index = 0;
    return index;
}

function refreshSelectedData(id_group)
{
    let display_config = $(".config-item-id").filter(function() {
        return $(this).text().replace(/[0-9]/g, '') == id_group;
    }).parent().filter(function() {
        if($(this).find(".select-component").length > 0) return 1;
        return 0;
    });

    if(display_config.length == 0) return;
    for(let i = 0; i < display_config.length; i++)
    {
        let id_selected = $(display_config[i]).find(".select-component").val();  
        createDroplist(id_group, $(display_config[i]), id_selected);
    }
}

function updateAllShowData()
{
    updateDisplayBuildData();
}

function addNewConfigItem(id_group, main_sibling, main_num_config_item, load)
{
    if(load === undefined)
        if(check_display_new_group(id_group) != 1) return;

    let text = getGroupText(id_group)
    let num_of_config_items;
    if(main_num_config_item === undefined) num_of_config_items = getNumOfConfigItems(id_group) + 1;
    else num_of_config_items = main_num_config_item;

    let config_item = document.createElement("div");
    config_item.classList.add("config-item");

    let config_item_text = document.createElement("span");
    config_item_text.classList.add("config-item-text");
    config_item_text.innerText = text + ' #' + num_of_config_items;
    
    let config_item_id = document.createElement("span");
    config_item_id.classList.add("config-item-id");
    config_item_id.innerText = id_group + num_of_config_items;

    config_item.appendChild(config_item_text);
    config_item.appendChild(config_item_id);

    $(config_item).insertAfter($(main_sibling));

    addConfigItemInsert(config_item);
    addConfigItemShow(config_item.getElementsByClassName("config-add-item")[0]);
    addConfigItemClose(config_item.getElementsByClassName("config-item-close")[0]);

    return $(config_item);
}

function removeConfigItemMultiple(id_group)
{
    let main_parent = $(".config-item-text").filter(function() {
        return $(this).text() === getGroupText(id_group);
    }).parent();
    
    removeMultipleConfigItems(id_group);
    loadMultipleConfigItems(id_group, main_parent);   
}

function get_same_group_display(id_group)
{
    return remove_items = $(".config-item-id").filter(function() {
        return $(this).text().replace(/[0-9]/g, '') === id_group;
    }).length;
}

//nalozi vse
function loadMultipleConfigItems(id_group, parent)
{
    let load_ids = getConfigItemMultipleIds(id_group);

    if(load_ids[0] === null) return;

    //Prvi element
    loadConfigItemEssential(parent, id_group, load_ids[0])
    load_ids.shift();

    let last_insert = parent;
    for(let i = 0; i < load_ids.length; i++)
    {
        last_insert = addNewConfigItem(id_group, last_insert, i+2, "load");
        loadConfigItemEssential(last_insert, id_group, load_ids[i]);
    }

    if(load_ids.length == 0) last_insert = parent;
    addNewConfigItem(id_group, last_insert);
}

function loadConfigItemEssential(parent, id_group, id_selected)
{
    
    createDroplist(id_group, parent, id_selected);
    removePrevImg(parent);
    showImage(getConfigItemImage(id_selected), parent);
    
    let price = 0;
   
    if(Array.isArray(main_config["config_data"][id_group]))
    {
        for(let i = 0; i < main_config["config_data"][id_group].length; i++)
            if(main_config["config_data"][id_group][i]["id"] == id_selected)
            {
                price = main_config["config_data"][id_group][i]["price"];
                break;
            }         
    }
    else if(main_config["config_data"][id_group]["id"] == id_selected) 
        price = main_config["config_data"][id_group]["price"];

    removePrevComponentPrice(parent);
    showComponentPrice(parent, price);

    parent.find('.config-item-close').css("display", "block")
    parent.find('.config-add-item').css("display", "none");

    
}

//odstrani vse razn taglavnga
function removeMultipleConfigItems(id_group)
{
    let remove_items = $(".config-item-id").filter(function() {
        return $(this).text().replace(/[0-9]/g, '') === id_group;
    }).not(":first").parent();
    remove_items.remove();
}

function removeConfigItem(id_group)
{
    let remove_this = $(".config-item-id").filter(function() {
        return $(this).text().replace(/[0-9]/g, '') === id_group;
    }).last().parent();
    remove_this.remove();
}

function getConfigItemImage(id_component)
{
    let temp = -1;
    $.ajax({
        url: "../db_query/Configurator/configurator_get_component_image.php",
        type: "POST",
        data: {"id_component": id_component},
        async: false,
        success: function(response) {
            if(response == -1)
                return temp = -1;
            return temp = response;
        }
    });
    return temp
}

function getComponentData(id_component, id_group, parent, display_index)
{
    $.ajax({
        url: "../db_query/Configurator/configurator_get_component_data.php",
        type: "POST",
        data: {"id_component": id_component},
        async: false,
        success: function(response) {
            response = JSON.parse(response);
           
            if(checkCompatibility(id_group, response, id_component, display_index) == 1)
            {
                removePrevImg(parent);
                removePrevComponentPrice(parent);
                showImage(response["image_location"], parent);
                showComponentPrice(parent, response["price"]);

                let check = $(".config-item-id").filter(function() {
                    return $(this).text() === (id_group+parseInt(getNumOfConfigItems(id_group)+1));
                });

                if(checkMultipleSameConfigItem(id_group) && check.length == 0)
                {
                    addNewConfigItem(id_group, parent);
                }
                    
                
                updateDisplayBuildData();

                save_config(getConfigData());
            }
        }
    });
}

function clearMiddle()
{
    $("#container-middle").empty();
}

function setup_page()
{
    setupConfigItems();
    setupShow_RemoveButtons();
    loadConfigData(JSON.parse(get_config()));
    fill_configurator_check();
    load_from_main_config();
    document.getElementById("button-save").addEventListener("click", function(){
        clearMiddle();
        $("#container-middle").load("../templates/Configurator/configurator_save_build_template.php", () => {
            if(checkLogin() == 1)
                document.getElementById("container-build-email").style.display = "none";

            $.getScript("../scripts/Account/account_process.js", function(){
                setupInputs();
            });
            document.getElementById("button-prev").addEventListener("click", function(){
                clearMiddle();
                $("#container-middle").load("../templates/Configurator/configurator_item_template.php", () => {
                    setup_page();
                });
            })

            document.getElementById("button-build-save").addEventListener("click", function(){
                save_build();
            });
        });
    })  
}

let old_value = document.getElementById("watt-offset").value;
document.getElementById("watt-offset").addEventListener("input", function(){
    let max = 40, min = 0;

    if(this.value > max) this.value = max;
    else if(this.value < min) this.value = min;

    psu_offset_watt = this.value / 100;

    if(check_psu_power(main_config["config_data"]["psu"]["watt"], main_config["config_data"]["psu"]["ucinkovitost"], 213) == 0)
    {
        this.value = old_value;
        psu_offset_watt = old_value / 100;
        return;
    }

    if(safety_check() == 1) 
    {
        old_value = this.value;
    }   
    else 
    {
        this.value = old_value;
        psu_offset_watt = old_value / 100;
    }

    request_data_refresh("watt");
    
});

$("#watt-offset[type='number']").keypress(function (evt) {
    evt.preventDefault();
});