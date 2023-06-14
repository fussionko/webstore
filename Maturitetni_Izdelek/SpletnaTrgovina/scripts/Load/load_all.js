$.getScript("../scripts/All/queryURL_functions.js");
$.getScript("../scripts/All/navigationURL_functions.js");
$.getScript("../scripts/All/quantity_selector.js");
$.getScript("../scripts/Account/account_process.js");

$(document).ready(function() {
  //  $(this).find("#container-top").load("../templates/All/navbar_template.php");
    $.ajax({
        url: "../templates/All/navbar_template.php",
        type: "GET",
        success: function(response){
            document.getElementById("container-top").innerHTML = response;
        
            $.ajax({
                url: "../db_query/Account/account_check_login.php",
                type: "GET",
                success: function(response){    
                    let l1 = document.createElement("li");
                    let l2 = document.createElement("li");
                    if(response==0)
                    {
                        l1.innerHTML = '<a href="../pages/account.php?login=n">Prijava</a>';
                        l2.innerHTML = '<a href="../pages/account.php?reg=y">Registracija</a>';
                    }
                    else if(response ==1)
                    {
                        l1.innerHTML = '<li id="button-logout">Odjava</li>';
                        l2.innerHTML = '<li>Racun</li>';
                    }
                    document.getElementById("account-list").appendChild(l1);
                    document.getElementById("account-list").appendChild(l2);

                    $.getScript("../scripts/Order/order_session_storage_functions.js", () => {
                        $.getScript("../scripts/Cart/cart_local_storage_functions.js", () => {
                            $.getScript("../scripts/Cart/cart_display_functions.js", () => {
                                displayCartNavbar();
                            });
                        })
                    });
                }
            });
        }
    });
    $.getScript("../scripts/All/sticky_menu.js");
    $.getScript("../scripts/All/active_page.js");
    $.getScript("../scripts/All/top_cat.js");
});
