<?php

// namespace hydrogen\helpers;

class Html
{
    public static $voidTags = [
        'area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input',
        'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr'
        ];

    public static function tag($tag, $content, $options = [])
    {
        $html = "<{$tag} " . static::optionsHandler($options) . '>';
        if(array_search($tag, static::$voidTags)) {
            return $html;
        }

        return $html . $content . '</' . $tag . '>';
    }

    public static function optionsHandler($options = [])
    {
        if(is_array($options)) {
            $html_params = '';
            foreach ($options as $tag_attribute => $attribute_value) {
                if(!empty($attribute_value)) {
                    $html_params .= is_bool($attribute_value) ? "{$tag_attribute} "
                                                              : "{$tag_attribute}={$attribute_value} ";
                }
            }

            return $html_params;
        }
    }
}