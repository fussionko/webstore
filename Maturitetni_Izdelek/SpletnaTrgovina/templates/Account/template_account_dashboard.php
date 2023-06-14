<?php
    require_once("../../db_query/Account/account_user_tables.php");
    session_start();
    //TEMP
    function checkUserAdmin()
    {
        if(isset($_SESSION['username']))
            if(in_array($_SESSION['username'], admin_table))
                return 1;
        return 0;
    }
?>

<div id="container-overview"><span class="overview-name">RAČUN</span><ul id="overview-items">
    <li class="overview-item" id="account-data">Podatki<span></span></li>
    <li class="overview-item" id="account-orders">Naročila<span></span></li>
    <li class="overview-item" id="account-cards">Kartice<span></span></li>
    <li class="overview-item" id="account-addresses">Naslovi<span></span></li>
</ul>

<?php
    if(checkUserAdmin())
    {
        require_once("../../db_query/Admin/admin_functions.php");
        require_once("template_account_dashboard_admin.php");
    }
?>
</div>

<div class="container-content" id="content-user"></div>
<div class="container-content" id="content-admin"></div>
