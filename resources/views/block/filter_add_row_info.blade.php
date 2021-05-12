<div class="row">

    <div class="col-md-3">

        @if ($block->simplePhotos->count() > 0)
            <a href="#" class="js-modal-photo mr-3" data-id="{{ $block->id }}">
                <i class="fas fa-camera fa-fw"></i> <sup>{{ $block->simplePhotos->count() }}</sup></a>
        @endif

        @if ($block->planPhotos->count() > 0)
            <a href="#" class="js-modal-plan mr-3">
                <i class="fas fa-print fa-fw"></i> <sup>{{ $block->planPhotos->count() }}</sup></a>
        @endif

        @if ($block->is_out_of_market)
            <span class="ml-3"><strong>В рекламе:</strong> объект вне рынка</span>
        @else
            <strong>В рекламе:</strong>
            <a href="{{$block->cian_offer_id ? 'https://www.cian.ru/rent/flat/'. $block->cian_offer_id : '#'}}"
               class="ml-2" title="{{ $block->ad_title }}" target="_blank">
                <i class="icon-inv icon-cian @if(!$block->cian) icon-inactive @endif"></i>
            </a>
            <a href="#" class="ml-2" title="{{ $block->avito_promo_description }}">
                <i class="icon-inv icon-avito @if(!$block->avito_promo) icon-inactive @endif"></i>
            </a>
        @endif

    </div>
    <div class="col-md-4">
        <div class="col-md-12">Статистика</div>
        <div class="col-md-12">Охват: {{(int)$statistic->coverage}}</div>
        <div class="col-md-12">Показы: {{(int)$statistic->shows_count}}</div>
        <div class="col-md-12">Выдача в поиске: {{(int)$statistic->searches_count}}</div>
        <div class="col-md-12">Показов телефона: {{(int)$statistic->phones_shows}}</div>

    </div>

    <div class="col-md-5">

        <button data-id="{{ $block->id }}"
                class="btn btn-default btn-xs {{ $block->status !== \App\Models\Block::STATUS_ACTIVE ? 'd-none' : '' }} js-btn-deactivate-listing">
            Листинг не актуален
        </button>

        <button data-id="{{ $block->id }}"
                class="btn btn-info ml-4 btn-xs js-btn-activate-listing">Листинг актуален
        </button>

        @if($block->cian || $block->avito_promo || $block->yandex_promo)
            <div class=" row">
                <div class="col-md-12">
                    @if($block->cian)
                        <div class="row">
                            <div class="col-md-12">Тип: {{$block->cian_description}}</div>
                            <div class="col-md-12">Ставка: {{$block->bet}} </div>
                            <div class="col-md-12">К-во дней в размещении: {{$block->cian_publication_date_count}}</div>
                        </div>
                    @endif
                </div>
                @if($block->avito_promo)
                    <div class="col-md-12">
                        {{__('cruds.block.fields.avito_promo')}}: {{$block->avito_promo_description}}
                    </div>
                @endif
                @if($block->yandex_promo)
                    <div class="col-md-12">
                        {{__('cruds.block.fields.yandex_promo')}}: {{$block->yandex_promo_description}}
                    </div>
                @endif
            </div>
        @endif

    </div>

</div>


@if ($block->simplePhotos->count() > 0)
    @include('block.subview.modal-carousel', [
        'id' => 'modalPhotos',
        'title' => 'Фотографии',
        'photos' => $block->simplePhotos,
    ])
@endif

@if ($block->planPhotos->count() > 0)
    @include('block.subview.modal-carousel', [
    'id' => 'modalPlans',
        'title' => 'Планы',
        'photos' => $block->planPhotos,
    ])
@endif
