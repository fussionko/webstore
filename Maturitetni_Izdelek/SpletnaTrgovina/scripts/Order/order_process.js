function loadAddressTemplate()
{
    clearMiddle();
    replaceQuery("type", "order");
    $("#container-middle").load("../templates/Order/order_address_template.php", () => {
        loadCountries();
        document.getElementById("button-next").addEventListener("click", function(){
            //ce nisti prjavlen
            if(checkValidAddress() == 1)
                loadCardTemplate();

        });
        document.getElementById("button-prev").addEventListener("click", function(){
            reloadCart();
        });

        //ce nisti prjavlen
        if(checkLogin() == 0)
        {
            let data = getSessionStorageAddress();
            if(data !== null)
                loadAddressData(JSON.parse(data));
        }
        else
        {
            document.getElementById("container-email").style.display = "none";
            loadUserAddresses();
        }

        setupInputs();
    });
}

function loadCardTemplate()
{
    clearMiddle();
    $("#container-middle").load("../templates/Order/order_card_template.php", () => {
        document.getElementById("button-next").addEventListener("click", function(){
            if(checkValidCard() == 1) 
                loadShippingTemplate();
        })
        document.getElementById("button-prev").addEventListener("click", function(){
            loadAddressTemplate();
        })

        document.getElementById("card-expires").addEventListener("input", function(){
            if(this.value.length == 2) this.value += '/';
        })

        document.getElementById("card-number").addEventListener("input", function(){
            let temp_val = this.value.replace(/\s/g, '');
            if(temp_val.length == 16) return;
            if(temp_val.length % 4 == 0) this.value += ' ';
        });

        //ce nisti prjavlen
        if(checkLogin() == 0)
        {
            let data = getSessionStorageCard();
            if(data !== null)
                loadCardData(JSON.parse(data));
        }
        else
        {
            loadUserCards();
        }
        setupInputs();
    });
}

function loadShippingTemplate()
{
    clearMiddle();
    $("#container-middle").load("../templates/Order/order_shipping_template.php", () => {
        document.getElementById("button-next").addEventListener("click", function(){
            if(checkValidShipping() == 1)
                loadOrderSummaryTemplate();

        })
        document.getElementById("button-prev").addEventListener("click", function(){
            loadCardTemplate();
        })

        //ce nisti prjavlen
        if(checkLogin() == 0)
        {
            let data = getSessionStorageShipping();
            if(data !== null)
                loadShippingData(JSON.parse(data));
        }
        loadShippingCompanies();
        setupInputs();
    });
}

function loadOrderSummaryTemplate()
{
    clearMiddle();
    $("#container-middle").load("../templates/Order/order_summary_template.php", () => {
        document.getElementById("button-next").addEventListener("click", function(){
            //logAll();
            if(checkValidOrderSummary() == 1)
            {
                createOrder();
                //orderBeginTransaction();
            }
                
            else 
                orderError();

        })
        document.getElementById("button-prev").addEventListener("click", function(){
            loadShippingTemplate();
        })

        // //ce nisti prjavlen
        // if(checkLogin() == 0)
        // {
        //     let data = getSessionStorageShipping();
        //     if(data !== null)
        //         loadShippingData(JSON.parse(data));
        // }
        // loadShippingCompanies();
        setupInputs();
    });
}

function createOrder()
{
    let data = {};

    let address_id = sendAddress(JSON.parse(getSessionStorageAddress()));
    let id_card = sendCard(JSON.parse(getSessionStorageCard()));
    let shipping_name = JSON.parse(getSessionStorageShipping());
    shipping_name = shipping_name["shipping_company"];
    if(sendShipping({"shipping_company":shipping_name}) != 1)
        return -1;

    let cart;
    if(checkLogin() == 0)
        cart = JSON.parse(getLocalStorageCart());
    else 
        cart = getCartData();

    if(address_id == -1 || id_card == -1 || shipping_name == -1 || cart === null || cart == -1)
    {
        orderError();
        return -1;
    }

    data["id_address"] = address_id;
    data["id_card"] = id_card;
    data["shipping_name"] = shipping_name;
    data["cart"] = cart;
    
    let email;
    if(checkLogin() == 0)
    {
        email = JSON.parse(getSessionStorageAddress());
        email = email["email"];
    }

    $.ajax({
        url: "../db_query/Order/order_create_order.php",
        type: "POST",
        data: data,
        success: function(response){
            if(response == -1)
            {
                //napaka
                afterOrder();
                return;
            }
            if(orderBeginTransaction(id_card, response) == 1)
            {
                clearCartAfterOrder()
                afterOrder();
                orderSuccess(response);
                orderSuccessEmail(email);
            }                     
        }
    });
}

