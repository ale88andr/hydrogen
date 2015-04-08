<?php
    spl_autoload_register(function($class) {
        require_once ROOT_APP . 'core' . DS . 'classes' . DS . $class . EXT;
    });