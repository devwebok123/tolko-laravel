<?php

namespace App\Http\Requests\Block;

use App\Models\Block;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\Block\BlockCollection;

class BlockSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [

            'page' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'per_page' => [
                'nullable',
                'integer',
                'min:1',
                'max:100',
            ],

            'sort_dir' => [
                'nullable',
                'in:desc,asc',
            ],
            'sort_attr' => [
                'nullable',
                'in:' . implode(',', [
                    'id',
                    'buildings.address',
                    'buildings.name',
                    'floor',
                    'rooms',
                    'area',
                    'cost',
                    'commission_type',
                    'blocks.updated_at',
                ]),
            ],

            'id' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'buildings_address' => [
                'nullable',
            ],
            'buildings_name' => [
                'nullable',
            ],
            'region' => [
                'nullable',
            ],
            'metro' => [
                'nullable',
            ],
            'metro_time' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'floor' => [
                'nullable',
                'integer',
            ],
            'rooms' => [
                'nullable',
                'integer',
                'min:1',
                'max:8',
            ],
            'area_from' => [
                'nullable',
            ],
            'area_to' => [
                'nullable',
            ],
            'cost_from' => [
                'nullable',
            ],
            'cost_to' => [
                'nullable',
            ],
            'living_conds' => [
                'nullable',
                'integer',
                'min:1',
                'max:5',
            ],
            'commission_type' => [
                'nullable',
                'integer',
                'min:1',
                'max:2',
            ],
            'living_area_from' => [
                'nullable',
            ],
            'living_area_to' => [
                'nullable',
            ],
            'kitchen_area_from' => [
                'nullable',
            ],
            'kitchen_area_to' => [
                'nullable',
            ],
            'balcony' => [
                'nullable',
                'array',
            ],
            'balcony.*' => [
                'in:' . implode(',', array_keys(Block::optionsLang(
                    Block::BALCONIES,
                    'cruds.block.fields.balcony_options'
                ))),
            ],
            'windowsInOut' => [
                'nullable',
                'integer',
                'in:' . implode(',', array_keys(Block::optionsLang(
                    Block::WINDOWS,
                    'cruds.block.fields.windowsInOut_options'
                ))),
            ],
            'separate_wc_count' => [
                'nullable',
                'array',
            ],
            'separate_wc_count.*' => [
                'in:' . implode(',', array_keys(Block::optionsLang(
                    Block::WC_COUNTS,
                    'cruds.block.fields.wc_count_options'
                ))),
            ],
            'combined_wc_count' => [
                'nullable',
                'array',
            ],
            'combined_wc_count.*' => [
                'in:' . implode(',', array_keys(Block::optionsLang(
                    Block::WC_COUNTS,
                    'cruds.block.fields.wc_count_options'
                ))),
            ],
            'renovation' => [
                'nullable',
                'in:' . implode(',', array_keys(Block::optionsLang(
                    Block::RENOVATIONS,
                    'cruds.block.fields.renovation_options'
                ))),
            ],
            'filling' => [
                'nullable',
                'array',
            ],
            'filling.*' => [
                'in:' . implode(',', array_keys(Block::optionsLang(
                    Block::FILLINGS,
                    'cruds.block.fields.filling_options'
                ))),
            ],
            'shower_bath' => [
                'nullable',
                'array',
            ],
            'shower_bath.*' => [
                'in:' . implode(',', array_keys(Block::optionsLang(
                    Block::SHOWER_BATHS,
                    'cruds.block.fields.shower_bath_options'
                ))),
            ],
            'ceiling_from' => [
                'nullable',
            ],
            'ceiling_to' => [
                'nullable',
            ],
            'rooms_type' => [
                'nullable',
                'integer',
                'in:' . implode(',', array_keys(Block::optionsLang(
                    Block::ROOM_TYPES,
                    'cruds.block.fields.rooms_type_options'
                ))),
            ],
            'type' => [
                'nullable',
                'integer',
                'in:0,' . implode(',', array_keys(Block::optionsLang(
                    Block::TYPES,
                    'cruds.block.fields.type_options'
                ))),
            ],
            'status' => [
                'nullable',
            ],
            'cian' => [
                'nullable',
                'in:0,1'
            ],
            'create_date_from' => [
                'nullable',
                'date',
                'date_format:Y-m-d',
            ],
            'create_date_to' => [
                'nullable',
                'date',
                'date_format:Y-m-d',
            ],
            'created_at' => [
                'nullable',
                'date',
                'date_format:Y-m-d'
            ]
        ];
    }

    /**
     * Get an input element from the request.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function attr($key, $default = null)
    {
        $attrs = $this->validated();

        return isset($attrs[$key]) ? $attrs[$key] : $default;
    }

    /**
     * @return Builder
     */
    public function getQueryBuilder(): Builder
    {
        // Init Builder
        $query = Block::query();

        // Select Array
        $select = [
            'blocks.id',
            'blocks.building_id',
            'blocks.rooms',
            'blocks.floor',
            'blocks.area',
            'blocks.cost',
            'blocks.status',
            'blocks.commission_type',
            'buildings.address AS buildings.address',
            'buildings.name AS buildings.name',
            'buildings.floors AS buildings.floors',
            'blocks.contact',
            'blocks.created_at'
        ];

        $query->select($select);

        // Filters
        if (!empty($this->attr('id'))) {
            $query->where('blocks.id', $this->attr('id'));
        }
        if (!empty($this->attr('floor'))) {
            $query->where('blocks.floor', $this->attr('floor'));
        }
        if (!empty($this->attr('rooms'))) {
            $query->where('blocks.rooms', $this->attr('rooms'));
        }
        if (!empty($this->attr('rooms'))) {
            $query->where('blocks.rooms', $this->attr('rooms'));
        }
        if (!empty($this->attr('area_from'))) {
            $query->where('blocks.area', '>=', $this->attr('area_from'));
        }
        if (!empty($this->attr('area_to'))) {
            $query->where('blocks.area', '<=', $this->attr('area_to'));
        }
        if (!empty($this->attr('cost_from'))) {
            $query->where('blocks.cost', '>=', $this->attr('cost_from'));
        }
        if (!empty($this->attr('cost_to'))) {
            $query->where('blocks.cost', '<=', $this->attr('cost_to'));
        }
        if (!empty($this->attr('living_conds'))) {
            $query->where('blocks.living_conds', $this->attr('living_conds'));
        }
        if (!empty($this->attr('commission_type'))) {
            $query->where('blocks.commission_type', $this->attr('commission_type'));
        }
        if (!empty($this->attr('buildings_address'))) {
            $query->where('buildings.address', 'like', '%' . $this->attr('buildings_address') . '%');
        }
        if (!empty($this->attr('buildings_name'))) {
            $query->where('buildings.name', $this->attr('buildings_name'));
        }
        if (!empty($this->attr('metro_time'))) {
            $query->where('buildings.metro_time', $this->attr('metro_time'));
        }
        if (!empty($this->attr('region'))) {
            $query->where('regions.id', $this->attr('region'));
        }
        if (!empty($this->attr('metro'))) {
            $query->where('metros.id', $this->attr('metro'));
        }
        // Living Area Blocks Filter
        if (!empty($this->attr('living_area_from'))) {
            $query->where('blocks.living_area', '>=', $this->attr('living_area_from'));
        }
        if (!empty($this->attr('living_area_to'))) {
            $query->where('blocks.living_area', '<=', $this->attr('living_area_to'));
        }
        if (!empty($this->attr('kitchen_area_from'))) {
            $query->where('blocks.kitchen_area', '>=', $this->attr('kitchen_area_from'));
        }
        if (!empty($this->attr('kitchen_area_to'))) {
            $query->where('blocks.kitchen_area', '<=', $this->attr('kitchen_area_to'));
        }
        if (!empty($this->attr('balcony'))) {
            if (sizeof($this->attr('balcony')) > 0) {
                $query->whereIn('blocks.balcony', $this->attr('balcony'));
            }
        }
        if (!empty($this->attr('windowsInOut'))) {
            $query->where('blocks.windowsInOut', $this->attr('windowsInOut'));
        }
        if (!empty($this->attr('separate_wc_count'))) {
            if (sizeof($this->attr('separate_wc_count')) > 0) {
                $query->whereIn('blocks.separate_wc_count', $this->attr('separate_wc_count'));
            }
        }
        if (!empty($this->attr('combined_wc_count'))) {
            if (sizeof($this->attr('combined_wc_count')) > 0) {
                $query->whereIn('blocks.combined_wc_count', $this->attr('combined_wc_count'));
            }
        }
        if (!empty($this->attr('renovation'))) {
            $query->where('blocks.renovation', $this->attr('renovation'));
        }
        if (!empty($this->attr('filling'))) {
            if (sizeof($this->attr('filling')) > 0) {
                $query->whereIn('blocks.filling', $this->attr('filling'));
            }
        }
        if (!empty($this->attr('shower_bath'))) {
            if (sizeof($this->attr('shower_bath')) > 0) {
                $query->whereIn('blocks.shower_bath', $this->attr('shower_bath'));
            }
        }
        if (!empty($this->attr('ceiling_from'))) {
            $query->where('buildings.ceil_height', '>=', $this->attr('ceiling_from'));
        }
        if (!empty($this->attr('ceiling_to'))) {
            $query->where('buildings.ceil_height', '<=', $this->attr('ceiling_to'));
        }
        //
        if (!empty($this->attr('rooms_type'))) {
            if ($this->attr('rooms_type') > 0) {
                $query->where('blocks.rooms_type', $this->attr('rooms_type'));
            }
        }
        //
        if (!empty($this->attr('type'))) {
            $query->where('blocks.type', $this->attr('type'));
        }
        //
        if (!is_null($this->attr('status'))) {
            $query->where('blocks.status', $this->attr('status'));
        } else {
            $query->where('status', '!=', Block::STATUS_DRAFT);
        }
        if (!is_null($this->attr('cian'))) {
            if ((int)$this->attr('cian') === 0) {
                $query->whereNull('cian');
            }
            if ((int)$this->attr('cian') === 1) {
                $query->whereNotNull('cian');
            }
        }
        if ($this->attr('create_date_from')) {
            $query->where('blocks.created_at', '>', $this->attr('create_date_from') . ' 00:00:00');
        }
        if ($this->attr('create_date_to')) {
            $query->where('blocks.created_at', '<', $this->attr('create_date_to') . ' 23:59:59');
        }
        if ($this->attr('created_at')) {
            $query->where('blocks.created_at', '>', $this->attr('created_at') . ' 00:00:00')
                ->where('blocks.created_at', '<', $this->attr('created_at') . ' 23:59:59');
        }

        // Joins
        $query->leftJoin('buildings', 'buildings.id', '=', 'blocks.building_id')
            ->leftJoin('regions', 'regions.id', '=', 'buildings.region_id')
            ->leftJoin('metros', 'metros.id', '=', 'buildings.metro_id');

        return $query;
    }

    /*
     * @return array
     */
    public function paginate()
    {
        // Get Query
        $query = $this->getQueryBuilder();

        //
        $query->orderBy($this->attr('sort_attr'), $this->attr('sort_dir'));
        $items = $query->paginate($this->attr('per_page'));

        // Mutate values
        foreach ($items as $key => $value) {
            // Mutate floors
            if (isset(Block::ROOMS [$value->rooms])) {
                $items [$key]->rooms = __('cruds.block.fields.rooms_options.' . Block::ROOMS [$value->rooms]);
            }

            // Mutate living conditions
            $arrayConds = [];
            foreach ($value->living_conds as $cond) {
                if (isset(Block::LIVING_CONDS [$cond])) {
                    $arrayConds [] = __('cruds.block.fields.living_conds_options.' . Block::LIVING_CONDS [$cond]);
                }
            }
            $items [$key]->living_conds = implode(', ', $arrayConds);

            // Mutate commission type
            if (isset(Block::COMMISSION_TYPES [$value->commission_type])) {
                $type = $value->commission_type;
                $langKey = 'cruds.block.fields.commission_type_options.';
                $items[$key]->commission_type = __($langKey . Block::COMMISSION_TYPES[$type]);
            }
        }

        return new BlockCollection($items);
    }
}
