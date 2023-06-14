function showHeat(heat_num, color)
{
    let heat = document.getElementById("heat").getElementsByClassName("add")[0];
    heat.innerText = heat_num + 'W';

    if(color === undefined) return;
    heat.style.color = color;
}

function showPowerUsage(power_usage_num, color)
{
    let power_usage = document.getElementById("power-usage").getElementsByClassName("add")[0];
    power_usage.innerText = power_usage_num + 'W';

    if(color === undefined) return;
    power_usage.style.color = color;
}

function showPrice(price_num)
{
    document.getElementById("price").getElementsByClassName("add")[0].innerText = price_num + '€';
}

function showImage(image_location, parent)
{
    let image = document.createElement("img");
    image.classList.add("config-item-image");
    image.src = image_location;
    parent.append(image);
}

function removePrevImg(parent)
{
    $(parent).find(".config-item-image").remove();
}

function showComponentPrice(parent, price_num)
{
    let price = document.createElement("span");
    price.classList.add("config-item-price");
    price.style.display = "block";
    price.innerText = price_num;
    parent.find(".container-component-price").append(price);

}

function removePrevComponentPrice(parent)
{
    $(parent).find(".config-item-price").remove();
}