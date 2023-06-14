let localStorage = window.localStorage;

const cart = "temp_cart";

function saveLocalStorageCart(data)
{
    localStorage.setItem(cart, JSON.stringify(data));
}

function removeLocalStorageCart()
{
    localStorage.removeItem(cart);
}

function getLocalStorageCart()
{
    return localStorage.getItem(cart);
}