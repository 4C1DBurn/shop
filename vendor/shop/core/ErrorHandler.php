<?php

namespace shop;


class ErrorHandler {

    public function __construct() {

        if(DEBUG){
            ini_set('display_errors', 'on');
            error_reporting(-1);
        } else {
            ini_set('display_errors', 'off');
            error_reporting(0);
        }

        set_error_handler('exeptionHandler');
        //set_exception_handler('exceptionHandler');
        //register_shutdown_function('fatalErrorHandler');
    }

    public function exceptionHandler($e) {

        $this->logErrors($e->getMessage(), $e->getFile(), $e->getLine(), 404);
        $this->displayError('Exeption', $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode());

    }

    public function fatalErrorHandler() {

        if ($error = error_get_last() AND $error['type'] & (E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR)) {
            ob_end_clean();
            $this->displayError($error['type'], $error['message'], $error['file'], $error['line'], 500);
        }
    }

    protected function logErrors($message = '', $file = '', $line = '') {
        error_log("[" . date('Y-m-d H:i:s') . "] Error message:
         {$message}  | File: {$file} | On line {$line}\n=========\n",
            3, ROOT . '/tmp/errors.log');
    }

    protected function displayError($errorno, $errstr, $errfile, $errline, $response = 404) {

        http_response_code($response);
        if ($response == 404 && !DEBUG){
            require WWW . '/errors/404.php';
            die;
        }
        if (DEBUG) {
            require WWW . '/errors/dev.php';
        } else {
            require WWW . '/errors/prod.php';
        }
        die;
    }
}