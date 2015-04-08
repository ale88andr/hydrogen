<?php
class App

{
    protected $controller, $action, $params = [];

    /**
     * Application html layout name
     *
     * @var string
     */
    static private $_layout;

    /**
     * Controller generated content
     *
     * @var string
     */
    public $content;

    function __construct()
    {
        try {
            $this->applyController($this->parseURL());
        }
        catch(Exception $e) {
            die($e->getMessage());
        }
        if (empty(self::$_layout)) {
            self::setLayout();
        }
    }

    /**
     * Controller handler by request params.
     *
     * @param array $url Request string (URI)
     * @return void
     */
    private function applyController($url)
    {
        $this->controller = array_shift($url);
        $controller_dir = ROOT_APP . 'controllers' . DS;
        $controller_path = $controller_dir . $this->controller . EXT;
        if (file_exists($controller_path)) {
            require_once $controller_path;

            $this->controller = new $this->controller;
            $this->action = (empty($url[0])) ? 'index' : array_shift($url);
            $this->applyControllerAction($url);
        }
        else {
            throw new Exception("Controller '{$this->controller}' not found. Searched in: '{$controller_dir}' <br />
                Search path: {$controller_path}");
        }
    }

    /**
     * Action handler by request params.
     *
     * @param array $url Part of request URI
     * @return void
     */
    private function applyControllerAction($url)
    {
        if (method_exists($this->controller, $this->action)) {
            $this->params = $url;
            ob_start();
            call_user_func_array([$this->controller, $this->action], $this->params);
            $this->content = ob_get_clean();
        }
        else {
            throw new Exception("Controller '" . get_class($this->controller) . "' does't have action '{$this->action}'");
        }
    }

    /**
     * Set application html layout.
     *
     * @param string $layout HTML layout name
     * @return void
     */
    public static function setLayout($layout = false)
    {
        self::$_layout = ($layout) ? $layout : 'application';
    }

    /**
     * Get application html layout.
     *
     * @return srting
     */
    private function getLayout()
    {
        return self::$_layout;
    }

    /**
     * Apply application html layout.
     *
     * @return string
     */
    public function layout()
    {
        $layout_dir = ROOT_APP . 'views' . DS . 'layouts' . DS;
        $layout_file = self::getLayout() . EXT_VIEW;
        $layout = $layout_dir . $layout_file;
        if (file_exists($layout)) {
            return $layout;
        }
        else {
            throw new Exception("Layout {$layout_file} not found. Searched in {$layout_dir}");
        }
    }

    /**
     * Parse request URI
     *
     * @return array
     */
    function parseURL()
    {
        if (isset($_GET['url'])) {
            return $url = explode('/', filter_var(rtrim($_GET['url'], '/') , FILTER_SANITIZE_URL));
        }
    }
}