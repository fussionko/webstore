//Dodaj funkcijo k prever za napake v queriju
function reset()
{
    //neki
}


//nedela cist
function checkQueryError()
{
    let check = new URL(window.location.href);
    check = check.href.split('?')[1];
    if(check==undefined) return;
    if(check.includes('&'))
    {
        check = check.split('&');
        for(let i = 0; i<check.length; i++)
        {
            let find = check[i].split('=');
            let search = 'ul.'+find[0];
            let search2 = 'ul.'+find[0]+' > li.category';
            if($(document).find(search).length <= 0 ||$(document).find(search2).length <= 0)
            {
                removeQuery();
                //location.reload();
                return;
            }

            if(find[0]=='' || find[1]=='')
            {
                removeQuery();
                //location.reload();
                return;
            }
        }
    }
    else
    {
        let find = check.split('=');
        let search = 'ul.'+find[0];
        let search2 = 'ul.'+find[0]+' > li.category';
        if($(document).find(search).length <= 0 ||$(document).find(search2).length <= 0)
        {
            removeQuery();
            //location.reload();
            return;
        }

        if(find[0]=='' || find[1]=='')
        {
            removeQuery();
            //location.reload();
            return;
        }
    }
    startQuery("cat", "all");
}

window.addEventListener('hashchange', function(){
    e.preventDefault();
    checkQueryError();
});