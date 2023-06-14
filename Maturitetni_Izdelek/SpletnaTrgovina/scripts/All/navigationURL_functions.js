// Funckije za navigacijo

// Nastavi default poizvedbo v URL
function startQuery(atr, value)
{
    let url = window.location.href;
    if(url.includes("?")) return 1;
    replaceQuery(atr, value);
    return 0;
}

// Resetira vse na defualt classe
function resetAll() 
{
    $(document).find("ul > li.category").removeClass("active");
    $(document).find("ul").removeClass("show");
}

// Doda class active k elementu glede Iskani class
function makeActive(className, classSearch, innerCheck) 
{
    let search = "."+classSearch + " > li";
    let p = $(document).find(search).children().filter(function() {
    return $(this).text() == innerCheck;
    }).parent();
    p.addClass(className);
    p.children("ul").addClass("show");
}

// Vzame vse poizvedbe iz URL in jih naredi aktivne
function navigateURL() 
{
    let query = getSearchQuery();
    query.forEach(element => 
    {
        makeActive('active', element[0], element[1]);
    });
}


// Vrne array ood querieev
function getSearchQuery()
{
    let query = decodeURI(window.location.search).slice(1).split('&');
    let all = [];
    query.forEach(element => {
        all.push(element.split('='));
    });
    return all;
}
