<?php
class Model

{
    /**
     * Database connection (Singletone)
     *
     * @var Database::connect()
     */
    public $db = null;

    /**
     * Current table
     *
     * @var Model class name
     */
    protected $table;

    function __construct()
    {
        $this->db = Database::connect();
        $this->table = mb_strtolower(get_class($this)) . 's';
    }

    /**
     * A wrapper for Database::query().
     *
     * @param string $sql       The (PDO)SQL instructions
     * @param array $values     Values for PDO statement
     * @param boolean $write    Type of query (READ or WRITE)
     * @return mixed OR int
     */
    private function send_query($sql, $values = [], $write = false)
    {
        $this->db->query($sql, $values, $write);
        if ($write) {
            return $this->db->row_count();
        }
        else {
            return $this->db->results();
        }
    }

    /**
     * Method gets all records from table.
     * Example: $user->all(['login', 'created_at']);
     *
     * @param array $select  Columns for select(default:false[all table columns])
     * @return mixed
     */
    public function all($select = false)
    {
        $sql = 'SELECT ' . $this->set_fields($select) . ' FROM ' . $this->table;
        return $this->send_query($sql);
    }

    /**
     * Find record(s) by constraint.
     * Example: $users->find(['login' => $login, 'name' => 'user1'], ['login', 'created_at']);
     *
     * @param string $constraint    SQL Where constraint
     * @param array $select         Columns for select(default:false[all table columns])
     * @return mixed
     */
    public function find($constraint, $select = false)
    {
        if (is_array($constraint)) {
            $columns = array_keys($constraint);
            $values = array_values($constraint);
            $sql = 'SELECT ' . $this->set_fields($select) . ' FROM ' . $this->table . ' WHERE ';
            foreach($columns as $index => $column) {
                if ($index > 0) {
                    $sql.= ' AND ' . $column . ' = ?';
                }
                else {
                    $sql.= $column . ' = ?';
                }
            }

            return $this->send_query($sql, $values);
        }
        elseif (is_integer($constraint)) {
            return $this->find_by_id($constraint, $select);
        }
        else {
            $this->_error = 'Undefined constraint ' . $constraint . ' for find';
        }
    }

    /**
     * Find record(s) by id.
     * Example: $users->find_by_id(1, ['login', 'created_at']);
     *
     * @param string $id        id constraint
     * @param array $select     Columns for select(default:false[all table columns])
     * @return mixed
     */
    public function find_by_id($id, $select = false)
    {
        if (is_integer($id)) {
            $sql = 'SELECT ' . $this->set_fields($select) . ' FROM ' . $this->table . ' WHERE id = ?';
            return $this->send_query($sql, [$id]);
        }
        else {
            $this->_error = "Can't find row by id = '{$id}' in '{$this->table}' table.";
        }
    }

    /**
     * Return part of query with list of columns or ' * '(all)
     *
     * @param array $select     Columns for select
     * @return string
     */
    private function set_fields($fields)
    {
        return count($fields) > 0 ? implode(', ', $fields) : ' * ';
    }

    /**
     * Insert row.
     * Example: $user->insert(['login' => $login])
     *
     * @param array $hash_values  Inserted values [column => value, ...]
     * @return int
     */
    public function insert($hash_values)
    {
        if(count($hash_values)){
            $columns = array_keys($hash_values);
            $values = '';
            $i = 1;
            foreach ($hash_values as $key => $value) {
                $values .= ":{$key}";
                if($i < count($hash_values)){
                    $values .= ', ';
                }

                $i++;
            }

            $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") VALUES ({$values})";
            return $this->send_query($sql, $hash_values, true);
        } else {
            $this->_error = 'Callerror: insert parameters must be array !';
        }
    }

    /**
     * Update row(s).
     * Example: $user->update($id, ['login' => $login])
     *
     * @param string $constraint  SQL Where constraint
     * @param array $hash_values  Inserted values [column => value, ...]
     * @return int
     */
    public function update($constraint, $hash_values)
    {
        if(count($hash_values)){
            $where = array_keys($constraint);
            $values = '';
            $i = 1;
            foreach ($hash_values as $key => $value) {
                $values .= "{$key} = :{$key}";
                if($i < count($hash_values)){
                    $values .= ', ';
                }

                $i++;
            }

            $sql = "UPDATE {$this->table} SET {$values} WHERE ";
            foreach ($where as $index => $column) {
                if($index > 0){
                    $sql .= " AND {$column} = :{$column}";
                } else {
                    $sql .= "{$column} = :{$column}";
                }
            }

            print_r($sql);
            return $this->send_query($sql, array_merge($hash_values, $constraint), true);
        } else {
            $this->_error = 'Callerror: insert parameters must be array !';
        }
    }
}