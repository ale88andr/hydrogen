<?php

class Model
{
    public $db = null;
    protected $table;

    function __construct(){
        $this->db = Database::connect();
        $this->table = mb_strtolower(get_class($this)) . 's';
    }

    private function send_query($sql, $values = []){
        $this->db->query($sql, $values);
        return $this->db->results();
    }

    // $user->all(['login', 'created_at']);
    public function all($select = false){
        $sql = 'SELECT ' . $this->set_fields($select) . ' FROM ' . $this->table;
        return $this->send_query($sql);
    }

    // $users->find(['login' => $login, 'name' => 'user1'], ['login', 'created_at']);
    public function find($constraint, $select = false){
        if(is_array($constraint)){
            $columns = array_keys($constraint);
            $values = array_values($constraint);
            $sql = 'SELECT ' . $this->set_fields($select) . ' FROM ' . $this->table . ' WHERE ';
            foreach ($columns as $index => $column) {
                if($index > 0){
                    $sql .= ' AND ' . $column . ' = ?';
                } else {
                    $sql .= $column . ' = ?';
                }
            }
            return $this->send_query($sql, $values);
        } elseif(is_integer($constraint)) {
            return $this->find_by_id($constraint, $select);
        } else {
            $this->_error = 'Undefined constraint ' . $constraint . ' for find';
        }
    }

    // $users->find_by_id(1);
    public function find_by_id($id, $select = false) {
        if(is_integer($id)){
            $sql = 'SELECT ' . $this->set_fields($select) . ' FROM ' . $this->table . ' WHERE id = ?';
            return $this->send_query($sql, [$id]);
        } else {
            $this->_error = 'Can\'t find row by id = "' . $id . '" in ' . $this->table . 'table!';
        }
    }

    private function set_fields($fields){
        if(count($fields) > 0) {
            $fields = implode(',', $fields);
        } else {
            $fields = '*';
        }
        return $fields;
    }
}