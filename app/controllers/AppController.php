<?php
/**
 * Created by PhpStorm.
 * User: m4k5
 * Date: 2020-01-07
 * Time: 23:55
 */

namespace app\controllers;


use app\models\AppModel;
use shop\base\Controller;

class AppController extends Controller {

    public function __construct($route) {
        parent::__construct($route);
        new AppModel();
    }

}