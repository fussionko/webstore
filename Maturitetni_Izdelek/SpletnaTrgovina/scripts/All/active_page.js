$(".dot").click(function() {         
    $(this).toggleClass("activeTop");
});

//gquery == $  document.ready => izvris se ob zagonu strnai
$(document).ready(function(){
    let url = window.location.href  //url strani
    url = url.split('?')[0];
    $(".dot").each(function() {    // za vsak element preveri ce se ujema z tem urljem
        if (this.href === url) {
         $(this).addClass("activeTop");
        }
    });
});

