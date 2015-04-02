<?php

    class Database
    {
        private static $_instance = null;
        private $dbh;
        private $_query,
                $_error = false,
                $_results,
                $_count = 0;
        
        private function __construct()
        {
            $dsn = $this->getDsn();
            try{
                $this->dbh = new PDO($dsn, Config::get('db:user'), Config::get('db:password'));
                // в продакшене прячем ошибку в логи
                //   try {  
                //      some wrong sql code here...
                // }  
                // catch(PDOException $e) {  
                //     echo "We have a problem.";  
                //     DEVELOPMENT_ENV ? file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND) : echo $e->getMessage();  
                // }
                $this->dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }

        private function getDsn(){
            switch (Config::get('db:driver')){
                case 'mysql':
                    $dsn = 'mysql:host=' . Config::get('db:host') . ';dbname=' . Config::get('db:database');
                    break;
                case 'sqlite':
                    $dsn = 'sqlite:' . Config::get('db:database');
                    break;
                case 'postgre':
                    $dsn = 'pgsql:dbname=' . Config::get('db:database') . ';host=' . Config::get('db:host');
                    break;
                default:
                    $dsn = null;
            }
            return $dsn;
        }

        public static function connect(){
            if(!isset(self::$_instance)){
                self::$_instance = new Database();
            }
            return self::$_instance;
        }

        public function query($sql, $params = [], $write = false){
            $this->_error = false;
            if($this->_query = $this->dbh->prepare($sql)){
                if(count($params)){
                    foreach ($params as $param => $param_value){
                        $this->_query->bindValue(is_string($param) ? ":{$param}" : ++$param, $param_value);
                    }
                }
                // TODO Remove!
                // if($this->_query->execute()){
                //     $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                //     $this->_count = $this->_query->rowCount();
                // } else {
                //     $this->_error = true;
                // }
                try {  
                    $this->_query->execute();
                    if(!$write){
                        $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                    }
                    $this->_count = $this->_query->rowCount();
                }  
                catch(PDOException $e) {  
                    echo "We have a problem with this request...";
                    $this->_error = true;
                    DEVELOPMENT_ENV ? print_r($e->getMessage()) : file_put_contents('error.log', $e->getMessage(), FILE_APPEND);  
                }
            }
            return $this;
        }

        public function results(){
            switch (count($this->_results)) {
                case '0':
                    return false;
                    break;

                case '1':
                    return $this->_results[0];
                    break;
                
                default:
                    return $this->_results;
                    break;
            }
            return $this->_results;
        }

        public function error(){
            return $this->_error;
        }

        public function row_count(){
            return $this->_count;
        }
    }