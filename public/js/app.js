const select2Options = {
    width: "100%",
    language: "ru-RU"
}

function alertNotify(message, type, subtitle = '', time = 5000) {
    const html = `<div class="toast bg-${type} fade show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <strong class="mr-auto">${type}</strong>
                        <small>${subtitle}</small>
                        <button data-dismiss="toast" type="button" class="ml-2 mb-1 close" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="toast-body">${message}</div>
                  </div>`;
    $('.toast').remove();
    $('#toastsContainerTopRight').prepend(html);
    $('.toast').fadeOut(time);
}

$(document).ready(function () {
    // select2 init
    $('.select2').select2({
        width: '100%'
    });

    // Set Token
    window._token = $('meta[name="csrf-token"]').attr('content');

    // NullableToggle handler
    $('.js-nullable-toggle-btn').click(function () {
        const input = $(this).closest('.btn-group').find('input[type="hidden"]');
        const newVal = $(this).data('value').toString();

        input.val(newVal === input.val() ? '' : newVal);

        $(this).siblings('.js-nullable-toggle-btn').removeClass('active');
        $(this).toggleClass('active')
    })

    // Global search
    $(".form-control-navbar").autocomplete({
        minLength: 1,
        source: function (request, response) {
            $.ajax({
                data: {
                    query: $(".form-control-navbar").val(),
                },
                dataType: "json",
                type: 'POST',
                url: "/search",
                headers: {
                    'X-CSRF-TOKEN': window._token
                },
                success: function (data) {
                    response(data.items);
                }
            });
        },
        select: function (event, ui) {
        },
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        if (item.id) {
            return $("<li></li>")
                .append('<div class="text-nowrap"><a href="/block/' + item.id + '/edit" class="d-block">#' + item.id + '</a></div>')
                .appendTo(ul);
        } else {
            const name = item.name;
            if (name != '') {
                item.name = ' (' + item.name + ')';
            }
            return $("<li></li>")
                .append('<div class="text-nowrap"><a href="/block?address=' + item.address + '" class="d-block">' + item.address + item.name + '</a></div>')
                .appendTo(ul);
        }
    };

});
