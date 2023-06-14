$(document).ready(function() {
    $(this).find("#container-middle").load("../templates/Cart/cart_template.php", () => {
        
            $.getScript("../scripts/Order/order_process.js", () => {
                $.getScript("../scripts/Account/account_process.js", () => {
                    $.getScript("../scripts/Cart/cart_display_functions.js", () => {
                        setupCartSummary();
               
                    })
                });
            });  

    });
});