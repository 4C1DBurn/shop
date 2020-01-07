<?php

namespace shop;


class ErrorHandler {

    public function __construct() {

        if (DEBUG) {
            ini_set('display_errors', 'on');
            error_reporting(-1);
        } else {
            ini_set('display_errors', 'off');
            error_reporting(0);
        }
        set_error_handler([$this, 'errorHandler']);
        set_exception_handler([$this, 'exceptionHandler']);
        register_shutdown_function([$this, 'fatalErrorHandler']);
    }

     public function getErrorName($error) {
        $errors = [
            E_ERROR             => 'ERROR',
            E_WARNING           => 'WARNING',
            E_PARSE             => 'PARSE',
            E_NOTICE            => 'NOTICE',
            E_CORE_ERROR        => 'CORE_ERROR',
            E_CORE_WARNING      => 'CORE_WARNING',
            E_COMPILE_ERROR     => 'COMPILE_ERROR',
            E_COMPILE_WARNING   => 'COMPILE_WARNING',
            E_USER_ERROR        => 'USER_ERROR',
            E_USER_WARNING      => 'USER_WARNING',
            E_USER_NOTICE       => 'USER_NOTICE',
            E_STRICT            => 'STRICT',
            E_RECOVERABLE_ERROR => 'RECOVERABLE_ERROR',
            E_DEPRECATED        => 'DEPRECATED',
            E_USER_DEPRECATED   => 'USER_DEPRECATED',
        ];
        if(array_key_exists($error, $errors)){
            return $errors[$error] . " [$error]";
        }
        return $error;
    }

    public function errorHandler($errno, $errstr, $file, $line) {

        $this->displayError($errno, $errstr, $file, $line);

        return true;
    }

    public function exceptionHandler(\Exception $e) {
        $this->displayError(get_class($e), $e->getMessage(), $e->getFile(), $e->getLine(), 404));
        $this->logErrors($e->getMessage(), $e->getFile(), $e->getLine(), 404);

    }

    public function fatalErrorHandler()
    {
        $error = error_get_last();
        if ($error !== null) {
            $errorType = $error["type"];
            $errorFile = $error["file"];
            $errorLine = $error["line"];
            $errorMessage = $error["message"];
        $this->displayError($errorType, $errorFile, $errorLine, 500);
        }
    }

    protected function logErrors($message = '', $file = '', $line = '') {
        error_log("[" . date('Y-m-d H:i:s') . "] Error message:
         {$message}  | File: {$file} | On line {$line}\n=========\n",
            3, ROOT . '/tmp/errors.log');
//        if (($error !== null) && ($errorType === E_ERROR)) {
//            // fatal error has occured
//            $logfilename = dirname(__FILE__, 3) . '/storage/logs/error.log';
//            $logFile = fopen($logfilename, 'a+');
//            fprintf(
//                $logFile,
//                "[%s] %s: %s in %s:%d\n",
//                date("Y-m-d H:i:s"),
//                $errorType,
//                $errorMessage,
//                $errorFile,
//                $errorLine
//            );
//            fclose($logFile);
//        }
    }

    protected function displayError($errorno, $errstr, $errfile, $errline, $response = 404) {

        http_response_code($response);
        if ($response == 404 && !DEBUG){
            require WWW . '/errors/404.php';
            die;
        } elseif ($response == 500 && !DEBUG) {
            require WWW . '/errors/500.php';
            die;
        }
        if (DEBUG) {
            require WWW . '/errors/dev.php';
        } else {
            require WWW . '/errors/prod.php';
        }
        die;
    }

    function ob_end_clean_all() {
        $handlers = ob_list_handlers();
        while (count($handlers) > 0 && $handlers[count($handlers) - 1] != 'ob_gzhandler' && $handlers[count($handlers) - 1] != 'zlib output compression') {
            ob_end_clean();
            $handlers = ob_list_handlers();
        }
    }
}