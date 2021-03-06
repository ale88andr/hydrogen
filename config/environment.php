<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));
define('ROOT_APP', ROOT . DS . 'app' . DS);
define('EXT', '.php');
define('EXT_VIEW', '.html.php');
define('ROOT_URL', 'http://' . $_SERVER['SERVER_NAME'] . '/public/');
define('DEVELOPMENT_ENV', true);
require_once ROOT . DS . 'config' . DS . 'initializer' . EXT;

date_default_timezone_set('Europe/Moscow');

before_load();