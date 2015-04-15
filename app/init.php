<?php
spl_autoload_register(function($class)
{
    $class = str_replace('\\', DS, $class);
    if(file_exists(ROOT_APP . 'lib' . DS . $class . EXT)){
        require_once ROOT_APP . 'lib' . DS . $class . EXT;
    }
});