function orderSuccessEmail(email)
{
    $.ajax({
        url: "../db_query/Order/order_send_success_email.php",
        type: "POST",
        data: {"email" : email},
        success: function(response){
            if(response == 0)
            {
                //napaka
                return;
            }
        }
    });

}

function orderSuccess(id_order)
{
    clearMiddle();
    $("#container-middle").load("../templates/Order/order_finish_template.php", () => {
        let order = document.createElement("div");
        order.id = "container-order-data";
        order.innerText = "ID naročila: "+id_order;
        $("#container-load-order").append(order);

        document.getElementById("button-next").addEventListener("click", function(){
            history.replaceState(null, '', "webstore.php?cat=all");
            window.location.reload();
        });
    });


}

function clearCartAfterOrder()
{
    if(checkLogin() == 1)
    {
        $.ajax({
            url: "../db_query/Cart/cart_close_cart.php",
            type: "POST"
        });
    }
    else removeLocalStorageCart();
}

function afterOrder()
{
    clearSessionStorage();
    displayCartNavbar();
}

function orderBeginTransaction(id_card, id_order)
{
    temp = $.ajax({
        url: "../db_query/Payment_processing/payment_begin_transaction.php",
        type: "POST",
        data: {"id_card" : id_card, "id_order" : id_order},
        async: false,
        success: function(response){
            if(response == -1)
            {
                //napaka
                return;
            }
            else if(response == 0)
            {
                // ponovno poskusi

                clearMiddle();
                $("#container-middle").load("../templates/Order/order_reorder_template.php", () => {
                    document.getElementById("button-next").addEventListener("click", function(){
                        if(orderBeginTransaction(id_order) != 1)
                            orderError();
                        else return 1;
                    });
                });
            }
            else if(response == 1) return 1;
            
        }
    });


    if(temp.responseText == 0) return 0;
    else if(temp.responseText == 1) return 1;
    else if(temp.responseText == 1) return -1;
    return undefined;
}

function checkValidOrderSummary()
{
    
    if(checkLogin() == 0)
        if(validateCart(JSON.parse(getLocalStorageCart())) != 1)
        {
            clearSessionStorage();
            return 0;
        }
    else
    {
        if(validateCart() != 1)
        {
            clearSessionStorage();
            return 0;
        }
    }
    if(checkValidAddress(JSON.parse(getSessionStorageAddress())) != 1 || 
        checkValidCard(JSON.parse(getSessionStorageCard())) != 1 ||
        checkValidShipping(JSON.parse(getSessionStorageShipping())) != 1)
    {
        clearSessionStorage();
        return 0;
    }
    return 1;
}

function getCartData()
{
    let temp = $.ajax({
        url: "../db_query/Cart/cart_get_cart_data.php",
        type: "POST",
        async: false,
        success: function(response){
            if(response == -1)
                return -1;
            return response;
        }
    });

    if(temp.responseText == -1) return -1;
    return JSON.parse(temp.responseText);
}

function sendAddress(sum_data)
{
    let data;
    if(sum_data === undefined) data = getAddressData();
    else data = sum_data;

    let temp = $.ajax({
        url: "../db_query/Order/order_add_address.php",
        type: "POST",
        data: data,
        async: false,
        success: function(response){
            if(response == -1)
                return -1;
            return response;
        }
    });

    if(temp.responseText == -1) return -1;
    return parseInt(temp.responseText);
}

