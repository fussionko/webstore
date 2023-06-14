if(check_error === undefined)
    var check_error = 0;

function displayCartNavbar()
{
    if(checkLogin() == 0)
    { 
        if(validateCart() == 0) 
        {
            errorHandle();
            return;
        }
        getTempCartItems();
        return;
    }
    $.ajax({
        url: "../db_query/Store/store_get_cart_items.php",
        type: "POST",
        success: function(response){
            if(response == -1)
            {
                errorHandle();
                return;
            }
            else if(response == 0)
            {
                emptyCartHandle()
                return;
            }
            displayMainCart(JSON.parse(response));
        }
    });
}

function loadCart()
{
    setupCartSummary();
    if(checkLogin() == 0)
    { 
        if(validateCart() == 0) 
        {
            errorHandle();
            return;
        }
        getTempCartItems();
        return;
    }
    $.ajax({
        url: "../db_query/Store/store_get_cart_items.php",
        type: "POST",
        success: function(response){

            if(response == 0)
            {
                errorHandle();
                return;
            }
            
            clearCart();
            displayCart(JSON.parse(response));
        }
    });
}

function displayMainCart(data)
{
    clearCartNavbar();
    displayItemsInCartNavbar(data);

    let url = window.location.href;
    if(url.includes('cart.php') && url.includes("order") == 0)
    {
        clearCart();
        displayCart(data);
    }
}

function getTempCartItems()
{
    let data = getLocalStorageCart();

    if(data === null || data.length == 2) 
    {
        emptyCartHandle();
        return;
    }

    data = JSON.parse(data);
    $.ajax({
        url: "../db_query/Cart/cart_get_item_data.php",
        type: "POST",
        data: {"data" : data},
        success: function(response){
            if(response == 0)
            {
                //napaka pri dodajanu
                return;
            }
            displayMainCart(JSON.parse(response));
        }
    });
}

function addItemToCart(id_item, quantity)
{
    if(checkLogin() == 0)
    {
        let local_cart = getLocalStorageCart();
        if(local_cart === null)
        {
            let obj = {};
            obj[id_item] = quantity;
            saveLocalStorageCart(obj);
        }
        else
        {
            let data = JSON.parse(local_cart);
            if(data.hasOwnProperty(id_item))
            {
                data[id_item] += quantity;
            }
            else
            {
                data[id_item] = quantity;
            }
            saveLocalStorageCart(data);
        }
       
        displayCartNavbar();
    }
    else
    {
        $.ajax({
            url: "../db_query/Store/store_add_to_cart.php",
            type: "POST",
            data: {"id_item": id_item, "quantity" : quantity},
            success: function(response){
                if(response == 0)
                {
                    //napaka pri dodajanu
                    return;
                }

                displayCartNavbar();
            }
        });
    }
}

function changeQuantity(id, quantity)
{
  
    if(checkLogin() == 1)
    {
        $.ajax({
            type: "POST",
            url: "../db_query/Cart/cart_change_quantity.php",
            data: {"id_item":id, "quantity":quantity},
            async: true,
            success: function(response){
         
                if(response == -1 || response == 0)
                {
                    errorHandle();
                    return;
                }
                displayCartNavbar();
            }
        });
    }
    else
    {
        let local_cart = JSON.parse(getLocalStorageCart());
        if(!local_cart.hasOwnProperty(id))
        {
            errorHandle();
            return;
        }
        local_cart[id] = quantity;
        saveLocalStorageCart(local_cart);
        displayCartNavbar();
    }

}


