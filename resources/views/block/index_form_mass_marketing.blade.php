<?php

use App\Models\Block;
use App\Services\Form\Form;

$modelForm = new Block;
?>

<div class="card">
    <div class="card-header">
        <h5 class="float-sm-left text-uppercase"><?= 'Mass Marketing' ?></h5>
    </div>
    <div class="card-body">
    <?=
        FormBuilder::create([
            'action' => route('block.mass'),
            'method' => 'POST',
            'model'  => $modelForm,
            'id'  => 'mass-marketing-from',
            'groupClass' => 'col-md-2',
            'btnGroupClass' => 'col-md-2',
        ], function (Form $form) {

            $form->wrapper(['class' => 'row col-md-12']);

            $form->nullableRadio('out_of_market', [], [
                'nullable' => true,
                'groupClass' => 'col-md-2 label-block',
                'default' => false,
                'btnAddtWrap' => true,
            ]);

            $form->select('cian', [0 => 'убрать из фида'] + Block::CIAN_PROMOS);

            $form->number('bet', [
                'step' => 10,
                'min' => 0,
                'max' => 990,
            ]);

            $form->select('avito_promo', [0 => 'убрать из фида'] + Block::optionsLang(Block::AVITO_PROMOS, 'cruds.block.fields.avito_promos'));
            $form->select('yandex_promo', [0 => 'убрать из фида'] + Block::optionsLang(Block::YANDEX_PROMOS, 'cruds.block.fields.yandex_promos'));

            $form->buttons('submit', 'btn-info mt-label', __('global.saveAll'));
        });
    ?>
    </div>
</div>

@push('js')
<script>
    $('body').on('submit', '#mass-marketing-from', function(e) {
        e.preventDefault();
        const form = $(this);

        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': window._token
            },
            dataType: 'html',
            beforeSend: function () {
                $('.help-block', form).hide();
                $('.error', form).removeClass('error');
                $('.has-error', form).removeClass('has-error');
                form.css('opacity', '0.5');
            },
            success: function (data) {
                alertNotify('Успешно сохранено', 'success');
                form.css('opacity', '1');
            },
            error: function (jqXHR, textStatus, errorThrown) {

                if (jqXHR.status === 422) {
                    $.each(jQuery.parseJSON(jqXHR.responseText).errors, function (k, v) {
                        id = k.replace('.', '_')

                        field = $('#' + id, form);
                        formGroup = field.closest('.js-group');
                        field.addClass('error');
                        formGroup.addClass('has-error');
                        formGroup.find('.help-block').show().html(v[0]);
                    })
                } else {
                    alertNotify(errorThrown, 'danger');
                }
                form.css('opacity', '1');
            }
        })
    })
</script>
@endpush
