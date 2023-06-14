function showAttributesData(data, parent)
{
    let main_div = document.createElement("div");
    main_div.id = "container-attributes-data";
    for(const attribute_name in data)
    {
        let attribute_value_div = document.createElement("div");
        attribute_value_div.classList.add("container-attribute-value");

        let attribute_div = document.createElement("div");
        attribute_div.classList.add("container-attribute");
        let attribute_span = document.createElement("span");
        attribute_span.classList.add("attribute");
        attribute_span.innerText = attribute_name.replace(/_/g, ' ') + ':';
        if(attribute_name == "ah")
            attribute_span.innerText = "Audio izhodi";
        else if(attribute_name == "st_sys_f")
            attribute_span.innerText = "Število sistemskih fenov";
        else if(attribute_name == "sys_fan")
            attribute_span.innerText = "Sistemski feni";
        else if(attribute_name == "st_fan")
            attribute_span.innerText = "Število fenov"
        else if(attribute_name == "fans")
            attribute_span.innerText = "Feni"
        else if(attribute_name == "cip")
            attribute_span.innerText = "Čip"
        else if(attribute_name == "bp_usb")
            attribute_span.innerText = "Zadnji USB vhodi"
        else if(attribute_name == "fp_usb")
            attribute_span.innerText = "Sprednji USB vhodi"
        else if(attribute_name == "fp_ah")
            attribute_span.innerText = "Sprednji audio vhodi"
        else if(attribute_name == "bp_ah")
            attribute_span.innerText = "Zadnji audio vhodi"
        
        attribute_div.appendChild(attribute_span);

        attribute_value_div.appendChild(attribute_div);

        let value_div = document.createElement("div");
        value_div.classList.add("container-value");
        for(let i = 0; i < data[attribute_name].length; i++)
        {

            let value_inner_div = document.createElement("div");
            value_inner_div.classList.add("container-inner-value");

            if(data[attribute_name][i].includes(":"))
            {
                let data_split = data[attribute_name][i].split(":");

                let value_name_span = document.createElement("span");
                value_name_span.classList.add("value-name");
                value_name_span.innerText = data_split[1];

                let value_num_span = document.createElement("span");
                value_num_span.classList.add("value-num");
                value_num_span.innerText = data_split[0];

                let x_span = document.createElement("span");
                x_span.classList.add("split-x");
                x_span.innerText = 'x';

                if(attribute_name == "sys_fan" || attribute_name == "fans")
                    value_name_span.innerText += "mm"; 
  

                value_inner_div.appendChild(value_num_span);
                value_inner_div.appendChild(x_span);
                value_inner_div.appendChild(value_name_span);

            }
            else 
            {            
                let value_span = document.createElement("span");
                value_span.classList.add("value");

                value_span.innerText = data[attribute_name][i];
      
                if(attribute_name.includes('gb'))
                    value_span.innerText += 'gb';
                else if(attribute_name.includes("hitrost"))
                {
                    if(data[attribute_name].length === 1) value_span.innerText += "GHz";
                    else value_span.innerText += "Hz";                    
                }
                    
                else if(/.*(dolzina|sirina|visina).*/.test(attribute_name))
                    value_span.innerText += "mm";
                else if(attribute_name.includes("sli"))
                {
                    if(value_span.innerText == 1)
                        value_span.innerText = "DA";
                    else if(value_span.innerText == 0)
                        value_span.innerText = "NE";
                }
                else if(attribute_name.includes("tdp") || attribute_name.includes("watt"))
                    value_span.innerText += "W";
                else if(attribute_name.includes("ucinkovitost"))
                    value_span.innerText += "%";    
                else if(attribute_name == "velikost_fan")
                    value_span.innerText += "mm"; 
                    else if(attribute_name == "psu_konektor")
                    value_span.innerText += "pin"
       
                    
                   
                

                value_inner_div.appendChild(value_span);
            }
        


            value_div.appendChild(value_inner_div);
        }

        attribute_value_div.appendChild(value_div);

        main_div.appendChild(attribute_value_div);
    }

    parent.append(main_div);
}

function showDescription(description, parent)
{
    let text_span = document.createElement("span");
    text_span.classList.add("item-description");

    if(description == null)
        text_span.innerText = 'Ni opisa';
    else text_span.innerText = description;

    parent.append(text_span);
}

function showName(name, parent)
{
    let name_span = document.createElement("span");
    name_span.id = "item-name";
    name_span.innerText = name;
    parent.append(name_span);
}

function loadBasketButton()
{
    document.getElementById("button-add-to-basket").addEventListener("click", function(){
        
        let params = new URLSearchParams(document.location.search);
        let id = parseInt(params.get("id")); 
        addItemToCart(id, parseInt($(this).parent().find(".quantity-number").text()));
    }) 
}

