<?php

namespace shop;


class Db {

    use TSingletone;

    protected function __construct() {

        $db = require_once CONF . '/db_config.php';
        class_alias('\RedBeanPHP\R','\R');
        \R::setup($db['dsn'], $db['user'], $db['password']);
        if ( !\R::testConnection() ) {
            throw new \Exception("Failed connect to DB", 503);
        }
        \R::freeze(true);
        if (DEBUG) {
            \R::debug(true,1);
        }
    }
}