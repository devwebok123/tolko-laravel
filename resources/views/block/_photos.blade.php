<?php

use App\Models\Block;
use App\Models\BlockPhoto;

/** @var Block $block */
?>

<h5>Фото</h5>

<div id="block-photos" class="gallery-manager">

    <!-- Gallery Toolbar -->
    <div class="btn-toolbar" style="padding:4px">

        <div class="btn-group" style="display: inline-block; margin-right:4px">
            <div class="btn btn-success btn-file" style="display: inline-block">
                <i class="fa fa-plus"></i>{{ __('cruds.galleryManager.add') }}
                <input type="file" name="image_file" class="afile" accept="image/*" multiple="multiple"/>
            </div>
        </div>

        <div class="btn-group" style="display: inline-block;">
            <label class="btn btn-default">
                <input type="checkbox" style="margin-right: 4px;" class="select_all">{{ __('cruds.galleryManager.select_all') }}
            </label>
            <label class="btn btn-default disabled edit_selected">
                <i class="fa fa-edit"></i> {{ __('cruds.galleryManager.edit') }}
            </label>
            <label class="btn btn-default disabled remove_selected">
                <i class="fa fa-times"></i> {{ __('cruds.galleryManager.delete') }}
            </label>
        </div>

    </div>
    <hr/>

    <!-- Gallery Photos -->
    <div class="sorter">
        <div class="items"></div>
        <br style="clear: both;"/>
    </div>

    <!-- Modal window to edit photo information -->
    <div class="modal editor-modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title js-modal-title">{{ __('cruds.galleryManager.edit_info') }}</h4>
                    <h4 class="modal-title js-modal-title-error">{{ __('cruds.galleryManager.uploading_error') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form"></div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="js-btn-form btn btn-success save-changes">{{ __('cruds.galleryManager.save') }}</a>
                    <a href="#" class="js-btn-form btn" data-dismiss="modal">{{ __('cruds.galleryManager.cancel') }}</a>
                    <a href="#" class="js-btn-saving btn btn-success disabled hidden">{{ __('cruds.galleryManager.saving') }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="progress-overlay">
        <div class="overlay-bg">&nbsp;</div>
        <!-- Upload Progress Modal-->
        <div class="modal progress-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>{{ __('cruds.galleryManager.uploading_images') }}</h3>
                    </div>
                    <div class="modal-body">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-info progress-bar-striped active upload-progress"
                                 role="progressbar">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@push('js')
<script src="{{asset('js/jquery-ui.js')}}"></script>
<script src="{{asset('js/jquery.iframe-transport.min.js')}}"></script>
<script src="{{ asset('js/galleryManager/jquery.galleryManager.js') }}"></script>

<script type="text/javascript">

    $('#block-photos').galleryManager({

        'uploadUrl':'{{route('block.photo.upload', $block->id)}}',
        'deleteUrl':'{{route('block.photo.destroy', $block->id)}}',
        'updateUrl':'{{route('block.photo.update', $block->id)}}',
        'arrangeUrl':'{{route('block.photo.sort', $block->id)}}',

        'itemTemplate': '<div class="item" style="">'+
                        '<div class="js-image image-preview">' +
                            '<img src="">'+
                        '</div>' +
                        '<div class="caption">'+
                            '<p class="js-name"></p>'+
                            '<h5 class="js-tag_title"></h5>'+
                        '</div>'+
                        '<div class="actions">'+
                            '<span class="editPhoto btn btn-primary btn-xs"><i class="fa fa-edit"></i></span> '+
                            '<span class="deletePhoto btn btn-danger btn-xs"><i class="fa fa-times"></i></span>'+
                       '</div>'+
                       '<input type="checkbox" class="item-select"/>'+
                    '</div>',

    'fields': [

        {
            'name' : 'name',
            'type' : 'input',
            'label': '<?= __('cruds.blockPhoto.fields.name')?>'
        },

        {
            'name' : 'tag_id',
            'type' : 'select',
            'options' : <?= json_encode(BlockPhoto::TAGS)?>,
            'label': '<?= __('cruds.blockPhoto.fields.tag_id')?>'
        },

        {
            'name' : 'status',
            'type' : 'select',
            'options': <?= json_encode(BlockPhoto::STATUSES, JSON_THROW_ON_ERROR) ?>,
            'label': '<?= __('cruds.blockPhoto.fields.status')?>'
        }

    ],
    'items': <?= json_encode($block->photos->toArray())?>,
});
</script>
@endpush
@push('css')
    <link href="{{asset('css/jquery-ui.css')}}" rel="stylesheet" />
    <link href="{{ asset('css/galleryManager/galleryManager.css') }}" rel="stylesheet" />
@endpush
