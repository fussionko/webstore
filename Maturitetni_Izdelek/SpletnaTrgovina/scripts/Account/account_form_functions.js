function setupLoginEvents(location, search_params)
{
    if(location == "login")
    {
        replaceQuery("login", "n");
        document.getElementById("button-send").addEventListener("click", function(){
            $.ajax({
               url: "../db_query/Account/account_login.php",
               type: "POST",
               data: prepareData($(document).find(".form").find("div > input")),
               success: function(response){
                    //Uspešna prijava brez napak
                    if(errorResponse(JSON.parse(response)))
                    {
                       successLogin();
                       setTimeout(function() {
                           $('#container-middle').load("../templates/Account/template_account_dashboard.php");
                           $.ajax({
                            url: "../templates/Account/template_account_user_login.php",
                            type: "GET",
                            success: function(response){
                                document.getElementById("account-list").innerHTML = response;
                            }
                        });
                       }, 500);
                   }
               }
            });
        });
        document.getElementById("button-registration").addEventListener("click", function(){
            addRegistration();
        });
        document.getElementById("button-password-reset").addEventListener("click", function(){
            addInputPasswordReset();
        });
    }
    else if(location == "registration")
    {
        replaceQuery("reg", "n");
        document.getElementById("button-send").addEventListener("click", function(){
            $.ajax({
               url: "../db_query/Account/account_registration.php",
               type: "POST",
               data: prepareData($(document).find(".form").find("div > input")),
               success: function(response){
                   //Uspešna prijava brez napak
                   if(errorResponse(JSON.parse(response)))
                   {
                        successRegistration();
                        replaceQuery("reg", "y");
                        setTimeout(addLogin, 500);
                   }
                }
            });
        });
        document.getElementById("button-login").addEventListener("click", function(){
            addLogin();
        });
    }

    else if(location == "password-reset")
    {
        if(search_params === undefined) return "ERROR";
        document.getElementById("button-password-reset").addEventListener("click", function(){
            $.ajax({
               url: "../db_query/Account/account_reset_password.php",
               type: "POST",
               data: Object.assign({}, {"email":search_params}, prepareData($(document).find(".form").find("div > input"))),
               success: function(response){
                   //Uspešna prijava brez napak
                   if(errorResponse(JSON.parse(response)))
                   {
                        successPasswordReset();
                        replaceQuery("login", "n");
                        setTimeout(addLogin, 500);
                   }
               }
            });
        });
        document.getElementById("button-login").addEventListener("click", function(){
            addLogin();
        });
    }

    else if("input-password-reset")
    {
        replaceQuery("password-reset", "user");
        document.getElementById("button-password-reset").addEventListener("click", function(){
            let data = prepareData($(document).find(".form").find("div > input"));
            $.ajax({
               url: "../db_query/Account/account_input_reset_password.php", 
               type: "POST",
               data: data,
               success: function(response){
                   if(errorResponse(JSON.parse(response)))
                   {
                        $.ajax({
                            url: "../db_query/Account/account_reset_password_email.php",
                            type: "POST",
                            data: data["email"],
                            success: function(response) {
                                if(response==1)
                                {
                                    successInputPasswordReset();
                                    replaceQuery("login", "n");
                                    setTimeout(addLogin, 1000);
                                }
                                else
                                {
                                    $('#container-middle').load("../templates/Account/template_account_reset_password_error.php", function() {
                                    });
                                }
                            }
                        });

                   }
               }
            });
        });
        document.getElementById("button-login").addEventListener("click", function(){
            addLogin();
        });
    }
}


function loadMainForm(search_params)
{
    if(search_params[0][0] == 'reg')
    {
        $.getScript("../scripts/Account/account_load_form.js", () => addRegistration());
        replaceQuery("reg", "n"); // url nastavi na reg
    }
    else if(search_params[0][0] == 'login')
    {
        $.getScript("../scripts/Account/account_load_form.js", () => addLogin());
        replaceQuery("login", "n"); // url nastavi na login
    }
    else if(search_params[0][0] == "password-reset")
    {
        loadPasswordReset(search_params[0][1]);
    }

}

function loadPasswordReset(params_data)
{
    if(params_data == "user")
    {
        $('#container-middle').load("../templates/Account/template_account_input_password_reset.php", function() {
            $.getScript("../scripts/Account/account_load_form.js", function(){addInputPasswordReset()});
        });
    }
    else
    {
        $.ajax({
            url: "../db_query/Account/account_check_password_reset.php",
            type: "POST",
            data: params_data,
            success: function(response){
                if(response==1)
                {
                    $('#container-middle').load("../templates/Account/template_account_reset_password.php", function() {
                        $.getScript("../scripts/Account/account_load_form.js", function(){addPasswordReset(params_data)});
                    });
                }
                else
                {
                    $('#container-middle').load("../templates/Account/template_account_reset_password_error.php", function() {
                    });
                }

            }
        });
    }
}
function loadUserDashboard()
{
    replaceQuery("login", "y"); // url nastavi na uporabnik
    $('#container-middle').load("../templates/Account/template_account_dashboard.php", function() {
        logoutButton();
        $.ajax({
            url: "../db_query/Account/account_check_admin.php",
            type: "POST",
            success: function(){
                $.getScript("../scripts/Account/account_admin_functions.js", function() {
                    //addCategory();
                    addTableEvents();
                    // tle gre addTableEvents();
                });
            }
        });
    });
}
