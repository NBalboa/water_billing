$(document).ready(function () {
    function showAllProvinces(data) {
        let provinces = [];

        let region_ids = Object.keys(data);

        for (let i = 0; i < region_ids.length; i++) {
            if (data.hasOwnProperty(region_ids[i])) {
                let regions_data = data[region_ids[i]];
                if (regions_data.hasOwnProperty("province_list")) {
                    let provinces_data = regions_data.province_list;
                    let province_names = Object.keys(provinces_data);
                    province_names.forEach((name) => {
                        provinces.push(name);
                    });
                }
            }
        }
        provinces.forEach((provinces) => {
            $("#provinces").append(
                `<option value="${provinces}">${provinces}</option>`
            );
        });
    }

    function showAllMunicipalitiesByProvince(data) {
        // console.log(data);
        let municipalities = [];

        $("#provinces").on("change", function () {
            const province = $(this).val();
            $("#municipalities").val("");
            $("#barangays").val("");
            for (const key in data) {
                const regions = data[key];

                if (regions.province_list.hasOwnProperty(province)) {
                    const allMunicipality = Object.keys(
                        regions.province_list[province].municipality_list
                    );

                    municipalities = allMunicipality;
                }
            }
            municipalities.forEach((municipality) => {
                $("#municipalities").append(
                    `<option value="${municipality}">${municipality}</option>`
                );
            });
        });
    }

    function showAllBarangayByMunicipality(data) {
        let barangays = [];

        $("#municipalities").on("change", function () {
            const city = $(this).val();
            $("#barangays").val("");
            for (const key in data) {
                const regions = data[key];
                const provinces = regions.province_list;
                for (const province_key in provinces) {
                    const municipalities =
                        provinces[province_key].municipality_list;
                    if (municipalities.hasOwnProperty(city)) {
                        barangays = municipalities[city].barangay_list;
                    }
                }
            }

            barangays.forEach((barangay) => {
                $("#barangays").append(
                    `<option value="${barangay}">${barangay}</option>`
                );
            });
        });
    }

    $.getJSON(
        "https://raw.githubusercontent.com/flores-jacob/philippine-regions-provinces-cities-municipalities-barangays/3c993f5669bc7ca62d2c5740eb1733923e61eac2/philippine_provinces_cities_municipalities_and_barangays_2019v2.json",
        function (data) {
            showAllProvinces(data);
            showAllMunicipalitiesByProvince(data);
            showAllBarangayByMunicipality(data);
        }
    );
});
