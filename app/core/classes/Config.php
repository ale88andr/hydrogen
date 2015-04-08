<?php
class Config

{
    protected static $config = [];

    private static function init()
    {
        $db_cnf = ROOT . DS . 'config' . DS . 'database.yaml';
        if (file_exists($db_cnf)) {
            $config = yaml_parse_file($db_cnf);
            if (!DEVELOPMENT_ENV) {
                self::$config['db'] = $config['production'];
            }
            else {
                self::$config['db'] = $config['development'];
            }
        }
    }

    public static function get($path = null)
    {
        if (empty(self::$config)) {
            self::init();
        }
        if ($path) {
            $config = self::$config;
            $path = explode(':', $path);
            foreach($path as $bit) {
                if (isset($config[$bit])) {
                    $config = $config[$bit];
                }
            }
            return $config;
        }
        return false;
    }
}