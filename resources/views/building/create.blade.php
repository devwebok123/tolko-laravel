@extends('adminlte::page')

@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.building.title_singular') }}
        </div>

        <div class="card-body">
            @include('building._form', [
                'action' => route("building.store"),
                'method' => 'POST',
                'building' => new App\Models\Building,
            ])
        </div>
    </div>
@endsection
