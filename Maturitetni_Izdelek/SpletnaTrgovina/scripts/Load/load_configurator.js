$(document).ready(function() {
    $.getScript("../scripts/Configurator/configurator_display_functions.js");
    $.getScript("../scripts/Configurator/compatibility_functions.js");
    $.getScript("../scripts/Configurator/configurator_check_counter_functions.js");
    $(this).find("#container-middle").load("../templates/Configurator/configurator_item_template.php", () => {
        $.getScript("../scripts/Configurator/configurator_process_data.js").done(function(){
            
                $.getScript("../scripts/Configurator/configurator_save.js").done(function(){
                    
                    
                    $.getScript("../scripts/Configurator/add_component.js").done(() => {
                        setup_page();
                       
                    });
                });

        });
    });
});