<?php
    require_once '../config/environment.php';

    require_once ROOT_APP .'init' . EXT;

    $app = new App;

    require_once '../app/views/layouts/' . App::getLayout() . EXT_VIEW;