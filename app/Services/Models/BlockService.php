<?php


namespace App\Services\Models;

use App\Http\Requests\Block\BlockSearchRequest;
use App\Models\Block;
use App\Models\Metro;
use App\Models\Region;
use App\Services\Form\Builder;
use App\Services\Form\Form;

class BlockService
{
    /** @var MetroService $metroService */
    protected $metroService;

    public function __construct(MetroService $service)
    {
        $this->metroService = $service;
    }

    public function buildSearchForm(BlockSearchRequest $searchRequest): Builder
    {
        return (new Builder())->create([
            'method' => 'GET',
            'model' => $searchRequest,
            'modelAlias' => 'block',
            'id' => 'blocksFormFilters',
            'groupClass' => 'form-group',
            'btnGroupClass' => 'form-group',
            'attributes' => [
                'data-before-submit' => 'beforeSubmitFilterForm',
            ]
        ], function (Form $form) {

            $metros = $this->metroService->getWithBlocks();
            $toggleOptions = [1 => __('global.yes'), 0 => __('global.no'), '' => __('global.no_matter')];

            $form->wrapper(['class' => 'form-row']);

            $form->rangeFromTo('input', 'living_area', ['groupClass' => 'col-md-3']);

            $form->rangeFromTo('input', 'kitchen_area', ['groupClass' => 'form-group col-md-3']);

            $form->select(
                'rooms_type',
                Block::optionsLang(Block::ROOM_TYPES, 'cruds.block.fields.rooms_type_options'),
                [
                    'groupClass' => 'form-group col-md-3',
                ]
            );

            $form->select('region', Region::options(false), [
                'addClass' => 'select2',
                'groupClass' => 'col-md-3',
            ]);
            $form->select('metro', Metro::collectionOptions($metros), [
                'addClass' => 'select2',
                'groupClass' => 'col-md-3',
            ]);
            $form->number('metro_time', [
                'groupClass' => 'col-md-3',
            ]);
            $form->select(
                'living_conds',
                Block::optionsLang(Block::LIVING_CONDS, 'cruds.block.fields.living_conds_options'),
                [
                    'groupClass' => 'col-md-3',
                ]
            );

            $form->toggle(
                'type',
                array_merge(
                    [0 => __('global.no_matter')],
                    Block::optionsLang(Block::TYPES, 'cruds.block.fields.type_options')
                ),
                [
                    'groupClass' => 'form-group col-md-3 label-block',
                    'default' => 0,
                ]
            );

            $form->select(
                'balcony',
                Block::optionsLang(
                    Block::BALCONIES,
                    'cruds.block.fields.balcony_options'
                ),
                [
                    'groupClass' => 'form-group col-md-3 label-block',
                    'multiple' => 'multiple',
                    'empty' => false,
                ]
            );

            $form->select(
                'separate_wc_count',
                Block::optionsLang(Block::WC_COUNTS, 'cruds.block.fields.wc_count_options'),
                [
                    'groupClass' => 'form-group col-md-3 label-block',
                    'multiple' => 'multiple',
                    'empty' => false,
                ]
            );

            $form->select(
                'combined_wc_count',
                Block::optionsLang(Block::WC_COUNTS, 'cruds.block.fields.wc_count_options'),
                [
                    'groupClass' => 'form-group col-md-3 label-block',
                    'multiple' => 'multiple',
                    'empty' => false,
                ]
            );

            $form->select(
                'renovation',
                Block::optionsLang(Block::RENOVATIONS, 'cruds.block.fields.renovation_options'),
                [
                    'groupClass' => 'form-group col-md-3',
                ]
            );

            $form->select(
                'filling',
                Block::optionsLang(Block::FILLINGS, 'cruds.block.fields.filling_options'),
                [
                    'multiple' => 'multiple',
                    'empty' => false,
                    'groupClass' => 'form-group col-md-3 label-block',
                ]
            );

            $form->select(
                'shower_bath',
                Block::optionsLang(Block::SHOWER_BATHS, 'cruds.block.fields.shower_bath_options'),
                [
                    'multiple' => 'multiple',
                    'empty' => false,
                    'groupClass' => 'form-group col-md-3 label-block',
                ]
            );

            $form->select(
                'windowsInOut',
                Block::optionsLang(Block::WINDOWS, 'cruds.block.fields.windowsInOut_options'),
                [
                    'groupClass' => 'form-group col-md-3',
                ]
            );

            $form->rangeFromTo('input', 'ceiling', ['groupClass' => 'form-group col-md-3']);

            $form->input('text', 'create_date_from', [
                'addClass' => 'form-group col-md-12 datepicker',
            ]);
            $form->input('text', 'create_date_to', [
                'addClass' => 'form-group col-md-12 datepicker',
            ]);

            $form->select('cian', $toggleOptions, [
                'groupClass' => 'form-group col-md-3'
            ]);

            $form->button('submit', [
                'name' => 'submit',
                'type' => 'submit',
                'groupClass' => 'col-sm-12',
                'class' => 'btn-danger',
                'text' => __('global.search'),
            ]);
        });
    }
}
