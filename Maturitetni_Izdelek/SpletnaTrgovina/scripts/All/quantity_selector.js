function showQuantityButton(parent, quantity, id)
{
    let quantity_minus = document.createElement("div");
    quantity_minus.classList.add("quantity-minus");
    quantity_minus.innerHTML = "&#8722";

    let quantity_plus = document.createElement("div");
    quantity_plus.classList.add("quantity-plus");
    quantity_plus.innerHTML = "&#43";

    let quantity_number = document.createElement("div");
    quantity_number.classList.add("quantity-number");
    quantity_number.id = 'quan-id-'+id;
    if(quantity === undefined)
        quantity_number.innerText = 1;
    else quantity_number.innerText = parseInt(quantity);

    quantity_minus.addEventListener("click", function(){
        let current_quantity = parseInt($(this).parent().find(".quantity-number").text());
        if(current_quantity == 1)
            return;

        $(this).parent().find(".quantity-number").text(current_quantity-1);
    })

    quantity_plus.addEventListener("click", function(){
        let current_quantity = parseInt($(this).parent().find(".quantity-number").text());
        if(current_quantity == 10)
            return;

        $(this).parent().find(".quantity-number").text(current_quantity+1);
    })

    parent.append(quantity_minus);
    parent.append(quantity_number);
    parent.append(quantity_plus);

}