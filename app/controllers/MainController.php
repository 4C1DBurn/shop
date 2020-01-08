<?php

namespace app\controllers;


class MainController extends AppController {

    public function indexAction() {
        $this->setMeta("Main Page", "Description", "keys");
        $this->set(['name' => 'TestName', 'age' => 30]);
        $posts = \R::findAll('shop');
    }
}