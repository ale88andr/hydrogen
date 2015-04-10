<?php
/**
* String helpers
*/
class String
{
    public static function capitalize($subject)
    {
        return strtoupper($subject);
    }

    public static function titlize($subject)
    {
        return ucwords($subject);
    }

    public static function dasherize($subject)
    {
        if(strpos($subject, '_')){
            return str_replace('_', '-', $subject);
        }

        return $subject;
    }

    public static function first($subject)
    {
        if(is_string($subject)){
            return $subject[0];
        }
    }

    public static function raw($subject)
    {
        return htmlspecialchars($subject, ENT_QUOTES);
    }

    public static function remove($subject, $search)
    {
        return str_replace($search, '', $subject);
    }

    public static function truncate($subject, $length, $omission = '...')
    {
        if(length($subject) > $length){
            $omission_length = length($omission);
            $subject = substr($subject, 0, length($subject) - $omission_length);
            $subject .= $omission;
        }

        return $subject;
    }

    public static function isBlank($subject)
    {
        return (empty($subject)) ? true : false;
    }
}