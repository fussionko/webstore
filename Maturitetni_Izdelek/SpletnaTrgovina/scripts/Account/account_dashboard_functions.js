function logoutButton()
{
    document.getElementById("button-logout").addEventListener("click", function(e) {
        e.preventDefault();
        $.ajax({
            url: "../db_query/Account/account_logout.php",
            type: "GET",
            success: function() {
                replaceQuery("login", "n");
                addLogin();
                clearSessionStorage();
            }
        });
    });
}
