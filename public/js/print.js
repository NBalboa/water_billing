$(document).ready(function () {
    // alert("hi from print.js");

    $("#printBtn").on("click", function () {
        // alert("You click me print");

        window.print();
    });
});
