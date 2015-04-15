<?php
require_once '../config/environment.php';

require_once ROOT_APP . 'init' . EXT;

use core\App;

$app = new App;
try {
    require_once $app->layout();
}

catch(Exception $e) {
    die($e->getMessage());
}