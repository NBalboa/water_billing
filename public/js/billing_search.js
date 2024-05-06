$(document).ready(function () {
    $("#billing_search").on("input", function (event) {
        event.preventDefault();
        const search = $(this).val();

        // $.get(`http://127.0.0.1:8000/billing/search/${search}`, function());

        if (search) {
            $.get(
                `http://127.0.0.1:8000/billing/search/${search}`,
                function (data) {
                    $("#billing_result").html(data);
                }
            );
        } else {
            $.get(`http://127.0.0.1:8000/billing/search/all`, function (data) {
                $("#billing_result").html(data);
            });
        }
    });
});
