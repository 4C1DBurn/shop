<?php

namespace app\controllers;


use shop\Cache;

class MainController extends AppController {

    public function indexAction() {
        $this->setMeta("Main Page", "Description", "keys");
        $cache = Cache::instance();
        $names = ['Andy', 'Ray'];
        $this->set(['name' => 'TestName', 'age' => 30]);
    }
}