function displayItemsInCartNavbar(items) 
{
    let sum_price = 0.00;

    let cart_list = $("#cart-list");

    for(const element in items)
    {
        let li = document.createElement("li");

        let div = document.createElement("div");
        div.classList.add('cart-item');

        let img = document.createElement('img');
        img.classList.add('cart-item-image');
        img.src = items[element]["image_location"]; 
        img.style.width = '20px';
        img.style.height = '20px';

        let itemName = document.createElement('span');
        itemName.classList.add('cart-item-name');
        itemName.append(items[element]["itemName"]);
        itemName.innerText = items[element]["itemName"];
        
        let price = document.createElement('span');
        price.classList.add('cart-item-price');
        price.innerText = items[element]["price"] + '€';

        let discount = document.createElement('span');
        discount.classList.add('cart-item-discount');
        discount.innerText = items[element]["discount"] === undefined ? '' : items[element]["discount"];

        let quantity = document.createElement('span');
        quantity.classList.add('cart-item-quantity');
        quantity.innerText = items[element]["quantity"];

        let item_id = document.createElement('span');
        item_id.style.display = 'none';
        item_id.innerText = element;
        item_id.classList.add('cart-item-id');

        let remove_button = document.createElement('div');
        remove_button.classList.add("cart-item-remove-button");
        let remove_button_img = document.createElement("img");
        remove_button_img.src = "../images/site_image/close.jpeg";
        remove_button_img.classList.add("cart-item-remove-button-img");
        remove_button_img.alt = 'X';
        remove_button_img.style.width = '10px';
        remove_button_img.style.height = '10px';
        
        remove_button_img.addEventListener("click", function(){
            id_item = $(this).parent().parent().find(".cart-item-id").text();
            if(checkCorrectId(id_item) == 0) return 0;
            if(checkLogin() == 0)
            {
                removeItem(id_item);
                if(validateCart() == 0)
                {
                    removeLocalStorageCart();
                    return;
                }
            }
            else 
            {
                removeUserCartNavbar(id_item);
            }

            displayCartNavbar();
        });

        remove_button.appendChild(remove_button_img);

        div.appendChild(item_id);
        div.appendChild(img);
        div.appendChild(itemName);
        div.appendChild(price);
        div.appendChild(discount);
        div.appendChild(quantity);
        div.appendChild(remove_button);


        sum_price += getSumPrice(items[element]["price"], items[element]["quantity"])

        li.appendChild(div);
        cart_list.append(li);
    }

    let sum_price_div = document.createElement('div');
    sum_price_div.innerText = 'Skupno: ' + sum_price + '€';
    sum_price_div.style.borderTop = '3px solid black';
    cart_list.append(sum_price_div);
}

function displayCart(items)
{
    let parent = document.getElementById("container-items");
    for(const element in items)
    {
        let div = document.createElement("div");
        div.classList.add('item');

        let img = document.createElement('img');
        img.classList.add('item-image');
        img.src = items[element]["image_location"]; 
        img.style.width = '20px';
        img.style.height = '20px';

        let itemName = document.createElement('span');
        itemName.classList.add('item-name');
        itemName.append(items[element]["itemName"]);
        itemName.innerText = items[element]["itemName"];
        
        let price = document.createElement('span');
        price.classList.add('item-price');
        price.innerText = items[element]["price"] + '€';

        let discount = document.createElement('span');
        discount.classList.add('item-discount');
        discount.innerText = items[element]["discount"] === undefined ? '' : items[element]["discount"];

        let quantity = document.createElement('span');
        quantity.classList.add('container-quantity-selector');
        showQuantityButton(quantity, items[element]["quantity"], element)
    
  

        let item_id = document.createElement('span');
        item_id.style.display = 'none';
        item_id.innerText = element;
        item_id.classList.add('item-id');

        let remove_button = document.createElement('div');
        remove_button.classList.add("item-remove-button");
        let remove_button_img = document.createElement("img");
        remove_button_img.src = "../images/site_image/close.jpeg";
        remove_button_img.classList.add("item-remove-button-img");
        remove_button_img.alt = 'X';
        remove_button_img.style.width = '10px';
        remove_button_img.style.height = '10px';
        
        remove_button_img.addEventListener("click", function(){
            id_item = $(this).parent().parent().find(".item-id").text();
            if(checkCorrectId(id_item) == 0) return 0;

            if(checkLogin() == 0)
            {
                removeItem(id_item);
                if(validateCart() == 0)
                {
                    removeLocalStorageCart();
                    return;
                }
            }
            else 
            {
                removeUserCartNavbar(id_item);
            }

            displayCartNavbar();

        });

        remove_button.appendChild(remove_button_img);

        div.appendChild(item_id);
        div.appendChild(img);
        div.appendChild(itemName);
        div.appendChild(price);
        div.appendChild(discount);
        div.appendChild(quantity);
        div.appendChild(remove_button);


        parent.appendChild(div);

        let observer = new MutationObserver(function(mutations) {
            changeQuantity(target.id.split('-')[2], target.innerText)
            
        });

        let target = document.getElementById("quan-id-"+element);
        observer.observe(target, {
            attributes:    true,
            childList:     true,
            characterData: true
        });
    }
}