function checkValidAddress(sum_data)    
{
    let data;
    if(sum_data === undefined)
        data = getAddressData();
    else data = sum_data;

    let temp = $.ajax({
        url: "../db_query/Order/order_check_valid_address.php",
        type: "POST",
        async: false,
        data: data,
        success: function(response){
            removeInvalidError();
            if(response != 1)
            {
                handleAddressError(JSON.parse(response));
                return 0;
            }
            if(sum_data === undefined)
                saveAddressSessionStorage();
                
            return 1;
        }
    });

    if(temp.responseText == 0) return 0;
    else if(temp.responseText == 1) return 1;
    return undefined;
}

function checkValidCard(sum_data)
{
    let data;
    if(sum_data === undefined)
        data = getCardData();
    else data = sum_data;

    let temp = $.ajax({
        url: "../db_query/Order/order_check_valid_card.php",
        type: "POST",
        async: false,
        data: data,
        success: function(response){
            removeInvalidError();
            if(response != 1)
            {
                handleCardError(JSON.parse(response));
                return 0;
            }
            if(sum_data === undefined)
                saveCardSessionStorage();
            return 1;
        }
    });

    if(temp.responseText == 0) return 0;
    else if(temp.responseText == 1) return 1;
    return undefined;
}

function checkValidShipping(sum_data)
{
    let data;
    if(sum_data === undefined)
        data = getShippingData();
    else data = sum_data;

    // noben ni izbran
    if(data["shipping_company"] === undefined)
        return 0;

    let temp = $.ajax({
        url: "../db_query/Order/order_check_valid_shipping.php",
        type: "POST",
        async: false,
        data: data,
        success: function(response){
            removeInvalidError();
            if(response != 1)
            {
                handleShippingError(JSON.parse(response));
                return 0;
            }
            if(sum_data === undefined)
                saveShippingSessionStorage();
            return 1;
        }
    });

    if(temp.responseText == 0) return 0;
    else if(temp.responseText == 1) return 1;
    return undefined;
}

function sendCard(sum_data)
{
    let data;
    if(sum_data === undefined) data = getCardData();
    else data = sum_data;

    let temp = $.ajax({
        url: "../db_query/Order/order_add_card.php",
        type: "POST",
        data: data,
        async: false,
        success: function(response){
            if(response == -1)
                return -1;
            return response;
        }
    });

    if(temp.responseText == -1) return -1;
    return parseInt(temp.responseText);
}

function sendShipping(sum_data)
{
    let data;
    if(sum_data === undefined) data = getShippingData();
    else data = sum_data;

    let temp = $.ajax({
        url: "../db_query/Order/order_add_shipping.php",
        type: "POST",
        data: data,
        async: false,
        success: function(response){
            if(response == -1)
                return -1;
            return response;
        }
    });

    if(temp.responseText == -1) return -1;
    return parseInt(temp.responseText);
}

function loadAddressData(data)
{
    for(const id in data)
    {
        let temp_fill = document.getElementById(addressIdResolver(id));
        temp_fill.value = data[id];
        if(id == "country_name") $(temp_fill).val(data[id]).change();
        else $(temp_fill).parent().find("label").addClass("stay");
    }
}

function loadCardData(data)
{
    for(const id in data)
    {
        let temp_fill = document.getElementById(cardIdResolver(id));
        temp_fill.value = data[id];
        $(temp_fill).parent().find("label").addClass("stay");
    }
}

function loadShippingData(data)
{
    for(const id in data)
    {
        let temp_fill = document.getElementById(shippingIdResolver(id));
        temp_fill.value = data[id];
        $(temp_fill).parent().find("label").addClass("stay");
    }
}

function setupAddressPhoneNumber()
{
    let phone_number = document.getElementById("telephone-number");
    $(phone_number).empty();
    phone_number.placeholder = 'xxx-xxx-xxx';
}

