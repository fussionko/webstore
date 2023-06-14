let sessionStorage = window.sessionStorage;

const cart_temp_addr = "temp_addr";
const cart_temp_card = "temp_card";
const cart_temp_shipping = "temp_shipping";

function logAll()
{
    console.log("temp_addr", sessionStorage.getItem(cart_temp_addr));
    console.log("temp_card", sessionStorage.getItem(cart_temp_card));
    console.log("temp_shipping", sessionStorage.getItem(cart_temp_shipping));
}

function clearSessionStorage()
{
    sessionStorage.removeItem(cart_temp_addr);
    sessionStorage.removeItem(cart_temp_card);
    sessionStorage.removeItem(cart_temp_shipping);
}

function saveAddressSessionStorage()
{
    let data = getAddressData();
    sessionStorage.setItem(cart_temp_addr, JSON.stringify(data));
}

function saveShippingSessionStorage()
{
    let data = getShippingData();
    sessionStorage.setItem(cart_temp_shipping, JSON.stringify(data));
}

function saveCardSessionStorage()
{
    let data = getCardData();
    sessionStorage.setItem(cart_temp_card, JSON.stringify(data));
}

function getSessionStorageAddress()
{
    return sessionStorage.getItem(cart_temp_addr);
}

function getSessionStorageCard()
{
    return sessionStorage.getItem(cart_temp_card);
}

function getSessionStorageShipping()
{
    return sessionStorage.getItem(cart_temp_shipping);
}

function clearOrderSessionData()
{
    sessionStorage.clear();
}