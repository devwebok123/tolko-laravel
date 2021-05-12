function getAttributes ( $node ) {
      $.each( $node[0].attributes, function ( index, attribute ) {
      console.log(attribute.name+':'+attribute.value);
   } );
}

(function ($) {
    'use strict';

    var galleryDefaults = {
        tagMap: [],
        csrfToken: $('meta[name=csrf-token]').attr('content'),
        csrfTokenName: $('meta[name=csrf-param]').attr('content'),
        itemTemplate: '',
 
        uploadUrl: '',
        deleteUrl: '',
        updateUrl: '',
        arrangeUrl: '',
        uploadFromServerUrl: '',
        saveFromServerUrl: '',

        items: [],
        fields: [],

        editable: true,
    };

    function galleryManager(el, options) {

        var opts = $.extend({}, galleryDefaults, options);
        var csrfParams = opts.csrfToken ? '&' + opts.csrfTokenName + '=' + opts.csrfToken : '';
        var items = {}; 
        var $gallery = $(el);
              
        var $sorter = $('.sorter', $gallery);
        var $items = $('.items', $sorter);
        var $editorModal = $('.editor-modal', $gallery);
        var $progressOverlay = $('.progress-overlay', $gallery);
        var $uploadProgress = $('.upload-progress', $progressOverlay);
        var $editorForm = $('.form', $editorModal);

        function htmlEscape(str) {
            return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;');
        }

        function createEditorElement(id, data) {
            
            var htmlPreview = '';
            if ('preview' in data) {
                htmlPreview = '<img src="' + htmlEscape(data.preview) + '"  style="max-width:100%;">';
            }
            if ('download' in data) {
                htmlPreview = '<a href="' + htmlEscape(data.download) + '">скачать</a>';
            }
            
            var html = '<div class="item-editor row">' +
                        '<div class="col-6">' +        
                        htmlPreview +
                        '</div>' +
                        '<div class="col-6">';
                
            $.each(opts.fields, function(k, field) {
                
                field.value = data[field.name];
                    
                html += '<div class="form-group">';
                html +=     '<label class="control-label" for="items_' + id + '_' + field.name + '">' + field.label + ':</label> ';
                
                if (field.type == 'input') {
                    html += fieldInput(id, field);
                } 
                if (field.type == 'select') {
                    html += fieldSelect(id, field);
                } 
                if (field.type == 'checkbox') {
                    html += fieldCheckbox(id, field);
                } 
                
                html +=     '<span class="help-block" style=""></span>';
                html += '</div>';                
            })
                
            html += '</div>';    
            
            return $(html);
        }
        

        function fieldInput(id, field) {
            return '<input type="text" class="form-control" value="' + field.value + 
                   '" name="items[' + id + '][' + field.name + ']" id="items_' + id + '_' + field.name + '">';
        }

        function fieldSelect(id, field) {
            var html = '<select class="form-control" name="items[' + id + '][' + field.name + ']" id="items_' + id + '_' + field.name + '">';

            $.each(field.options, function (k, v) {
                html += '<option value="' + k + '" ' + (field.value == k ? 'selected' : '') + '>' + v + '</option>';
            })
            html += '</select>';

            return html;
        }

        function fieldCheckbox(id, field) {
            var html = '<div class="btn-group from_builder_buttons">';
                html +=     '<input type="hidden" id="items_' + id + '_is_active" name="items[' + id + '][' + field.name + ']" value="' + field.value + '">';
                html += '   <button type="button" data-active-class="btn-info" class="js-toggle-btn btn ' + (field.value == 1 ? 'btn-info' : 'btn-default') + '" data-value="1">да</button>';
                html += '   <button type="button" data-active-class="btn-info" class="js-toggle-btn btn ' + (field.value == 0 ? 'btn-info' : 'btn-default') + '" data-value="0">нет</button>';
                html += '</div>'
                
            return html;
        }

        var activeMap = [
            'opacity: 0.5',
            'opacity: 1'
        ];

        function addItem(id, data) {
            var itemElem = $(opts.itemTemplate);
            items[id] = itemElem;
           
            itemElem = itemUpdate(itemElem, data);
          
            $items.append(itemElem);

            return itemElem;
        }
        
        function itemUpdate(item, data) {
            item.attr('data-id', data.id)
            $.each(data, function(k, v) {
                if (k == 'preview') {
                    $('img', item).attr('src', data.preview);
                } else if (k == 'download') {
                    $('.js-download a', item).attr('href', data.download);
                } else {
                    $('.js-' + k, item).text(v);
                }
            })
              
            item.attr('data-item', JSON.stringify(data));
            
            if('is_active' in data) {
                item.attr('style', activeMap[data.is_active]);
            }
            
            return item;
        }

        function editItems(ids, errors) {
            var form = $editorForm.empty();
            
            if (errors.length) {
                var errorWrap = form.append('<div class="alert alert-danger" role="alert"></div>');
                $.each(errors, function(k, v) {
                    $('.alert', form).append(v + '<br/>');
                })
            }
            
            for (var i = 0; i < ids.length; i++) {
                var id = ids[i];
                
                var itemElem = items[id];
                
                form.append(createEditorElement(id, JSON.parse(itemElem.attr('data-item'))));
            }
            if (ids.length > 0 || errors.length) {
                $editorModal.modal('show');
            }
            
            if (!ids.length) {
                $('.modal-footer', $editorModal).hide();
                $('.js-modal-title-error', $editorModal).show();
                $('.js-modal-title', $editorModal).hide();
            } else {
                $('.modal-footer', $editorModal).show();
                $('.js-modal-title-error', $editorModal).hide();
                $('.js-modal-title', $editorModal).show();
            }
        }

        function removePhotos(ids) {
            $.ajax({
                type: 'DELETE',
                url: opts.deleteUrl,
                async: true,
                data: {ids: ids},
                headers: {
                    'X-CSRF-TOKEN': window._token
                },
                success: function (t) {
                    for (var i = 0, l = ids.length; i < l; i++) {
                        items[ids[i]].remove();
                        delete items[ids[i]];
                    }
                }
            });
        }

        function removePhotosToBasket(ids) {
            $.ajax({
                type: 'DELETE',
                url: opts.deleteUrl,
                async: true,
                headers: {
                    'X-CSRF-TOKEN': window._token
                },
                data: {ids: ids},
                success: function (t) {
                    for (var i = 0, l = ids.length; i < l; i++) {
                        items[ids[i]].remove();
                        delete items[ids[i]];
                    }
                }
            });
        }

        function deleteClickToBasket(e) {
            e.preventDefault();
            var itemElem = $(this).closest('.item');
            var id = itemElem.data('id');
            // here can be question to confirm delete
            if (!confirm('Вы уверены что хотите удалить запись в корзину?'))
                return false;
            removePhotosToBasket([id]);
            return false;
        }

        function deleteClick(e) {
            e.preventDefault();
            var itemElem = $(this).closest('.item');
            var id = itemElem.data('id');
            // here can be question to confirm delete
            if (!confirm('Вы уверены что хотите удалить запись навсегда?'))
                return false;
            removePhotos([id]);
            return false;
        }

        function editClick(e) {
            e.preventDefault();
            var itemElem = $(this).closest('.item');
            var id = itemElem.data('id');
            editItems([id], []);

        }

        function updateButtons() {
            var selectedCount = $('.item.selected', $sorter).length;
            $('.select_all', $gallery).prop('checked', $('.item', $sorter).length == selectedCount);
            if (selectedCount == 0) {
                $('.edit_selected, .remove_selected', $gallery).addClass('disabled');
            } else {
                $('.edit_selected, .remove_selected', $gallery).removeClass('disabled');
            }
        }

        function selectChanged() {
            var $this = $(this);
            if ($this.is(':checked'))
                $this.closest('.item').addClass('selected');
            else
                $this.closest('.item').removeClass('selected');
            updateButtons();
        }

        $items.on('click', '.item .deletePhoto', deleteClickToBasket)
                .on('click', '.item .editPhoto', editClick)
                .on('click', '.item .item-select', selectChanged);


        if (opts.editable) {

            $('.items', $sorter).sortable({tolerance: "pointer"}).disableSelection().bind("sortstop", function () {
                var orderIds = [];
                $('.item', $sorter).each(function () {
                    var t = $(this);
                    orderIds.push(t.data('id'));
                });
                $.ajax({
                    type: 'POST',
                    url: opts.arrangeUrl,
                    data: {ids: orderIds, _method: 'PUT'},
                    headers: {
                        'X-CSRF-TOKEN': window._token
                    },
                    dataType: "json"
                }).done(function (data) {
                    for (var id in data[id]) {
                        items[id].data('rank', data[id]);
                    }
                });
            });
        }

        if (window.FormData !== undefined) { // if XHR2 available

            var uploadFileName = $('.afile', $gallery).attr('name');

            var multiUpload = function (files) {
                var uploadErrors = [];
                
                if (files.length == 0)
                    return;
                $progressOverlay.show();
                $uploadProgress.css('width', '5%');
                var filesCount = files.length;
                var uploadedCount = 0;
                var ids = [];
                for (var i = 0; i < filesCount; i++) {
                    var fd = new FormData();

                    fd.append(uploadFileName, files[i]);
                    if (opts.csrfToken) {
                        fd.append(opts.csrfTokenName, opts.csrfToken);
                    }
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', opts.uploadUrl, true);
                    xhr.setRequestHeader('X-CSRF-TOKEN', window._token);
                    xhr.onload = function () {
                        uploadedCount++;
                        var resp = JSON.parse(this.response);
                            
                        if (this.status === 200) {
                            addItem(resp['id'], resp);
                            ids.push(resp['id']);
                        } else if (this.status === 422) {
                            
                            $.each(resp.errors, function (k, v) {
                                uploadErrors.push(v);
                            })
                        }
                        $uploadProgress.css('width', '' + (5 + 95 * uploadedCount / filesCount) + '%');

                        if (uploadedCount === filesCount) {
                            $uploadProgress.css('width', '100%');
                            $progressOverlay.hide();
                            editItems(ids, uploadErrors);
                        }
                    };
                    xhr.send(fd);
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState != 4)
                            return;
                    }
                }

            };

            (function () { // add drag and drop
                var el = $gallery[0];
                var isOver = false;
                var lastIsOver = false;

                setInterval(function () {
                    if (isOver != lastIsOver) {
                        if (isOver)
                            el.classList.add('over');
                        else
                            el.classList.remove('over');
                        lastIsOver = isOver
                    }
                }, 30);

                function handleDragOver(e) {
                    e.preventDefault();
                    isOver = true;
                    return false;
                }

                function handleDragLeave() {
                    isOver = false;
                    return false;
                }

                function handleDrop(e) {
                    e.preventDefault();
                    e.stopPropagation();


                    var files = e.dataTransfer.files;
                    multiUpload(files);

                    isOver = false;
                    return false;
                }

                function handleDragEnd() {
                    isOver = false;
                }


                el.addEventListener('dragover', handleDragOver, false);
                el.addEventListener('dragleave', handleDragLeave, false);
                el.addEventListener('drop', handleDrop, false);
                el.addEventListener('dragend', handleDragEnd, false);
            })();

            $('.afile', $gallery).attr('multiple', 'true').on('change', function (e) {
                e.preventDefault();

                multiUpload(this.files);
            });
        } 

        $('.save-changes', $editorModal).click(function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                typeType: 'json',
                data: $('input, textarea, select', $editorForm).serialize() + '&_method=PUT',
                url: opts.updateUrl,
                headers: {
                    'X-CSRF-TOKEN': window._token
                },
                beforeSend: function () {
                    $('.error', $editorForm).removeClass('error');
                    $('.has-error', $editorForm).removeClass('has-error');
                    $('.modal-footer .js-btn-form', $editorModal).addClass('hidden');
                    $('.modal-footer .js-btn-saving', $editorModal).removeClass('hidden');
                    $('form', $editorModal).css('opacity', '0.5');
                },
                success: function (data) {
                    $('.modal-footer .js-btn-form', $editorModal).removeClass('hidden');
                    $('.modal-footer .js-btn-saving', $editorModal).addClass('hidden');
                    $('form', $editorModal).css('opacity', '1');    
                    var count = data.length;
                    for (var key = 0; key < count; key++) {
                        var props = data[key];
                        var item = items[props.id];
                        itemUpdate(item, props);
                    }
                    $editorModal.modal('hide');
                    //deselect all items after editing
                    $('.item.selected', $sorter).each(function () {
                        $('.item-select', this).prop('checked', false)
                    }).removeClass('selected');
                    
                    $('.select_all', $gallery).prop('checked', false);
                    
                    updateButtons();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('.modal-footer .js-btn-form', $editorModal).removeClass('hidden');
                    $('.modal-footer .js-btn-saving', $editorModal).addClass('hidden');
                    $('form', $editorModal).css('opacity', '1');
                    
                    if (jqXHR.status === 422) {
                        $.each(jQuery.parseJSON(jqXHR.responseText).errors, function (k, v) {
                            var id = k.replace('.', '_');
                            id = id.replace('.', '_');
                            var field = $('#' + id, $editorModal);
                            var formGroup = field.closest('.form-group');
                            field.addClass('error');
                            formGroup.addClass('has-error');
                            formGroup.find('.help-block').show().html(v[0]);
                        })
                    } else {
                        alertNotify(errorThrown, 'danger');
                    }
                }
            })
        });

        $('.edit_selected', $gallery).click(function (e) {
            e.preventDefault();
            var ids = [];
            $('.item.selected', $sorter).each(function () {
                ids.push($(this).data('id'));
            });
            editItems(ids, []);
            return false;
        });

        $('.remove_selected', $gallery).click(function (e) {
            e.preventDefault();
            
            if (!confirm('Вы уверены что хотите удалить запись в корзину?')) {
                return false;
            }

            var ids = [];
            $('.item.selected', $sorter).each(function () {
                ids.push($(this).data('id'));
            });
            removePhotos(ids);

        });

        $('.select_all', $gallery).change(function () {
            if ($(this).prop('checked')) {
                $('.item', $sorter).each(function () {
                    $('.item-select', this).prop('checked', true)
                }).addClass('selected');
            } else {
                $('.item.selected', $sorter).each(function () {
                    $('.item-select', this).prop('checked', false)
                }).removeClass('selected');
            }
            updateButtons();
        });

        for (var i = 0, l = opts.items.length; i < l; i++) {
            var resp = opts.items[i];
            addItem(resp['id'], resp);
        }
    }

    // The actual plugin
    $.fn.galleryManager = function (options) {
        if (this.length) {
            this.each(function () {
                galleryManager(this, options);
            });
        }
    };
    
})(jQuery);