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
                    
                    case 'inclusion':
                        $this->inclusion($item, $value, $options);
                        break;

                    case 'format':
                        $this->format($item, $value, $options);
                        break;

                    case 'uniqueness':
                        $this->uniqueness($item, $value, $options);
                        break;

                    case 'confirmation':
                        $this->confirmation($item, $value, $source[$item . '_confirmation'], $options);
                        break;

                    case 'numericality':
                        $this->numericality($item, $value, $options);
                        break;

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
            $this->addError("'{$item}' is required!");
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

                case 'minimum':
                    $this->lengthMin($item, $value, $param_value);
                    break;

                case 'maximum':
                    $this->lengthMax($item, $value, $param_value);
                    break;
                
                default:
                    # code...
                    break;
            }
        }
    }

    private function lengthIn($item, $value, $range) {
        $range = explode('..', $range);
        $value_length = strlen($value);

        if($value_length < $range[0] || $value_length > $range[1] || empty($value)){
            $this->addError("'{$item}' length must be in {$range[0]} .. {$range[1]}!");
        }
    }

    private function lengthIs($item, $value, $length) {
        if(strlen($value) !== $length || empty($value)) {
            $this->addError("'{$item}' length must be {$length}");
        }
    }

    private function lengthMin($item, $value, $length) {
        if(strlen($value) < $length || empty($value)) {
            $this->addError("'{$item}' minimum length must be bigger than {$length}");
        }
    }

    private function lengthMax($item, $value, $length) {
        if(strlen($value) > $length || empty($value)) {
            $this->addError("'{$item}' minimum length must be less than {$length}");
        }
    }

    private function inclusion($item, $value, $options) {
        if(!in_array($value, $options) || empty($value)) {
            $this->addError("'{$item}' must be in one of: " . implode(', ', $options));
        }
    }

    private function format($item, $value, $regexp) {
        if(!preg_match($regexp, $value)) {
            $this->addError("'{$item}' not be in right format!");
        }
    }

    private function numericality($item, $value, $option) {
        if(!is_int($value) && $option === true) {
            $this->addError("'{$item}' must be integer.");
        }
    }

    private function confirmation($item, $value, $value_confirmation, $option) {
        if($value !== $value_confirmation && $option === true) {
            $this->addError("'{$item}' not equals '{$item}_confirmation'");
        }
    }

    private function uniqueness($item, $value, $options) {
        $unique = $this->_db->query("SELECT * FROM {$options} WHERE {$item} = '{$value}'");
        if($unique->row_count()){
            $this->addError("{$item} '{$value}' already exists in database.");
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