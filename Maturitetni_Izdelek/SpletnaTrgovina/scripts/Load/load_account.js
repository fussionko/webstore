$(document).ready(function() {
    $.getScript("../scripts/Account/account_error_functions.js");
    $.getScript("../scripts/Account/account_load_form.js", () => {
        $.getScript("../scripts/Account/account_dashboard_functions.js");
    });

    $.getScript("../scripts/Account/account_form_functions.js", () => {
        $.ajax({
            url: "../db_query/Account/account_check_login.php",
            type: "GET",
            success: function(response) {

                if(response == 1)
                {
                    loadUserDashboard();
                    return;
                }
                let search_params = getSearchQuery();
                if(search_params[0] == "")
                {
                    replaceQuery("login", "n");
                    search_params = getSearchQuery();
                }
                
                loadMainForm(search_params);

            }
        });
    });
});
