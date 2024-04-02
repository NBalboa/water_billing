$(document).ready(function () {
    const waterRates = 36.86;

    $("#current").on("input", function () {
        const current = parseInt($(this).val());
        const previos = parseInt($("#previos").val());

        // const source_charges = parseInt($("#source_charges").val());

        if (!isNaN(current - previos)) {
            const total_consumption = current - previos;
            $("#total_consumption").val(total_consumption);
            $("#price").val((total_consumption * waterRates).toFixed(2));

            $("#source_charges").removeAttr("disabled");
        }

        if (isNaN(current)) {
            $("#total_consumption").val("");
            $("#price").val("");
            $("#source_charges").val("");
            $("#total").val("");
            $("#source_charges").attr("disabled", "");
        }
    });

    $("#source_charges").on("input", function () {
        const source_charges = parseInt($(this).val());
        const water_bill = parseFloat($("#price").val());

        if (!isNaN(water_bill + source_charges)) {
            $("#total").val((water_bill + source_charges).toFixed(2));
        }

        if (isNaN(source_charges)) {
            $("#total").val("");
        }
    });
});
