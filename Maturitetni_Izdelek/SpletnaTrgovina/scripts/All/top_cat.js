$(".click_top").on("click", function(event) {
    event.preventDefault();
    let content = $(this).text();
    let url = window.location.href;
    url.substring(0, url.lastIndexOf('/'));
    url += "/webstore.php";
    replaceQuery("cat", content);
});