function loadUserAddresses()
{
    
    $.ajax({
        url: "../db_query/Order/order_get_user_addresses.php",
        type: "POST",
        success: function(response){
            if(response == -1)
            {
                //uporabnik ni priavlen
                return;
            }

            let where = $("#container-user-addresses");
            let span_h = document.createElement("span");
            span_h.id = "user-addresses-title";
            span_h.classList.add("title");
            span_h.innerText = "Dodani naslovi"
            where.append(span_h);

            if(response == 0)
            {
                //ni jih nc najdl
                let span_r = document.createElement("span");
                span_r.id = "user-addresses-response";
                span_r.classList.add("response-text");
                span_r.innerText = "Nimate dodanih naslovov"
                where.append(span_r);
            }
            else
            {
                response = JSON.parse(response);
                for(let i = 0; i < response.length; i++)
                {
                    let radio = document.createElement("input");
                    radio.type = "checkbox";
                    //radio.name = "user-address";
                    radio.classList.add("user-address");

                    let addr_id = document.createElement("span");
                    addr_id.classList.add("user-address-id");
                    addr_id.style.display = "none";
                    addr_id.innerText = response[i]["id_address"];
    
                    let country = document.createElement("div");
                    country.innerText = "Država: "+response[i]["country"];
    
                    let city = document.createElement("div");
                    city.innerText = "Mesto: "+response[i]["city"];
    
                    let postal_code = document.createElement("div");
                    postal_code.innerText = "Poštna številka: "+response[i]["postal_code"];
    
                    let address = document.createElement("div");
                    address.innerText = "Naslov: "+response[i]["address"];
    
                    let phone_number = document.createElement("div");
                    phone_number.innerText = "Poštna številka: "+response[i]["phone_number"];
    
                    radio.appendChild(addr_id);
                    radio.appendChild(country);
                    radio.appendChild(city);
                    radio.appendChild(postal_code);
                    radio.appendChild(address);
                    radio.appendChild(phone_number);
    
                    where.append(radio);

                    radio.addEventListener("change", function(){
                        $(this).parent().find("input[type=checkbox]").not(this).prop("checked", false);
                        if(this.checked == 1)
                            getUserAddress(this.getElementsByTagName("span")[0].innerText);   
                        else
                            clearInputs();
                    })
                }
            }

            let add_address_button = document.createElement("div");
            add_address_button.classList.add("button");
            add_address_button.id = "button-add-user-address"
            add_address_button.innerText = "Dodaj naslov";
            add_address_button.addEventListener("click", function(){
                if(checkValidAddress() == 1)
                {
                    if(sendAddress() != -1)
                    {
                        //uspesno
                        $("#button-add-user-address").remove();
                        clearParent(where);
                        loadUserAddresses();
                    }
                    else
                    {
                        //napaka
                        return;
                    }         
                }
                else
                    {
                        //napaka
                        return;
                    }
            });
            $(".container-button").append(add_address_button);
        }
    });
}

