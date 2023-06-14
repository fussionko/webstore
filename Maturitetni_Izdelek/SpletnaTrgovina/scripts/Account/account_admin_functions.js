function createInsertForm(parent, data)
{
    removePrevContent(parent);
    let form = document.createElement("div");
    form.classList.add("form");
    form.id = "form-data";

    if(data.length <= 0) return;
    for(let i = 0; i < data.length; i++)
    {
        let temp = data[i]["COLUMN_NAME"];
        if(temp.includes("time") && temp != "time_to_deliver")
            continue;

        let form_part = document.createElement("div");
        form_part.classList.add("form-part");

        let label = document.createElement("label");
        label.for = temp;
        form_part.appendChild(label);

        let input = document.createElement("input");
        if(data[i]["DATA_TYPE"] == "varchar")
            input.type = "text";
        else if(data[i]["DATA_TYPE"] == "int")
            input.type = "number";
        else if(data[i]["DATA_TYPE"] == "tinyint")
        {
            input.type = "number";
            input.min = "0";
            input.max = "1";
        }
            

        input.name = temp;
        input.id = temp;
        input.placeholder = temp;

        if(temp == "active")
            input.value = 1;
        form_part.appendChild(input);

        let check_null = document.createElement("div")
        check_null.classList.add("container-check_null");
        if(data[i]["IS_NULLABLE"] == "YES")
        {   
            check_null.innerText = "NULLABLE";
            form_part.appendChild(check_null);
        }
        
        form.appendChild(form_part);
    }

    let container_main = document.createElement("div");
    container_main.id = "container-main";
    form.appendChild(container_main);

    let button = document.createElement("div");
    button.classList.add("button");
    button.id = "button-send";
    button.innerHTML = "<span>Vstavi</span>";

    let container_button = document.createElement("div");
    container_button.classList.add("container-button");

    container_button.appendChild(button);
    form.appendChild(container_button);

    parent.appendChild(form);
}

function removePrevContent(parent)
{
    while(parent.firstChild)
        parent.removeChild(parent.firstChild);
}

function clearInputs(parent)
{
    $(parent).find("input").val("");
}

// V tabeli prikaze ze vnesene podatke iz baze  VERJETNO JE TEMP da se spremeni v tabelo z DIV in display grid
function displayData(parent, data, attr)
{
    let table_div = document.createElement("div");
    table_div.classList.add("data_table");
    let table = document.createElement("table");
    table.id = "table-response";

    //TEMP
    table.style.border = "1px solid black";
    table.style.borderCollapse = "collapse";
    let thead = document.createElement("thead");
    let tr = document.createElement("tr");

    if(attr == undefined) return;
    if(attr.length <= 0) return;
    for(let i = 0; i < attr.length; i++)
    {
        let th = document.createElement("th");
        th.innerText = attr[i]["COLUMN_NAME"];
        th.style.border = "1px solid black";
        tr.appendChild(th);
    }
    thead.appendChild(tr);
    table.appendChild(thead);

    for(let i = 0; i < data.length; i++)
    {
        let tr = document.createElement("tr");
        for(let j = 0; j < Object.keys(data[i]).length; j++)
        {
            let td = document.createElement("td");
            td.style.border = "1px solid black";
            td.innerText = Object.values(data[i])[j];
            tr.appendChild(td);
        }
        //TEMP
        tr.style.border = "1px solid black";
        table.appendChild(tr);
    }
    table_div.appendChild(table);
    parent.appendChild(table_div);
}

function prepareDataInsert(inputData)
{
    let data = {};
    Array.prototype.forEach.call(inputData, element => {


        let input = element.getElementsByTagName("input")[0];
        if(input.value.length == 0)
        {
            if(element.getElementsByClassName("container-check_null")[0] == undefined)
                data[input.id] = input.value;
            else
                data[input.id] = "NULL";
        }
        else 
            data[input.id] = input.value;
    });
    return data;
    
}

function updateTable(display, currentTable, tableAttributes)
{
    $(document).find(".data_table").remove();
    $.ajax({
        url: "../db_query/Admin/admin_load_data.php",
        type: "POST",
        data: JSON.stringify({"table":currentTable.innerText}),
        success: function(response){
            displayData(display, JSON.parse(response), tableAttributes);
        }
    });
}

function generateDataFunction()
{
    let button_send = document.getElementById("button-send");
    let parent = document.getElementById("content-admin");
    let value = $(button_send).parent().parent().find("#num_of_generations").val();

    value = {"num_of_generations" : value};

    $(parent).find("#container-generator-result").remove();

    $.ajax({
        url: "../data_generator/random_data_generator.php",
        type: "POST",
        data: value,
        success: function(response){
            $(parent).find("#num_of_generations").val('');
            let result = document.createElement("div");
            result.id = "container-generator-result";
            result.style.padding = "10px";
            if(response == 1)
            {
                result.innerText = "Uspešno";
                result.style.border = "2px solid limegreen";
            }
            else if(response == 0)
            {
                result.innerText = "Podatki niso prišli do generatorja";
                result.style.border = "2px solid yellow";
            }
            else
            {
                response = JSON.parse(response);
                result.innerText = "Neuspešno => " + response["error_msg"] + " na " + response["i_item"] + " izdelku";
                result.style.border = "2px solid red";
            }
            parent.appendChild(result);

            button_send.addEventListener("click", generateDataFunction);
        },
        error: function(response, error){
            button_send.addEventListener("click", generateDataFunction);
        }
    });
    button_send.removeEventListener("click", generateDataFunction);
}

