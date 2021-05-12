<?php
/**
 * @var \App\DataObjects\Setting\SettingObject $setting
 */
?>
@extends('adminlte::page')

@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.setting.title') }}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    @if (session('is_update'))
                        <div class="alert alert-success">{{__('global.success')}}</div>
                    @endif
                    <form action="{{route('setting.update')}}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="phone_cian">{{__('cruds.setting.fields.phone_cian')}}</label>
                            <input
                                type="text"
                                class="form-control"
                                id="phone_cian"
                                name="phone_cian"
                                value="{{old('phone_cian') ?: $setting->getPhoneCian()}}">
                            @error('phone_cian')
                            <span class="help-block">{{$message}}</span>
                            @enderror

                        </div>
                        <div class="form-group">
                            <label for="phone_avito">{{__('cruds.setting.fields.phone_avito')}}</label>
                            <input
                                type="text"
                                class="form-control"
                                id="phone_avito"
                                name="phone_avito"
                                value="{{old('phone_avito') ?: $setting->getPhoneAvito()}}">
                            @error('phone_avito')
                            <span class="help-block">{{$message}}</span>
                            @enderror

                        </div>
                        <div class="form-group">
                            <label for="phone_yandex">{{__('cruds.setting.fields.phone_yandex')}}</label>
                            <input
                                type="text"
                                class="form-control"
                                id="phone_yandex"
                                name="phone_yandex"
                                value="{{old('phone_yandex') ?: $setting->getPhoneYandex()}}">
                            @error('phone_yandex')
                            <span class="help-block">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 js-group-submit">
                            <button type="submit" class="btn btn-info">{{__('global.save')}}</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection
