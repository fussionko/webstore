let num = window.innerHeight;

$(document).ready(function() {
    $(window).bind('scroll', function () {
        if ($(window).scrollTop() >= 70 && !$(document).find('#container-top').hasClass(".sticky")) 
            $('#container-top').addClass('sticky');
        else 
            $('#container-top').removeClass('sticky');
    });
});
