<?php
/**
 * Created by PhpStorm.
 * User: m4k5
 * Date: 2020-01-08
 * Time: 00:03
 */

namespace shop\base;


class View {

    public $route;
    public $controller;
    public $view;
    public $model;
    public $prefix;
    public $layout;
    public $data = [];
    public $meta = [];

    public function __construct($route, $layout = '', $view ='', $meta = '') {

        $this->route = $route;
        $this->controller = $route['controller'];
        $this->view = $view;
        $this->model = $route['controller'];
        $this->prefix = $route['prefix'];
        $this->meta = $meta;

        if ($layout === false) {
            $this->layout = false;
        } else {
            $this->layout = $layout ?: LAYOUT;
        }
    }

    public function render($data){
        if (is_array($data)) extract($data);
         $viewFile = APP . "/views/{$this->prefix}{$this->controller}/{$this->view}.php";
        if (is_file($viewFile)){
            ob_start();
            require_once $viewFile;
            $content = ob_get_clean();
        } else {
            throw new \Exception("View: {$viewFile}, not found", 500);
        }
        if (false != $this->layout) {
            $layuotFile = APP . "/views/layouts/{$this->layout}.php";
            if (is_file($layuotFile)) {
                require_once $layuotFile;
            } else {
                throw new \Exception("Layout: {$this->layout}, not found", 500);
            }
        }
    }

    public function getMeta(){
        $meta = '<meta name="description" content="' . $this->meta['desc'] . '">' . PHP_EOL;
        $meta .= '<meta name="keywords" content="' . $this->meta['keywords'] . '">' . PHP_EOL;
        $meta .= '<title>' . $this->meta['title'] . '</title>' . PHP_EOL;
        return $meta;
    }

}