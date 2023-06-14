// Odstrani prejsni error
function errorRemovePrev(parent)
{
    let find = parent.querySelector(".container-error");

    if(find == null) return 0;
    if(find.innerText != '')
    {
        find.remove();
        return 1;
    }

    return 0;
}

// Doda response pod odgovror
function errorResponse(data)
{
    let continueCheck = 1;
    for(let x in data)
    {
        if(data[x] == 0)
            continue;

        let parent = document.getElementById("container-"+x);
        errorRemovePrev(parent);

        if(data[x] == 1) 
        {
            if(x != 'main')
                parent.querySelector("input").classList.remove("invalid");
            continue;
        }

        if(x != 'main')
            parent.querySelector("input").classList.add("invalid");
    
        continueCheck = 0;

        let errorDiv = document.createElement("div");
        errorDiv.classList.add("container-error");

        errorAsignDiv(data[x], errorDiv);

        parent.appendChild(errorDiv);
    }

    return continueCheck;
}


// Vsak error da v svoj div in span
function errorAsignDiv(data, parent)
{
    data.forEach(errorText => {
        let errorSpan = document.createElement("span");
        errorSpan.innerText = errorText;
        parent.appendChild(errorSpan);
    });
}