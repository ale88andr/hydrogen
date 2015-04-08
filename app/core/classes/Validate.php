<?php
/**
 * Validate is a class which checks values by rules
 * Validate form, input values,
 *
 */
class Validate

{
    /**
     * isValid validation flag
     * @var bool
     *
     * errors container
     * @var array
     *
     * db instance of database connection
     * @var obj
     */
    private $_isValid = false, $_errors = [], $_db = null;

    public function __construct()
    {
        $this->_db = Database::connect();
    }

    /**
     * A main validates handler.
     *
     * @param array $source         User input params
     * @param array $items_hash     Validation rules with params
     * @return obj
     * @api
     */
    public function validates($source, $items_hash = [])
    {
        foreach($items_hash as $item => $rules) {
            foreach($rules as $rule => $options) {
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

                    // code...

                    break;
                }
            }
        }

        if (empty($this->_errors)) {
            $this->_isValid = true;
        }

        return $this;
    }

    /**
     * Validates presence item value.
     * Example: $obj = new Validate;
     *          $result = $obj->validates($_POST, ['item' => ['presence' => true]];
     *          if($result->isValid()) {# code here ...}
     *
     * @param mixed $item     Validation field name
     * @param mixed $value    Validation field value
     * @param bool $option    Enable or disable validation flag
     * @return void
     */
    private function presence($item, $value, $option = false)
    {
        if ($option === true && empty($value)) {
            $this->addError("'{$item}' is required!");
        }
    }

    /**
     * Validates length item value.
     * Examples: $obj = new Validate;
     *           $result = $obj->validates($_POST, ['item' => ['length' => ['minimum' => 3, 'maximum' => 50]]];
     *           or
     *           $result = $obj->validates($_POST, ['item' => ['length' => ['in' => '3..50']]];
     *           or
     *           $result = $obj->validates($_POST, ['item' => ['length' => ['is' => '15']]];
     *           if($result->isValid()) {# code here ...}
     *
     * @param mixed $item     Validation field name
     * @param mixed $value    Validation field value
     * @param array $options  Parametrs for validate length
     * @return void
     */
    private function length($item, $value, $options = [])
    {
        foreach($options as $param => $param_value) {
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

                // code...

                break;
            }
        }
    }

    /**
     * Helper for length method. Validates length range.
     *
     * @param mixed $item     Validation field name
     * @param mixed $value    Validation field value
     * @param string $option  Range of length
     * @return void
     */
    private function lengthIn($item, $value, $range)
    {
        $range = explode('..', $range);
        $value_length = strlen($value);
        if ($value_length < $range[0] || $value_length > $range[1] || empty($value)) {
            $this->addError("'{$item}' length must be in {$range[0]} .. {$range[1]}!");
        }
    }

    /**
     * Helper for length method. Validates static length.
     *
     * @param mixed $item   Validation field name
     * @param int $value  Validation field value
     * @param int $length   Specifies the length
     * @return void
     */
    private function lengthIs($item, $value, $length)
    {
        if (strlen($value) !== $length || empty($value)) {
            $this->addError("'{$item}' length must be {$length}");
        }
    }

    /**
     * Helper for length method. Validates minimum allowed length.
     *
     * @param mixed $item   Validation field name
     * @param int $value  Validation field value
     * @param int $length   Specifies the minimum length
     * @return void
     */
    private function lengthMin($item, $value, $length)
    {
        if (strlen($value) < $length || empty($value)) {
            $this->addError("'{$item}' minimum length must be bigger than {$length}");
        }
    }

    /**
     * Helper for length method. Validates maximum allowed length.
     *
     * @param mixed $item   Validation field name
     * @param int $value  Validation field value
     * @param int $length   Specifies the maximum length
     * @return void
     */
    private function lengthMax($item, $value, $length)
    {
        if (strlen($value) > $length || empty($value)) {
            $this->addError("'{$item}' minimum length must be less than {$length}");
        }
    }

    /**
     * Validates predetermined values.
     * Example: $obj = new Validate;
     *          $result = $obj->validates($_POST, ['item' => ['inclusion' => ['item1', 'item2', 'item3']]];
     *          if($result->isValid()) {# code here ...}
     *
     * @param mixed $item               Validation field name
     * @param mixed $value              Validation field value
     * @param array $predetermined      Predetermined hash values
     * @return void
     */
    private function inclusion($item, $value, $predetermined)
    {
        if (!in_array($value, $predetermined) || empty($value)) {
            $this->addError("'{$item}' must be in one of: " . implode(', ', $predetermined));
        }
    }

    /**
     * Validates values with regexp.
     * Example: $obj = new Validate;
     *          $result = $obj->validates($_POST, ['item' => ['format' => '/A([^@s]+)@((?:[-a-z0-9]+.)`[a-z]{2,})z/i']];
     *          if($result->isValid()) {# code here ...}
     *
     * @param mixed $item         Validation field name
     * @param mixed $value        Validation field value
     * @param string $regexp      Regular expression
     * @return void
     */
    private function format($item, $value, $regexp)
    {
        if (!preg_match($regexp, $value)) {
            $this->addError("'{$item}' not be in right format!");
        }
    }

    /**
     * Validates that value is numeric.
     * Example: $obj = new Validate;
     *          $result = $obj->validates($_POST, ['item' => ['numericality' => true]];
     *          if($result->isValid()) {# code here ...}
     *
     * @param mixed $item     Validation field name
     * @param mixed $value    Validation field value
     * @param bool $option    Enable or disable validation flag
     * @return void
     */
    private function numericality($item, $value, $option)
    {
        if (!is_int($value) && $option === true) {
            $this->addError("'{$item}' must be integer.");
        }
    }

    /**
     * Validates that value in item equals value in item_confirmation.
     * Example: $obj = new Validate;
     *          $result = $obj->validates($_POST, ['item' => ['confirmation' => true]];
     *          if($result->isValid()) {# code here ...}
     *
     * @param mixed $item     Validation field name
     * @param mixed $value    Validation field value
     * @param mixed $value_confirmation    Validation field_confirmation value
     * @param bool $option    Enable or disable validation flag
     * @return void
     */
    private function confirmation($item, $value, $value_confirmation, $option)
    {
        if ($value !== $value_confirmation && $option === true) {
            $this->addError("'{$item}' not equals '{$item}_confirmation'");
        }
    }

    /**
     * Validates uniqueness of value.
     * Example: $obj = new Validate;
     *          $result = $obj->validates($_POST, ['item' => ['uniqueness' => 'items']];
     *          if($result->isValid()) {# code here ...}
     *
     * @param mixed $item     Validation field name
     * @param mixed $value    Validation field value
     * @param string $table   Database table where item check uniqueness
     * @return void
     */
    private function uniqueness($item, $value, $table)
    {
        $unique = $this->_db->query("SELECT * FROM {$table} WHERE {$item} = '{$value}'");
        if ($unique->row_count()) {
            $this->addError("{$item} '{$value}' already exists in database.");
        }
    }

    /**
     * Return validation flag.
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->_isValid;
    }

    /**
     * Add error in _error array.
     *
     * @return void
     */
    private function addError($error)
    {
        $this->_errors[] = $error;
    }

    /**
     * Return validation error(s).
     *
     * @return array
     */
    public function errors()
    {
        return $this->_errors;
    }
}