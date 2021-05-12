<?php
/**
 * @var \App\Models\Block $block
 */
?>
@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1>Объект: {{$block->id}}</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="text-center">Создан: {{$block->created_at}}</div>
        </div>
        <div class="col-md-5">
            <label style="" for="currency" class="">{{__('cruds.block.fields.status')}}</label>
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-default {{$block->status === \App\Models\Block::STATUS_ACTIVE ? 'active' : ''}}">
                    <input
                        class="status"
                        type="radio"
                        {{$block->status === \App\Models\Block::STATUS_ACTIVE ? 'checked="checked"' : ''}}
                        name="status"
                        @if($block->status === \App\Models\Block::STATUS_DRAFT) disabled="disabled" @endif
                        onclick="changeStatus({{$block->id}}, {{\App\Models\Block::STATUS_ACTIVE}})"
                        value="0"
                    >{{__('cruds.block.fields.status_options.active')}}
                </label>
                <label
                    class="btn btn-default  {{$block->status === \App\Models\Block::STATUS_NOT_ACTIVE ? 'active' : ''}}">
                    <input
                        class="status"
                        {{$block->status === \App\Models\Block::STATUS_NOT_ACTIVE ? 'checked="checked"' : ''}}
                        type="radio"
                        name="status"
                        @if($block->status === \App\Models\Block::STATUS_DRAFT) disabled="disabled" @endif
                        onclick="changeStatus({{$block->id}}, {{\App\Models\Block::STATUS_NOT_ACTIVE}})"
                        value="{{\App\Models\Block::STATUS_NOT_ACTIVE}}"
                    >{{__('cruds.block.fields.status_options.not_active')}}
                </label>
                <label class="btn btn-default  {{$block->status === \App\Models\Block::STATUS_DRAFT ? 'active' : ''}}">
                    <input
                        class="status"
                        {{$block->status === \App\Models\Block::STATUS_DRAFT ? 'checked="checked"' : ''}}
                        type="radio"
                        name="status"
                        disabled="disabled"
                        onclick="changeStatus({{$block->id}}, {{\App\Models\Block::STATUS_DRAFT}})"
                        value="{{\App\Models\Block::STATUS_DRAFT}}">{{__('cruds.block.fields.status_options.draft')}}
                </label>
                @if ($block->photosDraft->count() > 0)
                    <a href="#" class="js-modal-photo mr-3" data-id="{{ $block->id }}">
                        <i class="fas fa-camera fa-fw"></i> <sup>{{ $block->photosDraft->count() }}</sup></a>
                @endif
            </div>
            <span class="help-block"></span>
        </div>
        <div class="col-md-3">
            <a class="btn btn-secondary" href="{{route('block.edit', ['block' => $block->id])}}">
                <i class="fas fa-edit fa-fw"></i>Редактировать
            </a>
        </div>
    </div>
    <div class="card">
        <div class="row">
            <div class="col-md-6">
                @if ($block->simplePhotos->count() > 0)
                    @include('block.subview.carousel', ['photos' => $block->simplePhotos,])
                @endif
                <div>
                    <h2>{{__('cruds.contact.fields.name')}}</h2>
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td>{{__('cruds.block.fields.contact')}}</td>
                            <td><a target="_blank" href="{{$block->contact}}">{{__('cruds.contact.fields.link')}}</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <h2>{{__('cruds.block.fields.comment')}}</h2>
                    <div>{{$block->comment}}</div>
                </div>
                <div>
                    <h2>{{__('cruds.block.fields.description')}}</h2>
                    <div>{{$block->description}}</div>
                </div>

                @include('block.index_form_apirosreestr')
            </div>

            <div class="col-md-6">

                <div>
                    <h2>{{__('cruds.block.listing')}}</h2>
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td style="width: 300px">{{__('cruds.block.fields.cost')}}</td>
                            <td>{{$block->cost_formatted}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.deposit')}}</td>
                            <td>{{$block->deposit_formatted}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.cost_m')}}</td>
                            <td>{{$block->cost_meter}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.currency')}}</td>
                            <td>{{$block->currency_description}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.commission')}}</td>
                            <td>{{$block->commission_type_description}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.commission')}}</td>
                            <td>{{(int)$block->commission}}%</td>
                        </tr>

                        <tr>
                            <td>{{__('cruds.block.fields.commission_value')}}</td>
                            <td>{{$block->commission_amount}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.commission_comment')}}</td>
                            <td>{{$block->commission_comment}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.status')}}</td>
                            <td>{{$block->status_description}}</td>
                        </tr>
                        <tr>
                            <td> {{__('cruds.block.fields.out_of_market')}}</td>
                            <td>{{$block->out_of_market ? __('global.yes') : __('global.no')}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.contract_signed')}}</td>
                            <td>{{$block->contract_signed ? __('global.yes') : __('global.no')}}
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.included')}}</td>
                            <td>{{implode(', ', $block->included_descriptions)}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.parking_cost')}}</td>
                            <td>{{(int)$block->parking_cost}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.bargain')}}</td>
                            <td>{{$block->bargain}}%</td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <div>
                    <h2>{{__('cruds.block.title_singular')}}</h2>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 300px">Характеристика</th>
                            <th>Значение</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{__('cruds.block.fields.floor')}}</td>
                            <td>{{$block->floor}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.flat_number')}}</td>
                            <td>{{$block->flat_number}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.area')}}</td>
                            <td>{{$block->area}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.living_area')}}</td>
                            <td>{{$block->living_area}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.kitchen_area')}}</td>
                            <td>{{$block->kitchen_area}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.type')}}</td>
                            <td>{{$block->type_description}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.rooms')}}</td>
                            <td>{{$block->rooms}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.rooms_type')}}</td>
                            <td>{{$block->rooms_type_description}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.balcony')}}</td>
                            <td>{{$block->balcony_description}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.windowsInOut')}}</td>
                            <td>{{$block->windows_in_out_description}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.separate_wc_count')}}</td>
                            <td>{{$block->separate_wc_count}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.combined_wc_count')}}</td>
                            <td>{{$block->combined_wc_count}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.renovation')}}</td>
                            <td>{{$block->renovation_description}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.filling')}}</td>
                            <td> {{implode(', ',$block->filling_descriptions)}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.shower_bath')}}</td>
                            <td>{{implode(', ', $block->shower_bath_descriptions)}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.living_conds')}}</td>
                            <td>{{implode(', ', $block->living_conds_descriptions)}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.tenant_count_limit')}}</td>
                            <td>{{$block->tenant_count_limit}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.ceiling')}}</td>
                            <td>{{$block->building->ceil_height}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.cadastral_number')}}</td>
                            <td>{{$block->cadastral_number}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.video_url')}}</td>
                            <td>{{$block->video_url}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <h2>{{__('cruds.building.title_singular')}}</h2>
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td style="width: 300px">{{__('cruds.building.title_singular')}}</td>
                            <td>{{$block->building->name}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.building.fields.address')}}</td>
                            <td>{{$block->building->address}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.block.fields.metro')}}</td>
                            <td>{{$block->building->metro ? $block->building->metro->name : ''}}
                                {{$block->building->metro2 ? ', ' . $block->building->metro2->name : ''}}
                                {{$block->building->metro3 ? ', ' . $block->building->metro3->name : ''}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.building.fields.metro_time_type')}}</td>
                            <td>{{$block->building->metro_time_type_description}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.building.fields.metro_time')}}</td>
                            <td>{{$block->building->metro_time}}</td>
                        </tr>
                        <tr>
                            <td>{{__('cruds.building.fields.floors')}}</td>
                            <td>{{$block->building->floors}}</td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @if ($block->photosDraft->count() > 0)
        @include('block.subview.modal-carousel', [
            'id' => 'modalPhotos',
            'title' => 'Фотографии',
            'photos' => $block->photosDraft,
        ])
    @endif
@endsection

@push('js')
    <script>
        let currentStatus = {{(int)$block->status}};
        const STATUS_ACTIVE = {{\App\Models\Block::STATUS_ACTIVE}};
        const STATUS_NOT_ACTIVE = {{\App\Models\Block::STATUS_NOT_ACTIVE}};
        const STATUS_DRAFT = {{\App\Models\Block::STATUS_DRAFT}};

        function changeStatus(blockId, status) {
            if (status === currentStatus) {
                return false;
            }
            currentStatus = status;
            let url = '/api/block/' + blockId;
            if (status === STATUS_ACTIVE) {
                url += '/activate'
            } else if (status === STATUS_NOT_ACTIVE) {
                url += '/deactivate'
            } else {
                return false;
            }
            $.ajax({
                url: url,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': window._token
                }
            })
        }

        $('#carousel-photos').carousel({
            interval: false,
        });

        $('body').on('click', '.js-modal-photo', function (e) {
            e.preventDefault();
            $("#modalPhotos").modal("show");
        });
    </script>
@endpush
