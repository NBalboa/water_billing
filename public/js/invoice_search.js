$(document).ready(function () {
    $("#invoice_search").on("input", function (event) {
        event.preventDefault();

        const search = $(this).val();
        if (search) {
            $.get(
                `http://127.0.0.1:8000/billing/invoice/search/${search}`,
                function (data) {
                    $("#invoice_result").html(data);
                }
            );
        } else {
            $.get(
                `http://127.0.0.1:8000/billing/invoice/search/all`,
                function (data) {
                    console.log(data);
                    $("#invoice_result").html(data);
                }
            );
        }
    });
});
