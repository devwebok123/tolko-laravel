@extends('adminlte::page')

@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.block.title_singular') }}
        </div>
        <div class="card-body">

            @include('block._form', [
                'action' => route("block.update", $block->id),
                'method' => 'PUT',
                'block' => $block,
            ])

            @include('block._photos')

            @include('block.index_form_apirosreestr')

        </div>
    </div>
    <div id="toastsContainerTopRight" class="toasts-top-right fixed"></div>
@endsection

@push('js')
    <script>

        $(function () {
            // MultiSelect
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
        });


    </script>
    <script>
        @if ($errors->any())
            let message = '{{__("cruds.block.operations.save_error")}}: <br>';
            @foreach ($errors->all() as $error)
                message += '-{{$error}}<br>'
            @endforeach
            alertNotify(message, 'danger')
        @endif
    </script>
@endpush
@push('js')
    <script src="{{asset('js/bootstrap-multiselect.min.js')}}"></script>
    <script src="{{asset('js/object-form.js')}}"></script>
@endpush
@push('css')
    <link href="{{asset('css/bootstrap-multiselect.css')}}" rel="stylesheet" />
@endpush