function loadUserCards()
{
    
    $.ajax({
        url: "../db_query/Order/order_get_user_cards.php",
        type: "POST",
        success: function(response){
            if(response == -1)
            {
                //uporabnik ni priavlen
                return;
            }

            let where = $("#container-user-cards");
            let span_h = document.createElement("span");
            span_h.id = "user-cards-title";
            span_h.classList.add("title");
            span_h.innerText = "Dodane kartice"
            where.append(span_h);

            if(response == 0)
            {
                //ni jih nc najdl
                let span_r = document.createElement("span");
                span_r.id = "user-cards-response";
                span_r.classList.add("response-text");
                span_r.innerText = "Nimate dodanih kartic"
                where.append(span_r);
            }
            else
            {
                response = JSON.parse(response);
                for(let i = 0; i < response.length; i++)
                {
                    let radio = document.createElement("input");
                    radio.type = "checkbox";
                    //radio.name = "user-address";
                    radio.classList.add("user-cards");

                    let id_card = document.createElement("span");
                    id_card.innerText = response[i]["id_payment_card"];
                    id_card.classList.add("id_card");
                    id_card.style.display = "none";

                    let card_number = document.createElement("span");
                    card_number.innerText = response[i]["card_number"];
    
                    let cvv = document.createElement("div");
                    cvv.innerText = "CVV: "+response[i]["cvv"];
    
                    let expire_date = document.createElement("div");
                    expire_date.innerText = "Datum veljavnosti: "+response[i]["expire_date"];
    
                    let cardholder_name = document.createElement("div");
                    cardholder_name.innerText = "Lastnik kartice: "+response[i]["cardholder_name"];
    
                   
                    radio.appendChild(id_card);
                    radio.appendChild(card_number);
                    radio.appendChild(cvv);
                    radio.appendChild(expire_date);
                    radio.appendChild(cardholder_name);

                    if(response[i]["description"] != null)
                    {
                        let description = document.createElement("div");
                        description.innerText = "Opis: "+response[i]["description"];
                        radio.appendChild(description);
                    }

                    where.append(radio);
                    radio.addEventListener("change", function(){
                        $(this).parent().find("input[type=checkbox]").not(this).prop("checked", false);
                        if(this.checked == 1)
                            getUserCard(this.getElementsByClassName("id_card")[0].innerText); 
                        else
                            clearInputs();
                    })
                }
            }

            let add_card_button = document.createElement("div");
            add_card_button.classList.add("button");
            add_card_button.id = "button-add-user-cards"
            add_card_button.innerText = "Dodaj kartico";

            add_card_button.addEventListener("click", function(){
                if(checkValidCard() == 1)
                {
                    let check = sendCard();
                    if(check != -1 && check !== undefined)
                    {
                        //uspesno
                        $("#button-add-user-cards").remove();
                        clearParent(where);
                        loadUserCards();
                    }
                    else
                    {
                        //napaka
                        return
                    }         
                }
                else
                {
                    //napaka
                    return;
                }
            });
            $(".container-button").append(add_card_button);
        }
    });
}

function getUserAddress(id_address)
{
    $.ajax({
        url: "../db_query/Order/order_get_user_address.php",
        type: "POST",
        data: {"id_address" : id_address},
        success: function(response){
            if(response == 0)
                return;
            loadAddressData(JSON.parse(response));
        }
    });
}

function getUserCard(id_card)
{
    $.ajax({
        url: "../db_query/Order/order_get_user_card.php",
        type: "POST",
        data: {"id_card" : id_card},
        success: function(response){
            if(response == 0)
                return;
            loadCardData(JSON.parse(response));
        }
    });
}

function loadCountries()
{
    $.ajax({
        url: "../db_query/Order/order_get_country_names.php",
        async: false,
        type: "POST",
        success: function(response){
            let where = $("#country");
            response = JSON.parse(response);
            for(let i = 0; i < response.length; i++)
            {
                let option = document.createElement('option');
                option.value = response[i]["name"];
                option.innerText = response[i]["code"] + ' ' + response[i]["name"];

                if(response[i]["name"] == "Slovenia")
                    option.selected = "selected";

                where.append(option);
            }
            setupAddressPhoneNumber();
            where.on("change", function(){
                setupAddressPhoneNumber();
            });
           
            $("#telephone-number").on("click", function(){
                let placeholder = document.getElementById("telephone-number").placeholder;
                let temp_code = placeholder.split('x')[0];

                $("#telephone-number").val(temp_code);
            });      
        }
    });
}

function loadShippingCompanies()
{
    $("#container-shipping-company label").addClass("stay");
    $.ajax({
        url: "../db_query/Order/order_get_shipping_companies.php",
        type: "POST",
        success: function(response){
            let where = $("#container-shipping-company");
            response = JSON.parse(response);
            for(let i = 0; i < response.length; i++)
            {
                const id = "shipping-company-"+response[i]["name"];

                let radio = document.createElement("input");
                radio.type = "radio";
                radio.name = "shipping-company";
                radio.value = response[i]["name"];
                radio.id = id;

                let label = document.createElement("label");
                label.for = id;

                let name = document.createElement("div");
                name.innerText = response[i]["name"];
                name.classList.add("shipping-company-name");

                let time_to_deliver = document.createElement("div");
                time_to_deliver.innerText = response[i]["time_to_deliver"];
                time_to_deliver.classList.add("shipping-company-deliver");

                let price = document.createElement("div");
                price.innerText = response[i]["price"];
                price.classList.add("shipping-company-price");

                label.appendChild(name);
                label.appendChild(time_to_deliver);
                label.appendChild(price);

                where.append(label);

                where.append(radio);
            } 

            
        }
    });
}

