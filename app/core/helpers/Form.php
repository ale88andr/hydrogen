<?php

/**
* Form helpers
*/
class Form extends Html
{
    public static function text($name, $options = [])
    {
        $name = static::responceName($name);
        return static::tag('input', false, array_merge(['type' => 'text',
                                                        'name' => $name['script'],
                                                        'id' => $name['html'],
                                                        'class' => $name['html']
                                                        ], $options));
    }

    public static function label($for, $content, $options = [])
    {
        return static::tag('label', $content, array_merge(['for' => $for], $options));
    }

    public static function password($name, $options = [])
    {
        $name = static::responceName($name);
        return static::tag('input', false, array_merge(['type' => 'password',
                                                        'name' => $name['script'],
                                                        'id' => $name['html'],
                                                        'class' => $name['html']
                                                        ], $options));
    }

    public static function email($name, $options = [])
    {
        $name = static::responceName($name);
        return static::tag('input', false, array_merge(['type' => 'email',
                                                        'name' => $name['script'],
                                                        'id' => $name['html'],
                                                        'class' => $name['html']
                                                        ], $options));
    }

    public static function numeric($name, $options = [])
    {
        return static::tag('input', false, array_merge(['type' => 'number', 'name' => $name], $options));
    }

    public static function submit($value, $options = [])
    {
        return static::tag('input', false, array_merge(['type' => 'submit'], $options));
    }

    protected static function responceName($name)
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