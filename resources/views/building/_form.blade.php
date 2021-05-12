<?php

use App\Models\Building;
use App\Models\Metro;
use App\Models\Region;
use App\Services\Form\Form;

/** @var Building $building */
/** @var string $method */
/** @var string $action */

echo FormBuilder::create([
    'method'     => $method,
    'action'     => $action,
    'model'      => $building,
    'name'       => 'bForm',
    'groupClass' => 'form-group col-sm-6',
    'btnGroupClass' => 'js-group col-sm-6',
], function (Form $form) use ($building) {

    $form->hidden('metro_id_2');
    $form->hidden('metro_id_3');

    $form->wrapper(['class' => 'form-row']);

    $form->addCard('location', [
        'title' => 'Локация',
        'class' => 'col-sm-12',
    ]);

    $form->addCard('params', [
        'title' => 'Характеристики',
        'class' => 'col-sm-12',
    ]);

    $form->addCard('addt_info', [
        'template' => 'collspace',
        'title' => 'Дополнительная информация',
        'class' => 'col-sm-12',
    ]);

    //location
    $form->text('address', [
        'groupClass' => 'col-sm-12',
        'card' => 'location',
    ]);
    $form->text('name', [
        'groupClass' => 'col-sm-6',
        'card' => 'location',
    ]);
    $form->select('metro_id', Metro::options(), [
        'addClass' => 'select2',
        'groupClass' => 'col-sm-6',
        'card' => 'location',
    ]);
    $form->select('metro_time_type', Building::optionsLang(Building::TIME_TYPES, 'cruds.building.fields.metro_time_type_options'), [
        'card' => 'location',
        'groupClass' => 'col-sm-4',
    ]);
    $form->number('metro_time', [
        'groupClass' => 'col-sm-4',
        'card' => 'location',
    ]);
    $form->select('region_id', Region::options(false), [
        'card' => 'location',
        'addClass' => 'select2',
        'groupClass' => 'col-sm-4',
    ]);

    $form->textarea('description', [
        'card' => 'location',
        'row' => 2, 'groupClass' =>
        'col-sm-12 form-group'
    ]);

    //params
    $form->select('type', Building::optionsLang(Building::TYPES, 'cruds.building.fields.type_options'), [
        'groupClass' => 'col-sm-6',
        'card' => 'params',
    ]);
    $form->select('class', Building::CLASSES, [
        'groupClass' => 'col-sm-6',
        'card' => 'params',
    ]);
    $form->number('floors', [
        'groupClass' => 'col-sm-6',
        'card' => 'params',
    ]);

    $form->number('year_construction', [
        'card' => 'params',
        'groupClass' => 'form-group col-sm-6'
    ]);

    $form->select('parking_type', Building::optionsLang(Building::PARKING_TYPES, 'cruds.building.fields.parking_type_options'), [
        'card' => 'params',
        'groupClass' => 'form-group col-sm-3',
    ]);

    //add info
    $form->text('lon', [
        'card' => 'addt_info',
        'groupClass' => 'col-sm-3',
    ]);
    $form->text('lat', [
        'card' => 'addt_info',
        'groupClass' => 'col-sm-3',
    ]);
    $form->number('metro_distance', [
        'card' => 'addt_info',
        'groupClass' => 'col-sm-6',
    ]);

    $form->number('mkad_distance', [
        'card' => 'addt_info',
        'groupClass' => 'col-sm-6',
    ]);

    $form->buttons('submit', 'btn-info', __('global.save'));
});
?>
<br/>

@push('js')
    <script src="{{ asset('js/building.js') }}"></script>
    <script>
        window.building_id = '{{ $building->exists ? $building->id : "" }}';
    </script>
@endpush
