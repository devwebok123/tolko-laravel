<?php

namespace App\Services\Form;

use Illuminate\Support\Str;

class Html
{
    const FIELD_ATTRIBUTES = [
        'type',
        'name',
        'id',
        'class',
        'placeholder',
        'value',
        'step',
        'multiple',
        'data-value',
        'row',
        'rows',
        'readonly',
        'disabled',
        'labelTemplate',
//        'style',
    ];

    /**
     * @var Form
     */
    protected $form;

    /**
     *
     * @param  Form  $form
     *
     * @return void
     */
    public function __construct(Form  $form)
    {
        $this->form = $form;
    }

    /**
     * @return string
     */
    public function begin(): string
    {
        list($method, $subMethod) = $this->form->resolveMethod();

        $html = '<form id="' . $this->form->getId() . '" action="' . $this->form->action .
            '" method="' . $method . '" class="' . $this->form->formClass . '"';

        foreach ($this->form->attributes as $k => $v) {
            $html .= ' ' . $k . '="' . $v . '"';
        }

        $html .= ' enctype="multipart/form-data">';

        $html .= csrf_field();
        if ($subMethod !== null) {
            $html .= '<input type="hidden" name="_method" value="' . $subMethod . '">';
        }

        return $html;
    }

    /**
     * @return string
     */
    public function end(): string
    {
        return '</form>';
    }

