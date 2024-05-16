$(document).ready(function () {
    // alert("clickable_tr");

    $(".clickable-tr").click(function () {
        //   ($(this).data("href"));
        window.location = `http://127.0.0.1:8000${$(this).data("href")}`;
    });
});
