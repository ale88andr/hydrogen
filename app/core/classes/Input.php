<?php
class Input

{
    /**
     * Checks that user form is submitted.
     * Example: if(Input::isSubmit()) { # code here... }
     *
     * @param string $method   Form send method
     * @return bool
     */
    public static function isSubmit($method = 'post')
    {
        switch ($method) {
        case 'post':
            return (!empty($_POST)) ? true : false;
            break;

        case 'get':
            return (!empty($_GET)) ? true : false;
            break;

        default:
            return false;
            break;
        }
    }

    /**
     * Returns item from GET/POST array.
     * Example: Input::find($item)
     *
     * @param string $item   Item from GET/POST array
     * @return mixed
     */
    public static function find($item)
    {
        if(strpos($item, '.')) {
            $item = explode('.', $item);
            $search_item = $_POST;
            foreach ($item as $value) {
                $search_item = $search_item[$value];
            }

            return $search_item;
        }

        if (isset($_POST[$item])) {
            return $_POST[$item];
        }
        elseif (isset($_GET[$item])) {
            return $_GET[$item];
        }

        return '';
    }
}