function validateCart()
{
    let data = getLocalStorageCart();

    if(data === null || data.length == 2) 
    {
        emptyCartHandle()    
        return 1;
    }

    data = JSON.parse(data);

    let temp = $.ajax({
        url: "../db_query/Cart/cart_validate.php",
        type: "POST",
        data: {"temp_cart": data},
        async: false,
        success: function(response){
            if(response == 0)
                return 0;
            else if(response == 1) return 1;
        }
    });

    if(temp.responseText == 0) return 0;
    else if(temp.responseText == 1) return 1;
    return undefined;
}

function removeItem(id_item)
{
    let data = JSON.parse(getLocalStorageCart());
    delete data[id_item];
    saveLocalStorageCart(data);
}

function clearCart()
{
    let cart_list = $("#container-items");
    cart_list.children().remove(); 
}

function clearCartNavbar()
{
    let cart_list = $("#cart-list");
    cart_list.children().remove();    
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

function errorHandle()
{
    clearCartNavbar();
    let cart_list = $("#cart-list");
    let li = document.createElement("li");
    li.innerText = "Napaka";
    cart_list.append(li);
    let url = window.location.href;
    if(url.includes('cart.php') && url.includes('order') == 0)
    {
        clearCart();
        let parent = document.getElementById("container-items");
        let div = document.createElement("div");
        div.classList.add('item');
        div.innerText = "Napaka";
        parent.appendChild(div);
    }
}

function emptyCartHandle()
{
    clearCartNavbar();
    let cart_list = $("#cart-list");
    let li = document.createElement("li");
    li.innerText = "Prazna košarica";
    cart_list.append(li);
    let url = window.location.href;
    if(url.includes('cart.php') && url.includes('order') == 0)
    {
        clearCart();
        let parent = document.getElementById("container-items");
        let div = document.createElement("div");
        div.classList.add('item');
        div.innerText = "Prazna košarica";
        parent.appendChild(div);
    }
}

function removeUserCartNavbar(id_item)
{
    $.ajax({
        url: "../db_query/Store/store_remove_from_cart.php",
        type: "POST",
        data: {"id_item": id_item},
        success: function(response){
            if(response == 0)
            {
                errorHandle();
                return;
            } 

            displayMainCart(JSON.parse(response));
        }
    });
}

function getSumPrice(price, quantity)
{
    let temp_price = parseFloat(price*quantity);
    if(temp_price === NaN) return 0.00;
    return temp_price;
}

function setupCartSummary()
{
    let parent = document.getElementById("cart-go-order");
    parent.addEventListener("click", function(){
        if(checkLogin() == 0)
        {
            let data = getLocalStorageCart();
            if(data === null || data.length == 2) 
                return;
        }
        else
        {
            $temp = $.ajax({
                url: "../db_query/Cart/cart_get_cart_data.php",
                type: "POST",
                async: false,
                success: function(response){
                    if(response == -1)
                        return 1;
                    return 0;
                }
            });
            if($temp.responseText == 0) return;
        }
        loadAddressTemplate();
    });
}

function checkCorrectId(input_id)
{
    const regEx = new RegExp('^[0-9]*$');
    if(!regEx.test(input_id)) return 0;
    if(input_id < 0) return 0;
    return 1;
}

