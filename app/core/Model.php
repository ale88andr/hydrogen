<?php

class Model
{
    public $db = null; // current connection
    protected $table; // current table

    function __construct(){
        $this->db = Database::connect();
        $this->table = mb_strtolower(get_class($this)) . 's';
    }

    // wrapper for Database::query(), return: Database::results()
    private function send_query($sql, $values = []){
        $this->db->query($sql, $values);
        return $this->db->results();
    }

    // return: (array)all records from $this->table. Example: $user->all(['login', 'created_at']);
    public function all($select = false){
        $sql = 'SELECT ' . $this->set_fields($select) . ' FROM ' . $this->table;
        return $this->send_query($sql);
    }

    // return (array|obj)record(s) from $this->table with WHERE constraint. Example: $users->find(['login' => $login, 'name' => 'user1'], ['login', 'created_at']);
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

    // return: (obj)record from $this->table by id. Example: $users->find_by_id(1, ['login', 'created_at']);
    public function find_by_id($id, $select = false) {
        if(is_integer($id)){
            $sql = 'SELECT ' . $this->set_fields($select) . ' FROM ' . $this->table . ' WHERE id = ?';
            return $this->send_query($sql, [$id]);
        } else {
            $this->_error = 'Can\'t find row by id = "' . $id . '" in ' . $this->table . 'table!';
        }
    }

    // return: (str)part of query with list of columns or '*'(all)
    private function set_fields($fields){
        return count($fields) > 0 ? implode(',', $fields) : '*';
    }
}