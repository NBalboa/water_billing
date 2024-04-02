$(document).ready(function () {
    // alert("Hello From pay.js");

    $("#money").on("input", function () {
        let money = parseFloat($(this).val()).toFixed(2);
        // let change = parseFloat($("#change").val()).toFixed(2);
        let total = parseFloat($("#total").val()).toFixed(2);

        let change = parseFloat(money - total).toFixed(2);

        if (!isNaN(change)) {
            $("#change").val(change);
        }
        if (isNaN(money)) {
            $("#change").val("");
        }
    });
});
