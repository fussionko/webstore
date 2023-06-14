function displayItems(parent, items, index_start, index_end)
{
    if(index_start > items.length)
        return;

    index_max = items.length;

    for(let i = index_start; i < index_end; i++)
    {
        if(items[i] === undefined) continue;
        if(items[i][1]["image_location"] === null) continue;
        let div = document.createElement('div');
        div.classList.add('item');

        let img = document.createElement('img');
        img.classList.add('item-image');
        img.src = items[i][1]["image_location"][0]; //TEMP

        // animateDisplayImage(img);
    
        let itemName = document.createElement('span');
        itemName.classList.add('item-name');
        itemName.append(items[i][1]["itemName"]);
        itemName.innerText = items[i][1]["itemName"];
        
        let price = document.createElement('span');
        price.classList.add('item-price');
        price.innerText = items[i][1]["price"] + '€';

        let desc = document.createElement('span');
        desc.classList.add('item-desc');
        desc.innerText = items[i][1]["product_description"] !== undefined ? items[i][1]["product_description"] : '';

        let discount = document.createElement('span');
        discount.classList.add('item-discount');
        discount.innerText = items[i][1]["discount"] !== undefined ? items[i][1]["discount"] : '';

        let item_id = document.createElement('span');
        item_id.style.display = 'none';
        item_id.innerText = items[i][1]["idItem"];
        item_id.classList.add('item-id');
   
        let add_to_cart_button = document.createElement('div');
        add_to_cart_button.classList.add('item-addToCart');
        add_to_cart_button.innerText = 'V košarico';
        add_to_cart_button.style.border = '1px solid black';
        add_to_cart_button.addEventListener('click', function(){
            id_item = $(this).parent().find(".item-id").text();
            if(checkCorrectId(id_item) == 0) return 0;
            let quantity = 1;
            addItemToCart(id_item, quantity);

        });

        div.appendChild(item_id);
        div.appendChild(img);
        div.appendChild(itemName);
        div.appendChild(document.createElement("br"));
        div.appendChild(price);
        div.appendChild(desc);
        div.appendChild(discount);
        div.appendChild(add_to_cart_button);

        div.addEventListener("click", function(){
            let id = items[i][1]["idItem"];
            let name =  items[i][1]["itemName"];
            display_item(id, name);
            setup_category(id);
        })

        parent.appendChild(div);
    }
}   

function clearChange()
{
    $("#container-change").empty();
}

function display_item(id, name)
{
    let url = document.location.href.split('?')[0];
    url += "?name="+name+"&id="+id;
    window.history.pushState('', '', url);
    clearChange();
    $("#container-change").load("../templates/Store/item_template.php", () => {
        // $("container-images")
        $.ajax({
            type: "POST",
            url: "../db_query/Store/store_get_full_item_data.php",
            data: {"id_item" : id},
            success: function(response){
                let data = JSON.parse(response);
                loadBasketButton();
                for(let i = 0; i < data["images"].length; i++)
                    addImage(data["images"][i], data["data"]["itemName"]);   
                showSlides(slideIndex);
                showAttributesData(data["attributes"], $("#container-attributes-data"));
                showDescription(data["data"]["description"], $("#container-description"));
                showName(data["data"]["itemName"], $("#container-item-name"));
                showQuantityButton($(".container-quantity-selector"));
            }
        });
    });
}

function setup_sort_buttons()
{
    document.getElementById('sort-alphabet').addEventListener("click", function(){
        replaceThisQuery("sort", "abc");
        if(hasAttrURL("search") === true)
            search();
        else getItems();
    })
    
    document.getElementById('sort-price-low').addEventListener("click", function(){
        replaceThisQuery("sort", "prclow");
        if(hasAttrURL("search") === true)
            search();
        else getItems();
    })
    
    document.getElementById('sort-price-high').addEventListener("click", function(){
        replaceThisQuery("sort", "prchigh");
        if(hasAttrURL("search") === true)
            search();
        else getItems();
    })
}

setup_sort_buttons();


// Odstrani otroške elemente od starša
function removeItemsFromDisplay(parent)
{
    $(parent).empty();
}

let search_input = document.getElementById("search-click");

function search(search_data)
{
    let data = document.getElementById("search-input").value;
    let parent = document.getElementsByClassName("products-display")[0];

    if(search_data !== undefined)
    {
        data = search_data;
        document.getElementById("search-input").value = data;
    }
    else if(data.length == 0)
    {
        data = getSearchValue("search");
        document.getElementById("search-input").value = data;
    }
    else
        replaceQuery("search", data);

    if(data === undefined)
    {
        parent.innerText = "Žal nismo našli iskanega";
        return;
    } 

    $.ajax({
        url: "../db_query/Store/store_search_engine.php",
        type: "POST",
        data: {"data" : data, "param" : get_search_params_url()},
        success: function(response){
            removeItemsFromDisplay(parent)
            $("#cat_id").children().removeClass("active");
            if(response == 0)
            {
                removeItemsFromDisplay(parent)
                let div = document.createElement("div");
                if(data.includes('+'))
                    div.innerText = 'Za niz \'' + data.replace('+', ' ') + '\' ni bilo najdenih izdelkov!';
                else  
                    div.innerText = 'Za niz \'' + data.replace('+', ' ') + '\' ni bilo najdenih izdelkov!'; 
                parent.appendChild(div);
                return;
            }
            else if(response == -1)
                return;

            if(parent === undefined)
            {
                clearChange();
  
                $("#container-change").load("../templates/Store/store_display_items_template.php", function() {
   
                    setup_sort_buttons();
                    setup_search();
                    parent = document.getElementsByClassName("products-display")[0];
                    displayItems(parent, JSON.parse(response), sessionStorageIndex.getItem('index_start'), sessionStorageIndex.getItem('index_end'));
                });
                return;
            }
            setup_sort_buttons();
            setup_search();
            displayItems(parent, JSON.parse(response), parseInt(sessionStorageIndex.getItem('index_start')), parseInt(sessionStorageIndex.getItem('index_end')));
        },
    });
}

function setup_search()
{
    search_input.addEventListener("click", function(){
        search();
    });
    
    window.addEventListener("keydown", function(event) {
        if(event.key !== "Enter") return;
        search();
    });
    
}
setup_search();






