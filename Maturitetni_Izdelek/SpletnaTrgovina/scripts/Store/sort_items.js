function get_search_params_url()
{
    return getSearchValue("sort");
}

function getItems()
{
    let query = getSearchQuery();
    
    let data = {};

    let url = new URL(window.location);
    url = url.search;
    if(!url.includes("cat=all"))
    {
        query.forEach(element => 
        {
            if(element[0] == 'cat')
                data["parent_category"] = element[1];
            else if (element[0] == 'sub')
            {
                if(data.hasOwnProperty("category"))
                    data["parent_category"] = element[1];
                data["category"] = element[1];
            }
                
        });
        if(data.hasOwnProperty("parent_category"))
            if(!data.hasOwnProperty("category"))
            {
                data["category"] = data["parent_category"];
                data["parent_category"] = 'x';
            }
    }
    else data = 1;

    $.ajax({
        url: "../db_query/Store/store_get_category_items.php",
        type: "POST",
        data: {"data" : data, "param" : get_search_params_url()},
        success: function(response){
            let parent = document.getElementsByClassName("products-display")[0];
            if(response == 0)
            {
                removeItemsFromDisplay(parent)
                let div = document.createElement("div");
                if(data === 1)
                {
                    div.innerText = 'Na ponudbi ni izdelkov'; 
                    parent.appendChild(div);
                    return;
                }
                div.innerText = 'Za niz ' + data["category"] + ' ni bilo najdenih izdelkov!'; 
                parent.appendChild(div);
                return;
            }
            else if(response == -1)
                return;
            removeItemsFromDisplay(document.getElementsByClassName("products-display")[0]);

            if(parent === undefined)
            {
                clearChange();
                $("#container-change").load("../templates/Store/store_display_items_template.php", function() {
                    setup_sort_buttons();
                    setup_search();
                    parent = document.getElementsByClassName("products-display")[0];
                    displayItems(parent, JSON.parse(response), sessionStorageIndex.getItem('index_start'), sessionStorageIndex.getItem('index_end'));
                });
                return;
            }
            setup_sort_buttons();
            setup_search();
            displayItems(parent, JSON.parse(response), parseInt(sessionStorageIndex.getItem('index_start')), parseInt(sessionStorageIndex.getItem('index_end')));
        }
    });
}

let num_of_items = 30;
let index_max;
let sessionStorageIndex = window.sessionStorage;
sessionStorageIndex.clear();
reset_items_index();

setup_page_selector();

function setup_page_selector()
{
    document.getElementById("page-left").addEventListener("click", function(){
        setup_items_index_minus();
        if(hasAttrURL("search")) search();
        else getItems();
    });
    document.getElementById("page-right").addEventListener("click", function(){
        setup_items_index_plus();
        if(hasAttrURL("search")) search();
        else getItems();
    });
}

function reset_items_index()
{
    sessionStorageIndex.setItem('index_start', 0);
    sessionStorageIndex.setItem('index_end', num_of_items);   
    index_max = 0;
}

function setup_items_index_minus()
{
    let index_start = parseInt(sessionStorageIndex.getItem('index_start'));
    let index_end = index_start;

    if(index_start == 0)
        return;

    if(index_end === null)
        index_start = 0;
    else 
        index_start = index_end - num_of_items;

    sessionStorageIndex.setItem('index_start', index_start);
    sessionStorageIndex.setItem('index_end', index_end);   
}

function setup_items_index_plus()
{
    let index_end = parseInt(sessionStorageIndex.getItem('index_end'));
    let index_start;

    if(index_max - (index_max%num_of_items) + num_of_items < index_end) return;

    if(index_end === null)
        index_start = 0;
    else 
        index_start = index_end;
    
    index_end = index_start + num_of_items;
    sessionStorageIndex.setItem('index_start', index_start);
    sessionStorageIndex.setItem('index_end', index_end);   
}

document.getElementById("main-category").addEventListener("click", function(){ 
    $(".category").removeClass("active");
    $(".sub").removeClass("show");
    replaceQuery("cat", "all");
    getItems();
});