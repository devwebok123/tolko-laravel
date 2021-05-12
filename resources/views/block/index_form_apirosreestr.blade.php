@if(!empty($block->flat_number) || !empty($block->cadastral_number))
    <div class="col-md-12 mt-3">
        <div class="card mb-0">
            <div class="card-header">
                <h5>{{ __('cruds.apiRosReestr.title') }}</h5></div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 apirosreestr-wraper">
                        @if(!$block->order)
                            <button class="btn btn-info apirosreestr-checking-button" type="button">
                                {{ __('cruds.apiRosReestr.checking_button') }}
                            </button>

                            <button class="btn btn-info apirosreestr-ordering-button d-none" type="button">
                                {{ __('cruds.apiRosReestr.ordering_button') }}
                            </button>

                            <span class="text-danger apirosreestr-checking-empty d-none ml-4">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ __('cruds.apiRosReestr.checking_empty') }}
                            </span>

                            <span class="text-success apirosreestr-checking-available d-none ml-4">
                                <i class="fas fa-check-circle"></i>
                                {{ __('cruds.apiRosReestr.checking_available') }}
                            </span>

                            <span class="text-danger apirosreestr-checking-error d-none ml-4"></span>

                            <select class="form-control apirosreestr-searching-result d-none col-md-6 mb-2"></select>

                            <button class="btn btn-info apirosreestr-searching-button d-none" type="button">
                                {{ __('cruds.apiRosReestr.searching_button') }}
                            </button>
                            <button class="btn btn-info apirosreestr-waiting-button d-none disabled" type="button">
                                {{__('cruds.apiRosReestr.waiting_button')}}
                            </button>
                        @else
                            @if($block->order->status === \App\Models\BlockOrder::STATUS_DOWNLOAD)
                                <a class="btn btn-info apirosreestr-download-button"
                                   download
                                   target="_blank"
                                   href="{{$block->order->url}}">{{__('cruds.apiRosReestr.download_button')}}
                                </a>
                            @else
                                <button class="btn btn-info apirosreestr-waiting-button disabled" type="button">
                                    {{__('cruds.apiRosReestr.waiting_button')}}
                                </button>
                            @endif

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@push('js')
    <script>
        // 7-api-rosreestr
        $('body').on('click', '.apirosreestr-checking-button', function (e) {
            //
            e.preventDefault();

            //
            var spinner = `<span class="spinner-grow spinner-grow-sm mr-2"></span>`;
            var checking_button = '{{ __('cruds.apiRosReestr.checking_button') }}';
            var checking_status = '{{ __('cruds.apiRosReestr.checking_status') }}';
            var ordering_button = '{{ __('cruds.apiRosReestr.ordering_button') }}';
            var ordering_status = '{{ __('cruds.apiRosReestr.ordering_status') }}';


            $('.apirosreestr-checking-button').attr('disabled', 'disabled');
            $('.apirosreestr-checking-spinner').removeClass('d-none');

            var cadastralNumber = '{{$block->cadastral_number ?: ''}}';
            var flatNumber = '{{$block->flat_number ?: ''}}';

            if (cadastralNumber != '') {
                //
                $('.apirosreestr-checking-button').html(spinner + checking_status);
                $('.apirosreestr-checking-empty').addClass('d-none');
                $('.apirosreestr-checking-error').addClass('d-none');

                $.ajax({
                    url: '{{  route('block.apirosreestr.objectinfofull', $block->id) }}',
                    type: 'POST',
                    data: {
                        query: cadastralNumber,
                    },
                    headers: {
                        'X-CSRF-TOKEN': window._token
                    }
                })
                    .done(function (response) {
                        if (response.available === true) {
                            // Hide Checking Button
                            $('.apirosreestr-checking-button').addClass('d-none');
                            // Show Ordering Button
                            $('.apirosreestr-ordering-button').removeClass('d-none');
                            // Sho message
                            $('.apirosreestr-checking-available').removeClass('d-none');
                        } else {
                            // Return Checking Button to Default state
                            $('.apirosreestr-checking-button').text(checking_button);
                            $('.apirosreestr-checking-button').removeAttr('disabled');
                            $('.apirosreestr-checking-spinner').addClass('d-none');
                            // Show message
                            $('.apirosreestr-checking-empty').removeClass('d-none');
                        }
                    });
            } else if (flatNumber != '') {
                //
                $('.apirosreestr-checking-button').html(spinner + ordering_status);
                $('.apirosreestr-checking-empty').addClass('d-none');

                //
                $.ajax({
                    url: '{{ route('block.apirosreestr.search', $block->id) }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': window._token
                    }
                })
                    .done(function (response) {
                        if (response.error.length > 0) {
                            // Return Checking Button to Default state
                            $('.apirosreestr-checking-button').text(checking_button);
                            $('.apirosreestr-checking-button').removeAttr('disabled');
                            $('.apirosreestr-checking-spinner').addClass('d-none');

                            $('.apirosreestr-checking-error').removeClass('d-none');
                            $('.apirosreestr-checking-error').text(response.error.mess);
                        } else if (response.objects.length > 0) {
                            // If One result
                            if (response.objects.length === 1) {
                                //
                                var cadastral = response.objects[0].CADNOMER
                                // Save Cadastral
                                saveCadastral(cadastral);
                                $('#cadastral_number').val(cadastral);
                                // Hide checking button
                                $('.apirosreestr-checking-button').addClass('d-none');
                                // Show ordering Button
                                $('.apirosreestr-ordering-button').removeClass('d-none');
                                $('.apirosreestr-checking-available').removeClass('d-none');
                            } else {
                                // Hide checking button
                                $('.apirosreestr-checking-button').addClass('d-none');
                                // Show list of addresses
                                $.each(response.objects, function (key, item) {
                                    $('.apirosreestr-searching-result')
                                        .append($('<option>', {value: item.CADNOMER})
                                            .text(item.ADDRESS));
                                });
                                $('.apirosreestr-searching-result').removeClass('d-none');
                                $('.apirosreestr-searching-button').removeClass('d-none');
                            }
                        } else {
                            $('.apirosreestr-checking-error').removeClass('d-none');
                            $('.apirosreestr-checking-error').text(response);
                        }

                    });
            }
        });

        function saveCadastral(cadastral) {
            $.ajax({
                url: '{{ route('block.apirosreestr.cadastral', $block->id) }}',
                type: 'POST',
                data: {
                    cadastral: cadastral,
                },
                headers: {
                    'X-CSRF-TOKEN': window._token
                }
            }).done(function (response) {

            });
        }

        // save order

        $('.apirosreestr-ordering-button').on('click', function () {
            $.ajax({
                url: '{{ route('block.apirosreestr.saveorder', $block->id) }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': window._token
                }
            }).done(function (response) {
                $('.apirosreestr-ordering-button').addClass('d-none');
                $('.apirosreestr-checking-available').addClass('d-none');
                $('.apirosreestr-waiting-button').removeClass('d-none');

            });
        })
    </script>
@endpush
