$(document).ready(function () {
    $("#consumer_search").on("input", function (event) {
        const search = $(this).val();
        if (search) {
            $.get(
                `http://127.0.0.1:8000/consumer/search/${search}`,
                function (data) {
                    $("#consumer_result").html(data);
                }
            );
        }
    });
});
