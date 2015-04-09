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