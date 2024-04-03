$(document).ready(function () {
    const waterRates = 20;

    $("#current").on("input", function () {
        const current = parseInt($(this).val());
        const previos = parseInt($("#previos").val());

        if (!isNaN(current - previos)) {
            const total_consumption = current - previos;
            $("#total_consumption").val(total_consumption);
            $("#price").val((total_consumption * waterRates).toFixed(2));
            $("#total").val((total_consumption * waterRates).toFixed(2));
        }

        if (isNaN(current)) {
            $("#total_consumption").val("");
            $("#price").val("");
            $("#total").val("");
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
