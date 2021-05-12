@extends('adminlte::page')

@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('cruds.notifications.title') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped table-hover dataTable agrid" id="notifications-grid">
            </table>

        </div>
    </div>

@endsection

@push('js')
    <script src="js/agrid.js"></script>
    <script>
        $(function () {
            var properties = {
                opacity: '0.9',
                events: {
                    beforeSubmitFilterForm: function (opts, form) {
                    },
                    afterRender: function (data) {
                        $('.resolve').on('click', function () {
                            resolveNotification($(this).data('id'))
                            $(this).remove();
                        })
                    }
                },
                url: '{{ route('notifications.index.api') }}',
                notResultsEmpty: false,
                columns: [
                    {name: 'id', label: 'ID'},
                    {
                        name: 'block_id',
                        label: '{{__('cruds.block.title_singular')}}',
                        render: function (item) {
                            console.log(item.block_id);
                            if (!item.block_id) {
                                return '';
                            }
                            return '<a target="_blank" href="/block/' + item.block_id + '">' + item.block_id + '</a>';
                        }
                    },
                    {name: 'text', label: '{{__('cruds.notifications.fields.text')}}'},
                    {name: 'notification_date', label: '{{__('cruds.notifications.fields.notification_date')}}'},
                    {
                        name: 'actions',
                        label: '<?= __('global.actions') ?>',
                        render: function (item) {
                            if (!item.is_resolved) {
                                return '<button data-id="' + item.id +
                                    '" class="resolve btn btn-success">{{__('global.resolve')}}</button>';
                            }
                            return '';
                        },
                    },
                ],
                theadPanelCols: {
                    pager: 'col-sm-7',
                    actions: 'col-sm-2',
                },
                queryParams: [],
            }
            $('#notifications-grid').aGrid(properties);

            function resolveNotification(id) {
                $.ajax({
                    url: '/api/notifications/' + id + '/resolve',
                    type: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': window._token
                    }
                }).done(function (response) {

                })
            }
        });
    </script>
@endpush
