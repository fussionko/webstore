// Doda login template na account.php in zbrise kar je bilo na tem mestu pred njim
function removePrev()
{
    let middle = document.getElementById("container-middle");
    while (middle.firstChild)
        middle.removeChild(middle.firstChild);
}

function addLogin()
{
    removePrev();
    $(document).find("#container-middle").load("../templates/Account/template_account_login.php", function(){
        setupLoginEvents("login");
        setupInputs();
    });
}

// Doda register template na account.php in zbrise kar je bilo na tem mestu pred njim
function addRegistration()
{
    removePrev();
    $(document).find("#container-middle").load("../templates/Account/template_account_registration.php", function() {
        setupLoginEvents("registration");
        setupInputs();
    });
}

function addInputPasswordReset()
{
    removePrev();
    $(document).find("#container-middle").load("../templates/Account/template_account_input_password_reset.php", function() {
        setupLoginEvents("input-password-reset");
        setupInputs();
    });
}


function addPasswordReset(search_params)
{
    removePrev();
    $(document).find("#container-middle").load("../templates/Account/template_account_reset_password.php", function() {
        setupLoginEvents("password-reset", search_params);
        setupInputs();
    });
}

