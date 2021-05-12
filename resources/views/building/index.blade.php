@extends('adminlte::page')

@section('content')

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("building.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.building.title_singular') }}
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            {{ trans('cruds.building.title') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover dataTable agrid" id="buildings-grid"></table>
        </div>
    </div>

@endsection

@push('js')
    <script src="js/agrid.js"></script>
    <script>

        function bulkDelete(ids, callback) {
            $.ajax({
                url: '{{ route("building.mass-destroy") }}',
                type: 'DELETE',
                data: {
                    ids: ids,
                },
                headers: {
                    'X-CSRF-TOKEN': window._token
                }
            })
                .done(function (response) {
                    callback();
                })
        }

        $(function () {
            var submitFormTimer;

            var properties = {
                opacity: '0.9',
                events: {
                    beforeSubmitFilterForm: function (opts, form) {
                    },
                    afterRender: function (data) {
                    }
                },
                url: '{{ route('buildings.index') }}',
                notResultsEmpty: false,
                columns: [
                    {
                        name: 'address',
                        label: '<?= __('cruds.building.fields.address') ?>',
                        render: function (value) {
                            return '<a href="/building/' + value.id + '/edit">' + value.address + '</a>'
                        }
                    },
                    {name: 'name', label: '<?= __('cruds.building.fields.name') ?>'},
                    {name: 'region_name', label: '<?= __('cruds.building.fields.region_id') ?>'},
                    {name: 'blocks_count', label: '<?= __('cruds.building.fields.apartments_for_rent') ?>'},
                    {{--{name: 'metros.name', label: '<?= __('cruds.building.fields.metro_and_time') ?>'},--}}
                    {{--{name: 'buildings.release_date', label: '<?= __('cruds.building.fields.release_date') ?>'},--}}
                    {{--{name: 'rent_count', label: '<?= __('cruds.building.fields.rent_count') ?>'},--}}
                    {{--{name: 'sale_count', label: '<?= __('cruds.building.fields.sale_count') ?>'},--}}
                    {{--{name: 'inactive_count', label: '<?= __('cruds.building.fields.inactive_count') ?>'},--}}
                ],
                sort: {
                    attr: 'buildings.id',
                    dir: 'asc'
                },
                selectorRow: true,
                // filterForm: '#building-filter-form',
                theadPanelCols: {
                    pager: 'col-sm-7',
                    actions: 'col-sm-2',
                    filter: 'col-sm-3'
                },
                filterPanel: {
                    formId: 'building-filter-form',
                    inputs: [
                        {type: 'text', id: 'q', placeholder: '<?= __('cruds.buildingFilter.fields.q') ?>'}
                    ]
                },
                panelActions: [
                    {
                        type: 'button',
                        class: 'btn-danger',
                        confirm: 'Вы действительно хотите удалить?',
                        label: 'Удалить',
                        action: 'bulkDelete',
                    }
                ],
                queryParams: [],
            }

            $('body').on('keyup', '#q', function (e) {
                clearTimeout(submitFormTimer)
                submitFormTimer = setTimeout(function () {
                    var value = $('#q').val()
                    if (value.length >= 3) {
                        properties.queryParams[0] = 'query=' + value;
                        load();
                    }
                    if(value.length === 0){
                        delete properties.queryParams[0]
                        load();
                    }
                }, 500)
            })

            function load() {
                $('#buildings-grid').aGrid(properties);
            }
            load();
        });
    </script>
@endpush
