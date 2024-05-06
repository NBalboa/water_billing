$(document).ready(function () {
    $("#transaction_search").on("input", function (event) {
        event.preventDefault();

        const search = $(this).val();

        if (search) {
            $.get(
                `http://127.0.0.1:8000/transactions/search/${search}`,
                function (data) {
                    $("#transaction_result").html(data);
                }
            );
        } else {
            $.get(
                `http://127.0.0.1:8000/transactions/search/all`,
                function (data) {
                    $("#transaction_result").html(data);
                }
            );
        }
    });
});
