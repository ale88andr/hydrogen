<?php

// namespace hydrogen\helpers;

/**
* Link helper
*/
class Link extends Html
{

    public static function to($href, $title, $options = [])
    {
        $href = ['href' => ROOT_URL . $href];
        return static::tag('a', $title, array_merge($href, $options));
    }
}