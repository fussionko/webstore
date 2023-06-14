// URL QUERIES 

// Doda poizvedbo k URL
function appendQuery(atr, value) 
{
    let url = new URL(window.location.href);
    let checkDuplicate = url.searchParams.getAll(atr);
    if(checkDuplicate.length < 0) return alert("Error appendQuery: no attribute found");
    if(checkDuplicate.includes(value)==1) return;
    url.searchParams.append(atr, encodeURIComponent(value));
    window.history.pushState(null, '', url);
}

// Zamenja zadnjo poizvedbo v URL
function replaceLastQuery(atr, value)
{
    let url = new URL(window.location);
    let check = url.search.slice(1);
    if(check.length <= 0) return alert("Error replaceLastQuery: no existing query");
    if(check.includes('&'))
    {
        check = check.split('&');
        check.pop();
        check.push(atr+'='+value);
        check = check.join('&');
    }
    else
        check = atr+'='+value;
    url.search = '?'+check;
    window.history.pushState(null, '', url);
}

// Odstrani zadnjo poizvedbo v URL
function removeLastQuery() 
{
    let url = new URL(window.location.href);
    let params = new URLSearchParams(url.search.slice(1));
    params.delete(params.slice('&')[Array.from(params).length-1]);
    window.history.pushState(null, '', params);
}

// Odstrani poizvedbo do in vključno s tem v URL
function removeQueryToThis(value)
{
    let url = new URL(window.location.href);
    let check = url.search.slice(1);
    if(check.length <= 0) return;
    if(check.includes('&'))
    {
        check = check.split('&');
        let filter = [];
        for(let i = 0; i<check.length; i++)
        {
            if(check[i].includes(value))
                break;
            filter.push(check[i]);
        };
        check = filter.join('&');
        url.search = '?'+check;
    }
    else
        if(check.includes(value)) url.search = '';
    window.history.pushState(null, '', url);     
}

// Zamenja celotno poizvedbo v URL
function replaceQuery(atr, value)
{
    let url = new URL(window.location.href);
    url.href = url.href.split('?')[0];
   
    url.searchParams.set(atr, value);
    window.history.pushState(null, '', url);
}

function replaceThisQuery(atr, value)
{
    let url = new URL(window.location.href);
    url.searchParams.set(atr, value);
    window.history.pushState(null, '', url);
}

function replaceSubQuery(atr, value)
{
    let url = new URL(window.location.href);
    if(window.location.href.includes('sort'))
        url.searchParams.delete("sort");

    url.searchParams.set(atr, value);
    window.history.pushState(null, '', url);
}

function replaceWholeQuery(search)
{
    let url = new URL(window.location.href.split('?')[0]);
    for(const atr in search)
        url.searchParams.set(atr, search[atr]);
    window.history.pushState(null, '', url);
}


// Odstrani poizvedbo iz URL
function removeQuery() 
{
    let url = new URL(window.location.href);
    url = url.href.split('?')[0];
    window.history.pushState(null, '', url);
}

function appendURL(name)
{
    let url = new URL(window.location.href);
    url += "/" + encodeURIComponent(name);
    window.history.pushState(null, '', url);
}

function replaceURL(name, to)
{
    let url = new URL(window.location.href);
    url.split(to)[0];
    url += encodeURIComponent(to) + "/" + encodeURIComponent(name);
    window.history.pushState(null, '', url);
}

function getSearchValue(param)
{
    let url = new URL(window.location.href);
    let params = new URLSearchParams(url.search);
    return params.get(param);
}

function hasAttrURL(atr)
{
    let url = new URL(window.location.href);
    let params = new URLSearchParams(url.search);
    return params.has(atr);    
}

