<?php
class Validate {
    private $_isValid = false,
            $_errors = [],
            $_db = null;

    public function __construct() {
        $this->_db = Database::connect();
    }

    public function validates($source, $items_hash = []) {
        foreach ($items_hash as $item => $rules) {
            foreach ($rules as $rule => $options) {       
                $value = $source[$item];
                
                switch ($rule) {
                    case 'presence':
                        $this->presence($item, $value, $options);
                        break;

                    case 'length':
                        $this->length($item, $value, $options);
                        break;
                    
                    // case 'inclusion':
                    //     $this->inclusion($item, $value, $options);
                    //     break;

                    // case 'format':
                    //     $this->format($item, $value, $options);
                    //     break;

                    // case 'uniqueness':
                    //     $this->uniqueness($item, $value, $options);
                    //     break;

                    // case 'confirmation':
                    //     $this->confirmation($item, $value, $options);
                    //     break;

                    // case 'numericality':
                    //     $this->numericality($item, $value, $options);
                    //     break;

                    default:
                        # code...
                        break;
                }

            }
        }

        if(empty($this->_errors)){
            $this->_isValid = true;
        }

        return $this;
    }

    private function presence($item, $value, $option) {
        if($option === true && empty($value)){
            $this->addError("{$item} is required!");
        }
    }

    private function length($item, $value, $options = []) {
        foreach ($options as $param => $param_value) {
            switch ($param) {
                case 'in':
                    $this->lengthIn($item, $value, $param_value);
                    break;

                case 'is':
                    $this->lengthIs($item, $value, $param_value);
                    break;

                // case 'min':
                //     $this->lengthMin($item, $value, $param_value);
                //     break;

                // case 'max':
                //     $this->lengthMax($item, $value, $param_value);
                //     break;
                
                // default:
                //     # code...
                //     break;
            }
        }
    }

    private function lengthIn($item, $value, $range) {
        $range = explode('..', $range);
        $value_length = strlen($value);

        if($value_length < $range[0] || $value_length > $range[1] || empty($value)){
            $this->addError("{$item} length must be in {$range[0]} .. {$range[1]}!");
        }
    }

    private function lengthIs($item, $value, $param_value) {
        if($value !== $param_value || empty($value)) {
            $this->addError("{$item} length must be {$param_value}");
        }
    }

    public function isValid() {
        return $this->_isValid;
    }

    private function addError($error) {
        $this->_errors[] = $error;
    }

    public function errors() {
        return $this->_errors;
    }
}