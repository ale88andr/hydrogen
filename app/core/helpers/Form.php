<?php

/**
* Form helpers
*/
class Form extends Html
{
    public static function text($name, $options = [])
    {
        $name = static::responseName($name);
        return static::tag('input', false, array_merge(['type' => 'text',
                                                        'name' => $name['script'],
                                                        'id' => $name['html'],
                                                        'class' => $name['html']
                                                        ], $options));
    }

    public static function label($for, $content, $options = [])
    {
        if(strpos($for, '.')){
            $for = str_replace('.', '_', $for);
        }
        return static::tag('label', $content, array_merge(['for' => $for], $options));
    }

    public static function password($name, $options = [])
    {
        $name = static::responseName($name);
        return static::tag('input', false, array_merge(['type' => 'password',
                                                        'name' => $name['script'],
                                                        'id' => $name['html'],
                                                        'class' => $name['html']
                                                        ], $options));
    }

    public static function email($name, $options = [])
    {
        $name = static::responseName($name);
        return static::tag('input', false, array_merge(['type' => 'email',
                                                        'name' => $name['script'],
                                                        'id' => $name['html'],
                                                        'class' => $name['html']
                                                        ], $options));
    }

    public static function numeric($name, $min, $max, $step, $options = [])
    {
        $name = static::responseName($name);
        return static::tag('input', false, array_merge(['type' => 'number',
                                                        'name' => $name['script'],
                                                        'min' => $min,
                                                        'max' => $max,
                                                        'step' => $step,
                                                        'id' => $name['html'],
                                                        'class' => $name['html']
                                                        ], $options));
    }

    public static function submit($value, $options = [])
    {
        return static::tag('input', false, array_merge(['type' => 'submit', 'value' => $value], $options));
    }

    public static function select($name, $values = [], $options = [])
    {
        $name = static::responseName($name);
        $content = '';
        foreach ($values as $value) {
            $content .= '<option value=' . strtolower($value) . '>' . $value . '</option>';
        }

        return static::tag('select', $content, array_merge(['name' => $name['script'],
                                                            'id' => $name['html'],
                                                            'class' => $name['html']
                                                            ], $options));
    }

    public static function check_box($name, $checkboxes, $checked = false, $options = [], $inline = false)
    {
        $name = static::responseName($name);
        $html = '<div style={display: block}>';
        foreach ($checkboxes as $key => $value) {
            $checked_flag = [];
            if($checked !== false && $key == $checked){
                $checked_flag = ['checked' => true];
            }
            $html .= static::tag('input', false, array_merge([ 'type' => 'checkbox',
                                                                'name' => $name['script'],
                                                                'id' => $name['html'] . '_' . $key,
                                                                'class' => $name['html']
                                                                ], $options, $checked_flag));
            $html .= $value;

            if($inline === false) {
                $html .= static::tag('br');
            }
        }

        return $html . '</div>';
    }

    public static function radio($name, $buttons, $checked = false, $options = [], $inline = false)
    {
        $name = static::responseName($name);
        $html = '<div style={display: block}>';
        foreach ($buttons as $key => $value) {
            $checked_flag = [];
            if($checked !== false && $key == $checked){
                $checked_flag = ['checked' => true];
            }
            $html .= static::tag('input', false, array_merge([  'type' => 'radio',
                                                                'name' => $name['script'],
                                                                'id' => $name['html'] . '_' . $key,
                                                                'class' => $name['html']
                                                                ], $options, $checked_flag));
            $html .= $value;

            if($inline === false) {
                $html .= static::tag('br');
            }
        }

        return $html . '</div>';
    }

    public static function hidden($name, $value)
    {
        $name = static::responseName($name);
        return static::tag('input', false, ['type' => 'hidden', 'value' => $value]);
    }

    public static function date($name, $default = false, $options = [])
    {
        $name = static::responseName($name);
        $post_options = [];
        if($default !== false){
            $post_options['value'] = $default;
        }

        return static::tag('input', false, array_merge([
                                                        'type' => 'date',
                                                        'name' => $name['script'],
                                                        'id' => $name['html'],
                                                        'class' => $name['html']
                                                        ], $options, $post_options));
    }

    protected static function responseName($name)
    {
        $name = trim($name);
        if(strpos($name, '.')) {
            $array_name = explode('.', $name);
            $value_for_attributes = implode('_', $array_name);
            $value_for_name = array_shift($array_name);
            foreach ($array_name as $value) {
                $value_for_name .= '[' . $value . ']';
            }

            return ['html' => $value_for_attributes, 'script' => $value_for_name];
        }

        return ['html' => $name, 'script' => $name];
    }
}