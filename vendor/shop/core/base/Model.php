<?php

namespace shop\base;


use shop\Db;

abstract class Model {

    public $attributes = [];
    public $errors = [];
    public $rules = [];

    public function __construct() {

        Db::instance();
    }
}