    /**
     * @param string $tag
     * @param string $name
     * @param array $params
     * @return string
     */
    public function field(string $tag, string $name, array $params = []): string
    {
        $params['name'] = $name;
        $this->form->resolveFieldConfig($name, $params);

        $field = substr_count($tag, 'range') ?  $this->form->addFieldRangeFromTo('input', $params) :
            $this->form->addField($tag, $params);

        $this->form->resolveFieldConfig($name, $params);

        /*
        if (!empty($ers = $errors[$field['name']])) {
            $errorClass = 'has-error';
            $errSfx   = '<span class="help-block">' . implode(', ', $ers) . '</span>';
        }
        */

        $field['groupClass'] .= ' js-group-' . Str::snake($field['id']);

        //$html = '<div class="js-group ' . $field['groupClass'] . ' ' . @$errorClass . '">';
        $html = '<div class="js-group ' . $field['groupClass'] . '">';
        $html .= $this->getLabel($field);
        $html .= call_user_func_array([$this, $tag], [$field]);
        //$html .= @$errSfx . '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * @return string
     */
    public function render() : string
    {
        $errors = $this->form->getErrors();

        list($method, $subMethod) = $this->form->resolveMethod();

        $id = $this->form->getId();

        $form = '';
        $html = '';

        if (!$this->form->hideForm) {
            $form = '<form id="' . $id . '" action="' . $this->form->action . '" method="' . $method . '" class="' .
                $this->form->formClass . '" name="' . $this->form->name . '"';

            foreach ($this->form->attributes as $k => $v) {
                $form .= ' ' . $k . '="' . $v . '"';
            }

            $form .= ' enctype="multipart/form-data">';

            $html .= csrf_field();
            if ($subMethod !== null) {
                $html .= '<input type="hidden" name="_method" value="' . $subMethod . '">';
            }
        }

        $formBlocks = [];

        foreach ($this->form->fields as $field) {
            $itemHtml = '';

            if ($field['tag'] === 'input' && isset($field['type']) && $field['type'] === 'hidden') {
                $html .=    $this->{$field['tag'] . ''}($field);
                continue;
            }

            $errorClass = !empty($errors[$field['name']]) ? 'has-error' : '';
            $errorMsg   = !empty($errors[$field['name']]) ? implode(', ', $errors[$field['name']]) : '';

            $field['groupClass'] .= ' js-group-' . Str::snake($field['id']);

            if (!in_array($field['tag'], ['button', 'rawHtml'])) {
                $itemHtml .= '<div class="js-group ' . $field['groupClass'] . ' ' . $errorClass . '">';
                $itemHtml .=    $this->getLabel($field);
                $itemHtml .=    $this->{$field['tag'] . ''}($field);

                $itemHtml .=    '<span class="help-block">' . $errorMsg . '</span>';
                $itemHtml .= '</div>';
            } else {
                $itemHtml .=    $this->{$field['tag'] . ''}($field);
            }

            if (!empty($field['card']) && !empty($this->form->cards[$field['card']])) {
                if (!isset($formBlocks[$field['card']])) {
                    $formBlocks[$field['card']] = $this->form->cards[$field['card']];
                    $formBlocks[$field['card']]['type'] = 'card';
                }
                $formBlocks[$field['card']]['items'][] = $itemHtml;
            } else {
                $formBlocks[$field['name']]['html'] = $itemHtml;
                $formBlocks[$field['name']]['type'] = 'filed';
            }
        }

        //render form fileds with cards
        foreach ($formBlocks as $key => $formBlock) {
            if ($formBlock['type'] === 'filed') {
                $html .= $formBlock['html'];
            } elseif ($formBlock['type'] === 'card') {
                $htmlFields = '';
                foreach ($formBlock['items'] as $itemField) {
                    $htmlFields .= $itemField;
                }
                $formBlock['name'] = $key;
                $html .= str_replace('{content}', $htmlFields, $this->renderCard($formBlock));
            }
        }

        if (!empty($this->form->buttons)) {
            $html .= '<div class="' . $this->form->btnGroupClass . '">';

            foreach ($this->form->buttons as $button) {
                $html .= '<input class="btn '. $button['class'] .'" ';
                foreach ($button['options'] as $k => $v) {
                    $html .= sprintf('%s="%s"', $k, $v);
                }
                $html .= 'type="'. $button['type'] .'" value="' . $button['label'] .'"> &nbsp;&nbsp;';
            }

            $html .= '</div>';
        }

        if (!empty($this->form->ajaxifyPanel)) {
            $html .= '<div class="'. ($this->form->ajaxifyPanel['options']['groupClass'] ?? '') .'" >';
            $html .= '<label class="w-100">&nbsp;</label>';

            if ($this->form->model->exists) {
                if (!empty($this->form->ajaxifyPanel['actions']['edit']) &&
                    $this->form->ajaxifyPanel['actions']['edit']['hasAccess']
                ) {
                    $class = isset($this->form->ajaxifyPanel['actions']['edit']['class']) ?
                        $this->form->ajaxifyPanel['actions']['edit']['class'] : '';

                    $icon = isset($this->form->ajaxifyPanel['actions']['edit']['icon']) ?
                        '<i class="fas ' . $this->form->ajaxifyPanel['actions']['edit']['icon'] . '"></i>' : '';

                    $label = isset($this->form->ajaxifyPanel['actions']['edit']['label']) ?
                        $this->form->ajaxifyPanel['actions']['edit']['label'] : '';

                    $html .= ' <button class="btn ' . $class . ' js-ajaxify-btn-edit" type="button">' .
                            $icon . $label . '</button>';
                }
                if (!empty($this->form->ajaxifyPanel['actions']['destroy']) &&
                    $this->form->ajaxifyPanel['actions']['destroy']['hasAccess']
                ) {
                    $class = isset($this->form->ajaxifyPanel['actions']['destroy']['class']) ?
                        $this->form->ajaxifyPanel['actions']['destroy']['class'] : '';

                    $icon = isset($this->form->ajaxifyPanel['actions']['destroy']['icon']) ?
                        '<i class="fas ' . $this->form->ajaxifyPanel['actions']['destroy']['icon'] . '"></i>' : '';

                    $label = isset($this->form->ajaxifyPanel['actions']['destroy']['label']) ?
                        $this->form->ajaxifyPanel['actions']['destroy']['label'] : '';

                    $html .= ' <button class="btn ' . $class . ' js-ajaxify-btn-destroy" type="button" data-url="' .
                        $this->form->ajaxifyPanel['actions']['destroy']['url']
                        . '"> ' . $icon . $label . ' </button>';
                }
            } else {
                $class = isset($this->form->ajaxifyPanel['actions']['store']['class']) ?
                    $this->form->ajaxifyPanel['actions']['store']['class'] : '';

                $icon = isset($this->form->ajaxifyPanel['actions']['store']['icon']) ?
                        '<i class="fas ' . $this->form->ajaxifyPanel['actions']['store']['icon'] . '"></i>' : '';

                $label = isset($this->form->ajaxifyPanel['actions']['store']['label']) ?
                    $this->form->ajaxifyPanel['actions']['store']['label'] : '';

                $html .= ' <button class="btn ' . $class . ' js-ajaxify-btn-store" type="button">' . $icon . $label .
                            '</button>';
            }

            $html .= '</div>';
        }

        $form .= $this->addWrapper($html);

        if (!$this->form->hideForm) {
            $form .= '</form>';
        }

        return  $form;
    }

    /**
     * @param array $data
     * @return string
     */
    private function renderCard(array $data): string
    {
        $template = !empty($data['template']) ? $data['template'] : 'card';
        $titleTag = !empty($data['titltTag']) ? $data['titltTag'] : 'h5';
        $class = !empty($data['class']) ? $data['class'] : 'col-sm-12';

        $html = '';

        if ($template === 'card') {
            $html .= '<div class="js-card-' . $data['name'] . ' ' . $class . '">';
            $html .= '<div class="card">';
            $html .=    '<div class="card-header">';
            $html .=        "<{$titleTag}>{$data['title']}</{$titleTag}>";
            $html .=    '</div>';
            $html .=    '<div class="card-body">';
            $html .=    '<div class="row">';
            $html .=        '{content}';
            $html .=    '</div>';
            $html .=    '</div>';
            $html .= '</div>';
            $html .= '</div>';
        } elseif ($template === 'collspace') {
            $html .= '<div class="card ' . $class . ' js-card-' . $data['name'] . '">';
            $html .=   '<div class="card-header" id="heading-' . $data['name'] . '">';
            $html .=     '<h5 class="mb-0">';
            $html .=       '<a class="btn btn-link" href="#" data-toggle="collapse" '
                            . 'data-target="#collapse-' . $data['name'] . '" aria-expanded="true" '
                            . 'aria-controls="collapse-' . $data['name'] . '">' . $data['title'] .'</a>';
            $html .=     '</h5>';
            $html .=   '</div>';
            $html .=   '<div id="collapse-' . $data['name'] . '" class="collapse" '
                            . 'aria-labelledby="heading-' . $data['name'] . '">';
            $html .=     '<div class="card-body">';
            $html .=        '<div class="row">';
            $html .=            '{content}';
            $html .=        '</div>';
            $html .=     '</div>';
            $html .=   '</div>';
            $html .= '</div>';
        } elseif ($template === 'block') {
            $html .= '<div class="row js-card-' . $data['name'] . ' ' . $class . '">';
            $html .=        '{content}';
            $html .= '</div>';
        }

        return $html;
    }

    /**
     * @param array $field
     *
     * @return string
     */
    public function getLabel(array $field) : string
    {
        $class = $this->form->labelConfig[$field['name']]['class'] ?? '';
        $style = $this->form->labelConfig[$field['name']]['style'] ?? '';

        if (!empty($field['labelTemplate'])) {
            return '<label for="' . $field['id'] . '" class="' . $class . '">' .
                str_replace('{label}', $field['label'], $field['labelTemplate'])  . '< /label>';
        } else {
            return '<label style="' . $style . '" for="' . $field['id'] . '" class="' . $class . '">' .
                $field['label'] . ' </label>';
        }
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function number(array $attributes) : string
    {
        $attributes['type'] = 'number';

        return $this->input($attributes);
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function text(array $attributes) : string
    {
        $attributes['type'] = 'text';

        return $this->input($attributes);
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function input(array $attributes) : string
    {
        $html = '<input '.join(' ', array_map(function ($key) use ($attributes) {
                return in_array($key, self::FIELD_ATTRIBUTES)
                    ? $key . '="'.$attributes[$key].'"'
                    : '';
        }, array_keys($attributes))) . ' />';

        if (!empty($attributes['append'])) {
            $colField =  $attributes['colFieldClass'] ?? 'col-sm-6';
            $colAppend = $attributes['colAppendClass'] ?? 'col-sm-6';

            $html = '<div class="row"><div class="' .  $colField . '">' . $html . '</div>  ' .
                    '<div class="' . $colAppend . '">' . $attributes['append'] . '</div></div>';
        }

        return $html;
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function textarea(array $attributes) : string
    {
        return '<textarea '.join(' ', array_map(function ($key) use ($attributes) {
            return in_array($key, self::FIELD_ATTRIBUTES) && $key !== 'value'
                ? $key . '="'.$attributes[$key].'"'
                : '';
        }, array_keys($attributes))) . '>' . $attributes['value'] . '</textarea>';
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function select(array $attributes) : string
    {
        if (!empty($attributes['multiple'])) {
            $attributes['class'] = isset($attributes['class']) ?
                $attributes['class'] . ' bootstrap-select' : $attributes['class'];

            if (!substr_count($attributes['name'], '[]')) {
                $attributes['name'] = $attributes['name'] . '[]';
            }
        }

        $html = '<select '.join(' ', array_map(function ($key) use ($attributes) {
            return in_array($key, self::FIELD_ATTRIBUTES) && $key !== 'value'
                ? $key . '="'.$attributes[$key].'"'
                : '';
        }, array_keys($attributes))) . '>';

        if (!isset($attributes['empty']) || $attributes['empty'] !== false) {
            $empty = !empty($attributes['empty']) ? $attributes['empty'] : '-';
            $html .= '<option value="">' . $empty . '</option>';
        }

        foreach ($attributes['options'] as $k => $v) {
            $selected = (!is_array($attributes['value']) && $attributes['value'] === $k) ||
            (is_array($attributes['value']) && in_array($k, $attributes['value']))
                ? 'selected="selected"' : '';

            $html .= '<option value="' . $k . '" '. $selected .'>' . $v . '</option>';
        }

        $html .= '</select>';

        if (!empty($attributes['append'])) {
            $colField =  $attributes['colFieldClass'] ?? 'col-sm-6';
            $colAppend = $attributes['colAppendClass'] ?? 'col-sm-6';

            $html = '<div class="row"><div class="' .  $colField . '">' . $html . '</div>  ' .
                '<div class="' . $colAppend . '">' . $attributes['append'] . '</div></div>';
        }

        return $html;
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function image(array $attributes) : string
    {
        $html = '';

        $html .= '<input type="file"'.join(' ', array_map(function ($key) use ($attributes) {
            return in_array($key, self::FIELD_ATTRIBUTES) && $key !== 'value'
                ? $key . '="'.$attributes[$key].'"'
                : '';
        }, array_keys($attributes))) . '>';

        if (!empty($attributes['img'])) {
            $html .= sprintf('<br/><img src="%s" height="80">', $attributes['img']);
        }

        return $html;
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function rangeFromToInput(array $attributes) : string
    {
        $type = 'number';
        $class = 'form-control';

        $name = $attributes['name'];
        $vf = $attributes['value_from'];
        $vt = $attributes['value_to'];

        return '<div class="row">
            <div class="col-md-6">
                <div class="row align-items-end">
                    <label class="col-sm-1 col-xl-2 text-nowrap for="'.$name.'_from">От &nbsp;</label>
                    <div class="col-sm-11 col-xl-10">
                        <input type="'.$type.'" name="'.$name.'_from" value="'.$vf.'" class="'.$class.'" step="1">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row align-items-end">
                    <label class="col-sm-1 col-xl-2 text-nowrap" for="'.$name.'_to">До &nbsp;</label>
                    <div class="col-sm-11 col-xl-10">
                        <input type="'.$type.'" name="'.$name.'_to" value="'.$vt.'" class="'.$class.'" step="1">
                    </div>
                </div>
            </div>
        </div>';
    }

    /**
     * @param  array  $attributes
     *
     * @return string
     */
    public static function toggle(array $attributes) : string
    {
        $html = '<div class="btn-group btn-group-toggle" data-toggle="buttons">';

        $type = @$attributes['multiple'] ? 'checkbox' : 'radio';
        foreach ($attributes['options'] as $k => $v) {
            $v = str_replace(' ', '&nbsp;', $v);
            $active = $attributes['value'] === $k;

            $html .= '<label class="btn btn-default ' . ($active ? 'active' : '') . '">' .
                '<input type="' . $type . '" ' . ($active ? 'checked' : '') . ' ' .
                'name="' . $attributes['name'] . '" value="' . $k . '">' . $v . ' </label>';
        }

        return $html.'</div>';
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function nullableRadio(array $attributes) : string
    {
        if (array_key_exists('default', $attributes) && $attributes['default'] === false) {
            $attributes['value'] = '';
        }

        $html = '<div class="btn-group js-nullable-toggle">';
        $html.= '<input type="hidden" data-reset="'.$attributes['value'] . '" id="'.$attributes['id']
            .'" name="'.$attributes['name'] . '" value="'.$attributes['value'].'"/>';

        foreach ($attributes['options'] as $k => $v) {
            $v = str_replace(' ', '&nbsp;', $v);
            $active = $attributes['value'] === $k ? 'active' : '';
            $html .= '<button type="button" class="js-nullable-toggle-btn btn btn-default ' .
                $active . '" data-value="' . $k . '">' . $v . '</button>';
        }

        return $html.'</div>';
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function rawHtml(array $attributes) : string
    {
        $html = '<div>';

        $html.= '<div class="js-group ' . $attributes['groupClass'] . '">';
        $html.= '<label class="w-100"></label>';
        $html.= $attributes['html'];

        return $html.'</div>';
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function button(array $attributes) : string
    {
        $html = '<div class="' . $attributes['groupClass'] . '">';
        $html .= '<button type="'.$attributes['type'].'" class="btn ' . $attributes['class'] . '">';
        $html .= $attributes['text'];
        $html .= '</button>';
        $html .= '</div>';

        return $html;
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function range(array $attributes) : string
    {
        $type = 'number';
        $class = 'form-control';

        $name = $attributes['name'];
        $vf = $attributes['value_from'];
        $vt = $attributes['value_to'];

        return '<div class="row">
            <div class="col-md-6">
                <div class="row align-items-end">
                    <label class="col-sm-1 col-xl-2 text-nowrap for="'.$name.'_from">От
                        &nbsp;</label>
                    <div class="col-sm-11 col-xl-10">
                        <input type="'.$type.'" name="'.$name.'_from" value="'.$vf.'" class="'.$class.'" step="1">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row align-items-end">
                    <label class="col-sm-1 col-xl-2 text-nowrap" for="'.$name.'_to">До
                        &nbsp;</label>
                    <div class="col-sm-11 col-xl-10">
                        <input type="'.$type.'" name="'.$name.'_to" value="'.$vt.'" class="'.$class.'" step="1">
                    </div>
                </div>
            </div>
        </div>';
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function rangeDate(array $attributes) : string
    {
        $name = $attributes['name'];
        $vf = $attributes['value_from'];
        $vt = $attributes['value_to'];

        return '<div class="row">
            <div class="col-md-6">
                <div class="row align-items-end">
                    <label class="col-sm-1 col-xl-2 text-nowrap for="'.$name.'_from">От
                        &nbsp;</label>
                    <div class="col-sm-11 col-xl-10">
                        <input type="text" name="'.$name.'_from" value="'.$vf.
                            '" class="form-control js-datepicker js-mask-date">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row align-items-end">
                    <label class="col-sm-1 col-xl-2 text-nowrap" for="'.$name.'_to">До
                        &nbsp;</label>
                    <div class="col-sm-11 col-xl-10">
                        <input type="text" name="'.$name.'_to" value="'.$vt.
                            '" class="form-control js-datepicker js-mask-date">
                    </div>
                </div>
            </div>
        </div>';
    }

    /**
     * @param string $html
     *
     * @return string
     */
    public function addWrapper(string $html) : string
    {
        $htmlWithWtappers = '';

        if (!empty($this->form->wrappers)) {
            foreach ($this->form->wrappers as $wrapper) {
                $htmlWithWtappers .= '<div '.join(' ', array_map(function ($key) use ($wrapper) {
                        return $key . '="'.$wrapper[$key].'"';
                }, array_keys($wrapper))) . '>';
            }

            $htmlWithWtappers .= $html;

            foreach ($this->form->wrappers as $wrapper) {
                $htmlWithWtappers .= '</div>';
            }
        } else {
            return $html;
        }

        return $htmlWithWtappers;
    }
}
