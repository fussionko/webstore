function addImage(img_src, img_alt)
{
    let div = document.createElement("div");
    div.classList.add("mySlides");
    div.classList.add("fade");
   
    let img = document.createElement("img");
    img.src = img_src;
    img.alt = img_alt;
    img.classList.add("item-show-image");

    div.appendChild(img);

    num_of_slides++;
    let span = document.createElement("span");
    span.classList.add("item-image-dot");
    let index = num_of_slides;
    span.addEventListener("click", function(){
        
        currentSlide(index);
    });
    $("#container-image-select").append(span);
    $(".slideshow-container").append(div);
}

let slideIndex = 1;
let num_of_slides = 0;


function plusSlides(n) 
{
    showSlides(slideIndex += n);
}

function currentSlide(n) 
{
    showSlides(slideIndex = n);
}

function showSlides(n) {
    let slides = document.getElementsByClassName("mySlides");
    let dots = document.getElementsByClassName("item-image-dot");
    if (n > slides.length) slideIndex = 1; 
    if (n < 1) slideIndex = slides.length;
    for (i = 0; i < slides.length; i++) 
    {
        slides[i].style.display = "none";  
    }
    for (i = 0; i < dots.length; i++) 
    {
        dots[i].classList.remove("active-image");
    }
    slides[slideIndex-1].style.display = "block";  
    dots[slideIndex-1].classList.add("active-image");
}