function addTableEvents()
{
    // TEMP
    document.getElementById("temp-button").addEventListener("click", function(){
        transformProductToComponent("RAM", "neki");
    });

    // Dodajanje produkta
    document.getElementById("insert-product-button").addEventListener("click", function(){
        let parent = document.getElementById("content-admin");
        removePrevContent(parent);
        $.ajax({
            url: "../templates/Account/Admin_forms/admin_form_generate_data.php",
            type: "GET",
            success: function(response){
                parent.innerHTML = response;
                document.getElementById("button-send").addEventListener("click", generateDataFunction);
            }
        });
    });

    let parent = document.getElementById("overview-admin");
    parent = parent.getElementsByClassName("overview-item");
    for(let i = 0; i < parent.length; i++)
    {
        parent[i].addEventListener("click", function(){
            let currentTable = this;

            // Odstrani active vsem ostalim
            $(currentTable).siblings().removeClass("active-item");
            currentTable.classList.add("active-item");

            let content_admin = document.getElementById("content-admin");
            let tableAttributes;


            $.ajax({
                url: "../db_query/Admin/admin_load_attributes.php",
                type: "POST",
                data: JSON.stringify({"table" : currentTable.innerText}),
                success: function(response){
                    createInsertForm(content_admin, JSON.parse(response));
                    tableAttributes = JSON.parse(response);
                    updateTable(content_admin, currentTable, tableAttributes);
                    document.getElementById("button-send").addEventListener("click", function(){
                        $.ajax({
                            url: "../db_query/Admin/admin_insert_data.php",
                            type: "POST",
                            data: {"table_name":currentTable.innerText, "table_atr":prepareDataInsert($(document).find(".form").children(".form-part"))},
                            success: function(response){
                                updateTable(content_admin, currentTable, tableAttributes);
                                let this_form = document.getElementById("form-data");
                                if(JSON.parse(response)["main"] != 1)
                                {
                                    document.getElementById("container-main").innerHTML = "<span>" + response + "</span>";
                                    this_form.style.border = "2px solid red";
                                }
                                else
                                {
                                    this_form.style.border = "2px solid limegreen";
                                    setTimeout(() => {
                                        this_form.style.border = "none";
                                        clearInputs(this_form);
                                        $(this_form).find("#active").val("1");
                                    }, 700);
                                }

                            }
                        });
                           
                    });
                }
            });
        });
    }
}

// Products v Komponente
function transformProductToComponent(category, products)
{
    $.ajax({
        url: "../db_query/Admin/admin_get_products.php",
        type: "GET",
        data: {"name_parent_category": category},
        success: function(response){
        }
    });
}

function setupInputOpen()
{
    let inputs = document.getElementById("container-insert-product").getElementsByTagName("input");
    for(let x = 0; x < inputs.length; x++)
    {
        inputs[x].addEventListener("click", function(){
            let currentTable;

            let content_admin = document.getElementById("content-admin");
            let tableAttributes;

            if(this.id == 'storage')
            {
                // Dodatno da se pove stevilo izdelkov
                let num_of_items_input = document.createElement("input");
                num_of_items_input.type = 'number';
                num_of_items_input.id = 'num-of-items';
                num_of_items_input.name = 'num_of_items';
                

                let num_of_items_label = document.createElement("label");
                num_of_items_label.htmlFor = 'num_of_items';
                num_of_items_label.innerText = 'Število izdelkov';

                content_admin.appendChild(num_of_items_label);
                content_admin.appendChild(document.createElement('br'));
                content_admin.appendChild(document.createElement('br'));
                content_admin.appendChild(num_of_items_input);
                currentTable = 'storage_location';
            }
            else
                currentTable = this.id;

            if(currentTable != 'deleted' && currentTable != 'active')
                $.ajax({
                    url: "../db_query/Admin/admin_load_attributes.php",
                    type: "POST",
                    data: {"table":currentTable},
                    success: function(response){
                        tableAttributes = JSON.parse(response);
                        // updateTable(content_admin, currentTable, tableAttributes);
                        $(document).find(".data_table").remove();
                        $.ajax({
                            url: "../db_query/Admin/admin_load_data.php",
                            type: "POST",
                            data: {"table":currentTable},
                            success: function(response){
                                displayData(content_admin, JSON.parse(response), tableAttributes);
                                let table_items = document.getElementById("table-response").getElementsByTagName("tr");
                            }
                        });
                    }
                });
        });
    }
}