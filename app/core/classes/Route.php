<?php
class Route

{
    public static function to($path = null, $params = null)
    {
        $routes = include (ROOT . DS . 'config' . DS . 'routes' . EXT);

        $path_items = explode(':', $path);
        switch (count($path_items)) {
        case 1:
            $controller = $path_items[0];
            if (array_key_exists($controller, $routes)) {
                $routes = $routes[$controller];
            }

            if (array_key_exists('resource', $routes)) {
                $controller = $routes['resource'];
            }

            header('Location: ' . ROOT_URL . $controller . (($params) ? ('/' . $params) : ''));
            exit;

        case 2:
            $controller = $path_items[1];
            $action = $path_items[0];
            if (array_key_exists($controller, $routes)) {
                $routes = $routes[$controller];
            }

            if (array_key_exists('resource', $routes)) {
                $controller = $routes['resource'];
            }

            if (array_key_exists('path_names', $routes) && array_key_exists($action, $routes['path_names'])) {
                $action = $routes['path_names'][$action];
            }

            if (array_key_exists('only', $routes) && !array_search($action, $routes['only'])) {
                echo "Error Request!";
                exit;
            }

            header('Location: ' . ROOT_URL . $controller . '/' . $action . (($params) ? ('/' . $params) : ''));
            exit;

        default:
            header("HTTP/1.0 404 Not Found");
            break;
        }
    }
}

?>