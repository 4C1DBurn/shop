<?php

namespace shop;


class ErrorHandler {

    public function __construct()
    {
        if (DEBUG){
            error_reporting(-1);
            ini_set('display_errors', 'on');
        } else {
            error_reporting(0);
            ini_set('display_errors', 'off');
        }
        set_exception_handler([$this, 'exeptionHandler']);
    }

    public function exeptionHandler($e){
        $this->logErrors($e->getMessage(), $e->getFile(), $e->getLine());
        $this->displayErrors('Exeption', $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode());
    }

    protected function logErrors($message = '', $file = '', $line = ''){
        error_log("[" . date('Y-m-d H:i:s') . "] Text error: {$message} | File: {$file} | Line: {$line} 
        \n====================='=\n", 3, ROOT . '/tmp/errors.log');
    }

    protected function displayErrors($errorNumber, $errorMessage, $errorFile, $errorLine, $responce = 404){
        http_response_code($responce);
        if ($responce == 404 && !DEBUG){
            require WWW . '/errors/404.php';
            die();
        }
        if (DEBUG){
            require WWW . '/errors/dev.php';
        } else {
            require WWW . '/errors/prod.php';
        }
        die();
    }
}