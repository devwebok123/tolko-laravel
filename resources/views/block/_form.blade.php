<?php

use App\Models\Block;
use App\Services\Form\Form;

/** @var string $method */
/** @var string $action */
/** @var Block $block */

echo FormBuilder::create([
    'method' => $method,
    'action' => $action,
    'model' => $block,
    'name' => 'bForm',
    'groupClass' => 'form-group col-md-6',
    'btnGroupClass' => 'js-group col-md-6',
], function (Form $form) use ($block) {

    // Init Select/Toggle values
    $simpleToggle = [0 => __('global.no'), 1 => __('global.yes')];
    $typeOptions = Block::optionsLang(Block::TYPES, 'cruds.block.fields.type_options');
    $roomsOptions = Block::optionsLang(Block::ROOMS, 'cruds.block.fields.rooms_options');
    $roomsTypeOptions = Block::optionsLang(Block::ROOM_TYPES, 'cruds.block.fields.rooms_type_options');
    $balconyOptions = Block::optionsLang(Block::BALCONIES, 'cruds.block.fields.balcony_options');
    $windowsInOutOptions = Block::optionsLang(Block::WINDOWS, 'cruds.block.fields.windowsInOut_options');
    $WcCountOptions = Block::optionsLang(Block::WC_COUNTS, 'cruds.block.fields.wc_count_options');
    $renovationOptions = Block::optionsLang(Block::RENOVATIONS, 'cruds.block.fields.renovation_options');
    $fillingOptions = Block::optionsLang(Block::FILLINGS, 'cruds.block.fields.filling_options');
    $showerBathOptions = Block::optionsLang(Block::SHOWER_BATHS, 'cruds.block.fields.shower_bath_options');
    $livingCondsOptions = Block::optionsLang(Block::LIVING_CONDS, 'cruds.block.fields.living_conds_options');
    $commissionTypeOptions = Block::optionsLang(Block::COMMISSION_TYPES, 'cruds.block.fields.commission_type_options');
    $currencyOptions = Block::CURS;
    $includedOptions = Block::optionsLang(Block::INCLUDES, 'cruds.block.fields.included_options');
    $statusOptions = Block::optionsLang(Block::STATUSES, 'cruds.block.fields.status_options');

    $form->wrapper(['class' => 'form-row']);

    $form->addCard('object', [
        'title' => 'Объект',
        'class' => 'col-md-8',
    ]);

    $form->addCard('listing', [
        'title' => 'Листинг',
        'class' => 'col-md-4',
    ]);

    $form->addCard('market', [
        'title' => 'Маркетинг',
        'class' => 'col-md-12',
    ]);

    /* -------------------------------------------------------------------------------------- */

    // building_id
    $form->text('building_name', [
        'groupClass' => 'form-group col-md-12',
        'append' => '<a href="' . route('building.create') . '" class="btn btn-success create-building-btn">Создать новое здание</a>',
        'colFieldClass' => 'col-md-8',
        'colAppendClass' => 'col-md-4',
        'card' => 'object',
    ]);

    $form->hidden('building_id');

    $form->text('contact', [
        'groupClass' => 'form-group col-md-12',
        'card' => 'object',
        'label' => [
            'title' => '<a href="#" onclick="return false;" id="blocks_contact_label_url" target="_blank">' .
                trans('cruds.block.fields.contact') .
                '</a>'
        ]
    ]);

    /* -------------------------------------------------------------------------------------- */

    // rooms
    $form->select('rooms', $roomsOptions, [
        'addClass' => 'select2',
        'default' => '',
        'groupClass' => 'form-group col-md-6',
        'card' => 'object',
    ]);

    // rooms type
    $form->select('rooms_type', $roomsTypeOptions, [
        'addClass' => 'select2',
        'default' => '',
        'groupClass' => 'form-group col-md-6',
        'card' => 'object',
    ]);

    /* -------------------------------------------------------------------------------------- */

    $form->number('floor', [
        'groupClass' => 'form-group col-md-4',
        'card' => 'object',
    ]);

    $form->number('flat_number', [
        'groupClass' => 'form-group col-md-4',
        'card' => 'object',
    ]);

    $form->number('area', [
        'groupClass' => 'form-group col-md-4',
        'step' => '0.01',
        'card' => 'object',
    ]);

    /* -------------------------------------------------------------------------------------- */

    // type
    $form->toggle('type', $typeOptions, [
        'groupClass' => 'form-group col-md-4 label-block',
        'default' => 1,
        'card' => 'object',
    ]);

    $form->number('living_area', [
        'groupClass' => 'form-group col-md-4',
        'step' => '0.01',
        'card' => 'object',
    ]);

    $form->number('kitchen_area', [
        'groupClass' => 'form-group col-md-4',
        'step' => '0.01',
        'card' => 'object',
    ]);

    /* -------------------------------------------------------------------------------------- */

    // balcony
    $form->select('balcony', $balconyOptions, [
        'addClass' => 'select2',
        'default' => '',
        'groupClass' => 'form-group col-md-4',
        'card' => 'object',
    ]);

    // separate_wc_count
    $form->select('separate_wc_count', $WcCountOptions, [
        'addClass' => 'select2',
        'default' => '',
        'groupClass' => 'form-group col-md-4',
        'card' => 'object',
    ]);

    // combined_wc_count
    $form->select('combined_wc_count', $WcCountOptions, [
        'addClass' => 'select2',
        'default' => '',
        'groupClass' => 'form-group col-md-4',
        'card' => 'object',
    ]);

    /* -------------------------------------------------------------------------------------- */

    // windowsInOut
    $form->select('windowsInOut', $windowsInOutOptions, [
        'addClass' => 'select2',
        'default' => '',
        'groupClass' => 'form-group col-md-4',
        'card' => 'object',
    ]);

    // combined_wc_count
    $form->select('renovation', $renovationOptions, [
        'addClass' => 'select2',
        'default' => '',
        'groupClass' => 'form-group col-md-4',
        'card' => 'object',
    ]);

    $form->number('tenant_count_limit', [
        'step' => 1,
        'groupClass' => 'form-group col-md-4',
        'card' => 'object',
    ]);

    /* -------------------------------------------------------------------------------------- */

    $form->select('filling', $fillingOptions, [
        'default' => '',
        'multiple' => true,
        'groupClass' => 'form-group col-md-4 label-block',
        'empty' => false,
        'card' => 'object',
    ]);

    $form->select('shower_bath', $showerBathOptions, [
        'default' => '',
        'multiple' => true,
        'groupClass' => 'form-group col-md-4 label-block',
        'empty' => false,
        'card' => 'object',
    ]);

    $form->select('living_conds', $livingCondsOptions, [
        'default' => '',
        'multiple' => true,
        'groupClass' => 'form-group col-md-4 label-block',
        'empty' => false,
        'card' => 'object',
    ]);

    /* -------------------------------------------------------------------------------------- */

    $form->text('video_url', [
        'groupClass' => 'form-group col-md-12',
        'card' => 'object',
    ]);

    /* -------------------------------------------------------------------------------------- */

    $form->number('cost', [
        'groupClass' => 'form-group col-md-6',
        'card' => 'listing',
    ]);

    $form->toggle('currency', $currencyOptions, [
        'groupClass' => 'form-group col-md-6 label-block',
        'value' => 1,
        'card' => 'listing',
    ]);

    $form->number('deposit', [
        'groupClass' => 'form-group col-md-6',
        'card' => 'listing',
    ]);


    $form->number('commission', [
        'groupClass' => 'form-group col-md-6',
        'card' => 'listing',
        'step' => '0.01',
        'default' => 50
    ]);

    $form->number('commission_value', [
        'groupClass' => 'form-group col-md-6',
        'card' => 'listing',
    ]);

    // type
    $form->toggle('commission_type', $commissionTypeOptions, [
        'groupClass' => 'form-group col-md-6 label-block',
        'default' => 1,
        'card' => 'listing',
    ]);

    $form->textarea('commission_comment', [
        'groupClass' => 'form-group col-md-12',
        'card' => 'listing',
    ]);

    $form->textarea('description', [
        'groupClass' => 'form-group col-md-12',
        'card' => 'listing',
    ]);

    $form->textarea('comment', [
        'groupClass' => 'form-group col-md-12',
        'card' => 'listing',
    ]);

    $form->text('cadastral_number', [
        'groupClass' => 'form-group col-md-12',
        'card' => 'listing',
    ]);

    $form->select('status', $statusOptions, [
        'groupClass' => 'form-group col-md-12 label-block',
        'value' => 1,
        'card' => 'listing',
    ]);

    $form->toggle('out_of_market', $simpleToggle, [
        'groupClass' => 'form-group col-md-6 label-block',
        'value' => 0,
        'card' => 'listing',
    ]);

    $form->toggle('contract_signed', $simpleToggle, [
        'groupClass' => 'form-group col-md-6 label-block',
        'value' => 0,
        'card' => 'listing',
    ]);

    $form->select('included', $includedOptions, [
        'default' => '',
        'multiple' => true,
        'groupClass' => 'form-group col-md-6 label-block',
        'empty' => false,
        'card' => 'listing',
    ]);

    $form->number('parking_cost', [
        'groupClass' => 'form-group col-md-6',
        'card' => 'listing',
    ]);

    $form->number('bargain', [
        'groupClass' => 'form-group col-md-6',
        'card' => 'listing',
    ]);

    /* Marketing */
    $form->select('cian', Block::CIAN_PROMOS, [
        'default' => '',
        'groupClass' => 'form-group col-md-3',
        'card' => 'market',
    ]);

    $form->number('bet', [
        'groupClass' => 'form-group col-md-3',
        'card' => 'market',
    ]);

    $form->text('ad_title', [
        'groupClass' => 'form-group col-md-6',
        'card' => 'market',
    ]);

    $form->select(
        'avito_promo',
        Block::optionsLang(Block::AVITO_PROMOS, 'cruds.block.fields.avito_promos'),
        [
            'default' => '',
            'groupClass' => 'form-group col-md-6',
            'card' => 'market'
        ]
    );

    $form->select(
        'yandex_promo',
        Block::optionsLang(Block::YANDEX_PROMOS, 'cruds.block.fields.yandex_promos'),
        [
            'default' => '',
            'groupClass' => 'form-group col-md-6',
            'card' => 'market'
        ]
    );

    $form->button('button', [
        'name' => 'submit',
        'type' => 'submit',
        'groupClass' => 'form-group col-md-12',
        'class' => 'btn-info',
        'text' => __('global.save'),
    ]);

});
?>
