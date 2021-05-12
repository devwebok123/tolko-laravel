$(function () {
    const inputAddress = $("#address");

    inputAddress.autocomplete({
        minLength: 2,
        source: function (request, response) {
            $.ajax({
                data: {q: $("#address").val()},
                dataType: "json",
                type: 'POST',
                url: '/api/building/address-suggest',
                headers: {
                    'X-CSRF-TOKEN': window._token
                },
                success: function (data) {
                    response($.map(data, function (obj) {
                        return {
                            label: obj,
                            value: obj,
                        };
                    }));
                }
            });

        },
        select: function (event, ui) {
            const wrapAddress = inputAddress.closest('.js-group');
            const errorAddress = wrapAddress.find('.help-block');
            let data = {
                q: ui.item.label,
            };
            if (window.building_id !== "") {
                data.building_id = parseInt(window.building_id);
            }

            $.ajax({
                url: '/api/building/address-info',
                data: data,
                dataType: "json",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': window._token
                },
                success: function (data) {
                    wrapAddress.removeClass('has-error');
                    errorAddress.hide();
                    $('#address').val(ui.item.label);

                    if (data.region_id !== null) {
                        $('#region_id').val(data.region_id).select2(select2Options);
                    }
                    if (data.metro_distance !== null) {
                        $('#metro_distance').val(data.metro_distance).trigger('change');
                    }
                    if (data.metro_id !== null) {
                        $('#metro_id').val(data.metro_id).select2(select2Options);
                    }
                    if (data.mkad_distance !== null) {
                        $('#mkad_distance').val(data.mkad_distance);
                    }
                    if (data.lon !== null) {
                        $('#lon').val(data.lon);
                    }
                    if (data.lat !== null) {
                        $('#lat').val(data.lat);
                    }
                    if (data.address_street !== null) {
                        $('#address_street').val(data.address_street);
                    }
                    $('#metro_id_2').val(data.metro_id_2 !== null ? data.metro_id_2 : '');
                    $('#metro_id_3').val(data.metro_id_3 !== null ? data.metro_id_3 : '');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status === 422) {
                        var errors = $.parseJSON(jqXHR.responseText).errors;
                        if (typeof errors.q === 'object') {
                            wrapAddress.addClass('has-error');
                            errorAddress.html(errors.q[0]).show();
                        }
                    }
                }

            });

            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li></li>")
            .append("<a>" + item.label + "</a>")
            .appendTo(ul);
    };

    // рассчет времени и способа передвижения до метро из расстояния
    $('.js-group-metro_distance').on('change paste keyup', '#metro_distance', function (e) {
        const distance = e.target.value;
        const move_type = distance > 2500 ? 2 : 1; // 2=drive 1=go
        const time = distance / (move_type === 1 ? 100 : 333);

        $('#metro_time').val(Math.ceil(time));
        $('#metro_time_type').val(move_type);
    });

});
