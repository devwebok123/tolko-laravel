<?php
/**
 * @var \Illuminate\Database\Eloquent\Collection $regions
 * @var \App\DataObjects\Block\BlockIndexViewOptions $options
 * @var \App\Services\Form\Builder $form
 */

use App\Models\Block;


?>

@extends('adminlte::page')

@section('content')

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("block.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.block.title_singular') }}
            </a>
        </div>
    </div>
    @if($options->hasSearchForm())
        <div class="card">

            <div class="card-header">
                <h5 class="float-sm-left text-uppercase"><a
                        data-toggle="collapse"
                        href="#formCollapse"
                        role="button"
                        aria-expanded="false"
                        aria-controls="formCollapse">Поиск</a></h5>
            </div>

            <div id="formCollapse" class="card-body collapse">
                <?= $form ?>
            </div>

        </div>
    @endif


    <div class="card">
        <div class="card-header">
            {{ trans('cruds.block.title') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            @if($options->hasMassMarketingForm())
                @include('block.index_form_mass_marketing')
            @endif
            <table class="table table-bordered table-striped table-hover dataTable agrid" id="blocks-grid">

            </table>

        </div>

    </div>

    <div id="toastsContainerTopRight" class="toasts-top-right fixed"></div>
@endsection

@push('js')

    <script>

        function initDatepicker() {
            /**  START DATEPICKER **/
            $('.datepicker').datepicker({
                dateFormat: 'yy-mm-dd',
            })
            $(".datepicker").attr("autocomplete", "off");

            $(".datepicker").on("click", function () {

                let html = '<button class="datepicker-fast-button btn btn-info" data-day="today">Сегодня</button>' +
                    '<button class="datepicker-fast-button btn btn-info" data-day="yesterday">Вчера</button>' +
                    '<button class="datepicker-fast-button btn btn-info" data-day="week">Неделя</button>' +
                    '<button class="datepicker-fast-button btn btn-info" data-day="month">Месяц</button>';
                $("#ui-datepicker-div").append(html);

                $('.datepicker-fast-button').on('click', function () {
                    let value = $(this).attr('data-day')

                    var dateFrom = new Date();
                    var dateTo = new Date();
                    switch (value) {
                        case 'yesterday': {
                            dateFrom = dateTo = new Date(Date.now() - 1000 * 60 * 60 * 24);
                            break;
                        }
                        case 'week': {
                            dateFrom = new Date(Date.now() - 7000 * 60 * 60 * 24);
                            break;
                        }
                        case 'month': {
                            dateFrom = new Date(Date.now() - 30000 * 60 * 60 * 24);
                            break;
                        }
                    }
                    var dayFrom = dateFrom.getDate();
                    var dayTo = dateTo.getDate();
                    if (dayFrom < 10) {
                        dayFrom = '0' + dateFrom.getDate().toString();
                    }
                    if (dayTo < 10) {
                        dayTo = '0' + dateTo.getDate().toString();
                    }
                    $('#create_date_from').val(dateFrom.getFullYear() + '-' + (dateFrom.getMonth() + 1) + '-' + dayFrom);
                    $('#create_date_to').val(dateTo.getFullYear() + '-' + (dateTo.getMonth() + 1) + '-' + dayTo);
                    $('.datepicker').datepicker('hide');
                })
            });
        }


        /**  END DATEPICKER **/

        $('body').on('click', '.js-modal-photo', function (e) {
            e.preventDefault();
            $("#modalPhotos").modal("show");
        });

        $('body').on('click', '.js-modal-plan', function (e) {
            e.preventDefault();
            $("#modalPlans").modal("show");
        });

        $('body').on('click', '.js-btn-deactivate-listing', function (e) {
            e.preventDefault();
            var self = $(this);
            $.ajax({
                url: '/api/block/' + self.data('id') + '/deactivate',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': window._token
                }
            })
                .done(function (response) {
                    self.addClass('d-none');
                })
        })

        $('body').on('click', '.js-btn-activate-listing', function (e) {
            e.preventDefault();
            var self = $(this);
            $.ajax({
                url: '/api/block/' + self.data('id') + '/activate',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': window._token
                }
            })
                .done(function (response) {
                    self.closest('tr').find('.js-btn-deactivate-listing').removeClass('d-none');
                })
        })

        function bulkDelete(ids, callback) {
            $.ajax({
                url: '{{ route("block.mass-destroy") }}',
                type: 'DELETE',
                data: {
                    ids: ids,
                },
                headers: {
                    'X-CSRF-TOKEN': window._token
                }
            })
                .done(function (response) {
                    alertNotify('{{__('cruds.block.operations.deleted')}}', 'success')
                    callback();
                })
        }

        $(function () {

            var multipleSelectOptions = {
                includeSelectAllOption: true,
                allSelectedText: 'Выбрано все',
                selectAllText: 'Выбрать все',
                nSelectedText: 'Выбрано',
                nonSelectedText: 'Не выбрано',
                enableFiltering: false,
                maxHeight: 400,
                dropRight: true,
                numberDisplayed: 1,
            };

            $('.bootstrap-select').multiselect(multipleSelectOptions);

            $('#blocks-grid').aGrid({
                opacity: '0.9',
                filterForm: '#blocksFormFilters',
                events: {
                    beforeSubmitFilterForm: function (opts, form) {
                    },
                    afterRender: function (data) {
                        initDatepicker();
                    }
                },
                url: '{{ route('blocks.index') }}@if($options->onlyDrafts())?status={{\App\Models\Block::STATUS_DRAFT}}@endif',
                notResultsEmpty: false,
                columns: [
                    {
                        name: 'id',
                        label: '<?= __('cruds.block.fields.id') ?>',
                        render: function (value) {
                            return '<a href="/block/' + value.id + '/">' + value.id + '</a>'
                        },
                    },
                    {
                        name: 'buildings.address',
                        label: '<?= __('cruds.building.fields.address') ?>',
                    },
                    {name: 'buildings.name', label: '<?= __('cruds.building.fields.name') ?>'},
                    {
                        name: 'floor',
                        label: '<?= __('cruds.block.fields.floor') ?>/<?= __('cruds.building.fields.floors') ?>',
                        render: function (item) {
                            return item['floor'] + '/' + item['buildings.floors'];
                        }
                    },
                    {name: 'rooms', label: '<?= __('cruds.block.fields.rooms') ?>'},
                    {name: 'area', label: '<?= __('cruds.block.fields.short_area') ?>'},
                    {
                        name: 'status',
                        label: '{{__('cruds.block.fields.status')}}',
                        render: function (item) {
                            const STATUS_ACTIVE = {{\App\Models\Block::STATUS_ACTIVE}};
                            const ACTIVE_DESCRIPTION = '{{__('cruds.block.fields.status_options.active')}}';
                            const NOT_ACTIVE_DESCRIPTION = '{{__('cruds.block.fields.status_options.not_active')}}';
                            if (item.status === STATUS_ACTIVE) {
                                return ACTIVE_DESCRIPTION;
                            }
                            return NOT_ACTIVE_DESCRIPTION;

                        }
                    },
                    {
                        name: 'cost',
                        label: '<?= __('cruds.block.fields.cost') ?>',
                        render: function (item) {
                            return new Intl.NumberFormat('ru-RU').format(parseInt(item.cost))
                        }
                    },
                    {name: 'commission_type', label: '<?= __('cruds.block.fields.commission_type') ?>'},
                        @if($options->onlyDrafts())
                    {
                        name: 'created_at',
                        label: '{{trans('cruds.block.fields.created_at')}}',
                        render: function (item) {
                            var date = new Date(item.created_at);
                            return date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate() +
                                ' ' + date.getHours() + ':' + date.getMinutes();
                        }
                    },
                        @endif
                    {
                        name: 'actions',
                        label: '<?= __('global.actions') ?>',
                        render: function (item) {
                            var edit = '<div><a href="{{env('APP_URL')}}/block/' + item['id'] + '/edit">' +
                                '<button type="button" class="btn btn-info btn-xs">' +
                                '<i class="fas fa-edit fa-fw"></i></button></a></div>';
                            var contact = '';
                            if (item.contact) {
                                contact = '<div><a href="' + item.contact + '" target="_blank">' +
                                    '<button type="button" class="btn btn-success btn-xs">' +
                                    'С</button></a></div>';
                            }

                            return edit + contact;
                        },
                    },
                ],
                perPage: 20,
                sort: {
                    attr: 'blocks.updated_at',
                    dir: 'desc'
                },

                @if($options->getAddRowInfo())
                additionRow: {
                    type: 'ajax',
                    url: function (row) {
                        return '/api/block/' + row['id'] + '/addt-row-info';
                    }
                },
                @endif
                selectorRow: true,

                theadPanelCols: {
                    pager: 'col-sm-8',
                    actions: 'col-sm-4',
                    filter: 'col-sm-3'
                },

                // Filter Panel
                filterPanel: {},

                bulkForms: ['#mass-marketing-from'],

                // Filters in columns
                columnFilterPanel: [
                    {
                        name: 'id',
                        id: 'idFilterInput',
                        placeholder: 'ID',
                        autocomplete: {
                            source: '/api/block/autocomplete/id',
                            length: 1,
                            key: 'id',
                        },
                    },
                    {
                        name: 'buildings.address',
                        id: 'addressFilterInput',
                        placeholder: 'Адрес',
                        @if(!empty($searchAddress))
                        value: '{{$searchAddress}}',
                        @endif
                        length: 3,
                    },
                    {
                        name: 'buildings.name',
                        id: 'nameFilterInput',
                        placeholder: 'Название',
                        autocomplete: {
                            source: '/api/building/autocomplete/name',
                            length: 3,
                            key: 'name',
                        },
                    },
                    {
                        name: 'floor',
                        id: 'floorFilterInput',
                        placeholder: 'Этаж',
                        type: 'number',
                        step: 1,
                    },
                    {
                        name: 'rooms',
                        id: 'roomsFilterInput',
                        input: 'select',
                        source: @json(Block::optionsLang(Block::ROOMS, 'cruds.block.fields.rooms_options')),
                    },
                    {
                        name: 'status',
                        id: 'statusFilterInput',
                        input: 'select',
                        source: @json(Block::optionsLang(
                                        [1 =>'active', 2 =>'not_active'],
                                        'cruds.block.fields.status_options')),
                        value: 1,
                    },
                    {
                        name: 'cost',
                        id: 'costFilterInput',
                        input: 'range',
                        type: 'number',
                        step: 1,
                    },
                    {
                        name: 'commission_type',
                        id: 'commissionTypeFilterInput',
                        input: 'select',
                        source: <?= json_encode(Block::optionsLang(Block::COMMISSION_TYPES, 'cruds.block.fields.commission_type_options')) ?>,
                    },
                        @if($options->onlyDrafts())
                    {
                        name: 'created_at',
                        type: 'text',
                        value: null,
                        id: 'createdFilterInput',
                        placeholder: '',
                        addClass: 'datepicker',
                    }
                    @endif
                ],
                panelActions: [
                    {
                        type: 'button',
                        class: 'btn-danger',
                        confirm: 'Вы действительно хотите удалить?',
                        label: 'Удалить',
                        action: 'bulkDelete',
                    }
                ]
            });
        });

        $(document).ready(function () {
            initDatepicker();
        })

        // alert after create\update\block
        @if(session()->has('block.operation_status') && in_array(($status = session()->get('block.operation_status')), \App\Http\Controllers\Controller::SUCCESS_STATUSES))
        alertNotify("{{__('cruds.block.operations.'.$status)}}", 'success')
        @endif
    </script>
@endpush
@push('js')
    <script src="{{asset('js/bootstrap-multiselect.min.js')}}"></script>
    <script src="{{env('APP_URL')}}/js/agrid.js"></script>
@endpush
@push('css')
    <link href="{{asset('css/bootstrap-multiselect.css')}}" rel="stylesheet"/>
@endpush
