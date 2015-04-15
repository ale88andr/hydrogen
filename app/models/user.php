<?php

use core\Model;

class User extends Model
{
    public $name;

    public function __construct(){
        parent::__construct();
    }
}