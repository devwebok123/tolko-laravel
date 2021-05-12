@extends('adminlte::page')

@section('content')
    <?php
    /** @var App\Models\Building $building */
    ?>
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.building.title_singular') }}
        </div>

        <div class="card-body">
            @include('building._form', [
                'action' => route("building.update", [$building->id]),
                'method' => 'PUT',
                'building' => $building,
            ])
        </div>
    </div>
@endsection
