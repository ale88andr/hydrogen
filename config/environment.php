<?php
    define('DS', DIRECTORY_SEPARATOR);
    define('ROOT', dirname(dirname(__FILE__)));
    define('ROOT_APP', ROOT . DS . 'app' . DS);
    define('EXT', '.php');
    define('EXT_VIEW', '.html.php');

    define('DEVELOPMENT_ENV', true);

    require_once ROOT . DS . 'config' . DS . 'initializer' . EXT;

    before_load();