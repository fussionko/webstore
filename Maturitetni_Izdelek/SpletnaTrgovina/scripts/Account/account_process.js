// Vspešna prijava
function successLogin()
{
    let destroy = document.getElementById("container-login");
    while (destroy.firstChild) 
        destroy.removeChild(destroy.firstChild);
    
    let add = document.createElement("div");
    let spanAdd = document.createElement("span");
    spanAdd.innerHTML = "Uspešna prijava";
    add.appendChild(spanAdd);

    destroy.appendChild(add);
}

//Vspešna registracija
function successRegistration()
{
    let destroy = document.getElementById("container-registration");
    while (destroy.firstChild) 
        destroy.removeChild(destroy.firstChild);
    
    let add = document.createElement("div");
    let spanAdd = document.createElement("span");
    spanAdd.innerHTML = "Uspešna registracija";
    add.appendChild(spanAdd);

    destroy.appendChild(add);
}

function successPasswordReset()
{
    let destroy = document.getElementById("container-password-reset");
    while (destroy.firstChild) 
        destroy.removeChild(destroy.firstChild);
    
    let add = document.createElement("div");
    let spanAdd = document.createElement("span");
    spanAdd.innerHTML = "Uspešnna ponastavitev gesla";
    add.appendChild(spanAdd);

    destroy.appendChild(add);
}

function successInputPasswordReset()
{
    let destroy = document.getElementById("container-password-reset");
    while (destroy.firstChild) 
        destroy.removeChild(destroy.firstChild);
    
    let add = document.createElement("div");
    let spanAdd = document.createElement("span");
    spanAdd.innerHTML = "Na ta email račun je bil poslan link za ponastavitev gesla";
    add.appendChild(spanAdd);

    destroy.appendChild(add);
}

function setupInputs() 
{
    let inputs = document.getElementsByTagName("input");
    for(let x = 0; x < inputs.length; x++)
    {
        inputs[x].addEventListener("change", function() {
            if(this.value.length > 0)
                this.parentElement.querySelector("label").classList.add("stay");
            else
                this.parentElement.querySelector("label").classList.remove("stay");
        });
        inputs[x].addEventListener("focusout", function() {
            if(this.value.length > 0)
                this.parentElement.querySelector("label").classList.add("stay");
            else
                this.parentElement.querySelector("label").classList.remove("stay");
        });
        inputs[x].addEventListener("mouseleave", function() {
            if(this.value.length > 0)
                this.parentElement.querySelector("label").classList.add("stay");
            else
                this.parentElement.querySelector("label").classList.remove("stay");
        });
    }
}

function prepareData(inputData)
{
    let data = {};
    Array.prototype.forEach.call(inputData, element => {
        if(element.name = 'gender-type')
            if(element.checked)
                data['gender'] = element.id
        else
            data[element.id] = element.value;
    });
    return data;
}
