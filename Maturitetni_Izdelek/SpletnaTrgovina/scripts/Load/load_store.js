// Ob zagonu stora nalozi kategorije
$(document).ready(function() {
    $("#container-change").load("../templates/Store/store_display_items_template.php", () => {
        $(this).find(".product-category > ul").load("../templates/category_menu_template.php", () => {
            $.getScript("../scripts/Store/image_selector.js");
            $.getScript("../scripts/All/category_menu.js");
            $.getScript("../scripts/Store/display_item_data.js");
            $.getScript("../scripts/Store/sort_items.js", () => {
                $.getScript("../scripts/Store/display_items.js", () => {
                    let search_params = getSearchQuery();
                    if(search_params[0][0] == "search")
                    {
                        search(search_params[0][1]);
                        return;
                    }
         
                    if(search_params[0][0] == "name" && search_params[1][0] == "id")
                    {
                        display_item(search_params[1][1], search_params[0][1]);
                        setup_category(search_params[1][1]);
                        return;
                    }
                        
                    if(startQuery('cat', 'all'))
                        navigateURL();

                    change_category(decodeURI(search_params[0][1]).replace( /\+/g, ' ' ), search_params[1] === undefined ? undefined : decodeURI(search_params[1][1]).replace( /\+/g, ' ' ))
                    getItems();
                });
            });     
        });
    });

});