function clearInputs()
{
    $("input").val('');
    $("label").removeClass("stay");
    $("#container-country").find("label").addClass("stay");
}

function clearParent(parent)
{
    $(parent).empty();
}

function removeInvalidError()
{
    $("input").removeClass("invalid");
}

function clearMiddle()
{
    $("#container-middle").empty();
}

function getAddressData()
{
    let data = {};
    data["country_name"] = $("#country").val();
    data["city_name"] = $("#city").val();
    data["postal_code"] = $("#postal-code").val();
    data["address_name"] = $("#address").val();
    data["telephone_number"] = $("#telephone-number").val();
    if(checkLogin() == 0)
        data["email"] = $("#email").val();
    return data;
}

function getCardData()
{
    let data = {};
    data["card_number"] = $("#card-number").val();
    data["cvv"] = $("#cvv").val();
    data["card_expires"] = $("#card-expires").val();
    data["cardholder_name"] = $("#cardholder-name").val();
    let temp_description = $("#description").val();
    if(temp_description.length != 0)
        data["description"] = temp_description;
    return data;
}

function getShippingData()
{
    let data = {};
    data["shipping_company"] = $("input[name=shipping-company]:checked").val();
    return data;
}

function cardIdResolver(php_version)
{
    let id;
    if(php_version == "card_number")
        id = "card-number";
    else if(php_version == "cvv")
         id = "cvv";
    else if(php_version == "card_expires")
        id = "card-expires";
    else if(php_version == "cardholder_name")
        id = "cardholder-name";
    else if(php_version == "description")
        id = "description";
    else id = php_version;
    return id;
}

function addressIdResolver(php_version)
{
    let id;
    if(php_version == "country_name")
        id = "country";
    else if(php_version == "city_name")
         id = "city";
    else if(php_version == "postal_code")
        id = "postal-code";
    else if(php_version == "address_name")
        id = "address";
    else if(php_version == "telephone_number")
        id = "telephone-number";
    else id = php_version;
    return id;
}

function shippingIdResolver(php_version)
{
    let id;
    if(php_version == "shipping_company")
        id = "container-shipping-company";
    else id = php_version;
    return id;
}

function handleAddressError(errors)
{
    removeInvalidError();
    for(let i = 0; i < errors.length; i++)
    {
        id = addressIdResolver(errors[i]);
        if(id === 0)
             document.getElementsByClassName("form")[0].classList.add("invalid");
        else 
            document.getElementById(id).classList.add("invalid");
    }
}

function handleCardError(errors)
{
    removeInvalidError();
    for(let i = 0; i < errors.length; i++)
    {
        id = cardIdResolver(errors[i]);
        if(id === 0)
             document.getElementsByClassName("form")[0].classList.add("invalid");
        else 
            document.getElementById(id).classList.add("invalid");
    }
}

function handleShippingError(errors)
{
    removeInvalidError();
    for(let i = 0; i < errors.length; i++)
    {
        id = shippingIdResolver(errors[i]);
        if(id === 0)
             document.getElementsByClassName("form")[0].classList.add("invalid");
        else 
            document.getElementById(id).classList.add("invalid");
    }
}

function orderError()
{
    clearMiddle();
    $("#container-middle").load("../templates/Order/order_error_template.php", () => {
        setTimeout(reloadCart, 1000);
    });
}

function reloadCart()
{
    clearMiddle();
    removeQuery();
    $("#container-middle").load("../templates/Cart/cart_template.php", () => {
        loadCart();
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