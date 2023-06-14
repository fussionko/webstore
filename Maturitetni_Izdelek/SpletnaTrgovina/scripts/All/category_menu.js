$(".category > span").on("click", function(event) {
    let item = $(this).parent();
    let content = $(this).text();

    //kategorija
    if(item.parent().hasClass("cat"))
    {
        replaceQuery("cat", content);
        change_category(content)
    }
    //podkategorija
    else if(item.parent().hasClass("sub"))
    {
        replaceWholeQuery({"cat":item.parent().parent().find(".category-name").first().text(), "sub":content});
        change_category(content, item.parent().parent().find(".category-name").first().text())
    }

    getItems();
});
 

function change_category(parent_category, category)
{
    if(category === undefined)
    {
        $(".sub").removeClass("show");
        $(".category").removeClass("active");
    } 
    else $(".category").filter(function(){
        return $(this).parent().hasClass("sub");
    }).removeClass("active");
    
   
    let parent = $("li.category").find("span").filter(function(){
        return $(this).text() == parent_category;
    }).parent();

    parent.addClass("active");

    parent = parent.find(".sub");
    parent.addClass("show");

    if(category === undefined) return;

    parent = parent.find("li.category").find("span").filter(function(){
        return $(this).text() == category;
    }).parent();
    parent.addClass("active");
}

function setup_category(item_id)
{
    $.ajax({
        type: "POST",
        url: "../db_query/Store/store_get_categories.php",
        data: {"id_item":item_id},
        success: function(response){
            if(response == -1)
            {
                return;
            }
            response = JSON.parse(response);
            change_category(response["name_parent_category"], response["name_category"])
        }
    });
}