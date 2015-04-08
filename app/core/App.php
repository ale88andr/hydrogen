<?php
class App
{
    protected $controller;

    protected $action;

    protected $params = [];

    static private $_layout;

    public $content;

    function __construct(){
        $url = $this->parseURL();

        if(file_exists(ROOT_APP . 'controllers' . DS . $url[0] . EXT)){
            $this->controller = array_shift($url);
            require_once ROOT_APP . 'controllers' . DS . $this->controller . EXT;
            $this->controller = new $this->controller;

            $this->action = (empty($url[0])) ? 'index' : array_shift($url);

            if(method_exists($this->controller, $this->action)){
                $this->params = $url;
                ob_start();
                call_user_func_array([$this->controller, $this->action], $this->params);
                $this->content = ob_get_clean();
            } else {
                echo 'Controller "' . get_class($this->controller) . '" does\'t have action "' . $this->action . '"';
            }
        } else {
            echo 'Controller "' . $url[0] . '" not found. Searched in: "' . ROOT_APP . 'controllers' . DS . '"';
        }

        if(empty(self::$_layout)) {
            self::setLayout();
        }
    }

    public static function setLayout($layout = false) {
        self::$_layout = ($layout) ? $layout : 'application';
    }

    private function getLayout() {
        return self::$_layout;
    }

    public function layout() {
        $layout_dir = ROOT_APP . 'views' . DS . 'layouts' . DS;
        $layout_file = self::getLayout() . EXT_VIEW;
        $layout =  $layout_dir . $layout_file; 
        if(file_exists($layout)) {
            return $layout;
        }
        else {
            throw new Exception("Layout {$layout_file} not found. Searched in {$layout_dir}"); 
        }
    }

    function parseURL(){
        if(isset($_GET['url'])){
            return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }
}