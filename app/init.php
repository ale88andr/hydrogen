<?php
spl_autoload_register(function ($class)
{
    if(file_exists(ROOT_APP . 'core' . DS . 'classes' . DS . $class . EXT)) {
        require_once ROOT_APP . 'core' . DS . 'classes' . DS . $class . EXT;
    }
    elseif(file_exists(ROOT_APP . 'core' . DS . 'helpers' . DS . $class . EXT)) {
        require_once ROOT_APP . 'core' . DS . 'helpers' . DS . $class . EXT;
    }

});