$(document).ready(function () {

    const BLOCK_STATUS_ACTIVE = 1;
    const BLOCK_STATUS_NOT_ACTIVE = 2;
    const BLOCK_STATUS_DRAFT = 3;

    // Proccess commission
    calculateCommission();

    // Process Cost Value
    $("body").on("keyup change", "#cost", function () {
        calculateCommission();
    });

    // Process Percent Value
    $("body").on("keyup change", "#commission", function () {
        calculateCommission();
    });

    // Process Commission Value
    $("body").on("keyup change", "#commission_value", function () {
        calculateCommissionInverse();
    });

    $("body").on('change', '#status', function () {
        let newValue = $(this).val();
        if (newValue !== BLOCK_STATUS_ACTIVE) {
            clearAds();
        }
    });

    $('body').on('keyup change', '#cost', function () {
        applyDeposit();
    })

    $('#blocks_contact_label_url').on('click', function () {
        let url = $('#contact').val();
        if (url.length >= 5) {
            window.open(url, '_blank');
        }
    })

    $("#building_name").autocomplete({
        minLength: 3,
        source: function (request, response) {
            $.ajax({
                data: {
                    buildings_name: $("#building_name").val(),
                },
                dataType: "json",
                type: 'POST',
                url: "/api/building/autocomplete/name",
                headers: {
                    'X-CSRF-TOKEN': window._token
                },
                success: function (data) {
                    response(data.items);
                }
            });
        },
        select: function (event, ui) {
            var result = ui.item.address + ' (' + +')';
            if (ui.item.name !== null) {
                result += ' (' + ui.item.name + ')';
            }
            $("#building_name").val(result);
            $("#building_id").val(ui.item.id);
            return false;
        },
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        var result = item.address;
        if (item.name !== null) {
            result += ' (' + item.name + ')';
        }

        return $("<li></li>")
            .append('<div class="text-nowrap">' + result + '</div>')
            .appendTo(ul);
    };

});

function applyDeposit() {
    var depositSelector = $('#deposit');
    var costSelector = $('#cost');
    var depositValue = depositSelector.val();
    if (depositValue && !window.depositChangeStatus) {
        return false;
    }
    //если залог не был усановлен, то меняем до сохранения.
    window.depositChangeStatus = true;
    depositSelector.val(costSelector.val());

    return true;
}

function clearAds() {
    $('#bet').val(null)
    $('#cian').val(null).change();
    $('#avito_promo').val(null).change();
}

function calculateCommission() {
    // Init values
    var cost = parseInt($("#cost").val());
    var commission = parseFloat($("#commission").val());

    // Calculate commission
    if (cost > 0 & commission > 0) {
        var commissionVvalue = parseInt((cost / 100) * commission);
        $("#commission_value").val(commissionVvalue);
    }
}

function calculateCommissionInverse(el) {
    // Init values
    var cost = parseInt($("#cost").val());
    var commissionValue = parseInt($("#commission_value").val());

    // Calculate commission
    if (cost > 0 & commissionValue > 0) {
        var commission = parseFloat(commissionValue / (cost / 100)).toFixed(2);
        $("#commission").val(commission);
    }
}
