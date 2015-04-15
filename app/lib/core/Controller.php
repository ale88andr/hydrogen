<?php

namespace core;

class Controller

{
    public function model($model_name = '')
    {
        $model_name = empty($model_name) ? get_class($this) : trim(strtolower($model_name));
        if (file_exists(ROOT_APP . 'models' . DS . $model_name . EXT)) {
            require_once ROOT_APP . 'models' . DS . $model_name . EXT;

            return new $model_name;
        }
        else {
            echo 'Model "' . $model_name . '" not found. Searched in: "' . ROOT_APP . 'models' . DS . '"';
        }
    }

    public function render($partial, $data = [], $handle_data = true)
    {
        if ($handle_data === true) {
            foreach($data as $varname => $value) {
                $ {
                    $varname
                } = $value;
            }

            unset($data);
        }

        $path = $this->getPartialPath($partial);
        if (file_exists(ROOT_APP . 'views' . DS . $path['partial_dir'] . DS . $path['partial'])) {
            require_once ROOT_APP . 'views' . DS . $path['partial_dir'] . DS . $path['partial'];

        }
        else {
            echo 'Partial "' . $path['partial'] . '" not found. Searched in: "' . ROOT_APP . 'views' . DS . $path['partial_dir'] . DS . '"';
        }
    }

    private function getPartialPath($partial)
    {
        $path = [];
        $partial = ltrim($partial, '/') . EXT_VIEW;
        if (!strpos($partial, '/')) {
            $path['partial_dir'] = strtolower(get_class($this));
            $path['partial'] = $partial;
        }
        else {
            $tmp = explode('/', $partial);
            $path['partial'] = array_pop($tmp);
            $path['partial_dir'] = join($tmp);
        }

        return $path;
    }
}