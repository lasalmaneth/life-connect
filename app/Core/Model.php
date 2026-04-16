<?php

namespace App\Core;

trait Model
{

    use Database {
        insert as DatabaseInsert;
    }


    protected $limit = 10;
    protected $offset = 0;
    protected $order_type = 'desc';
    protected $order_column = "id";
    public $errors = [];
    private static $whereCount = 0; // Global counter for unique param names


    public function findAll($columns = "*", $order = "", $table = "")
    {
        if (is_array($columns)) $columns = implode(',', $columns);
        
        $table = !empty($table) ? $table : $this->table;
        $order = !empty($order) ? $order : "$this->order_column $this->order_type";
        $query = "select $columns from $table order by $order limit $this->limit offset $this->offset";

        return $this->query($query);
    }

    public function where($data, $data_not = [], $columns = "*", $order = "", $table = "")
    {
        if (is_array($columns)) $columns = implode(',', $columns);
        $table = !empty($table) ? $table : $this->table;
        
        $query = "select $columns from $table ";
        $params = [];
        
        if (!empty($data) || !empty($data_not)) {
            $query .= "where ";
            $this->applyWhere($data, $params, $query);
            if (!empty($data_not)) {
                $query .= (!empty($data) ? " && " : "");
                foreach ($data_not as $key => $value) {
                    $this->applyWhere([$key . " !=" => $value], $params, $query, false);
                    $query .= " && ";
                }
                $query = trim($query, " && ");
            }
        }

        $order = !empty($order) ? $order : "$this->order_column $this->order_type";
        $query .= " order by $order limit $this->limit offset $this->offset";

        return $this->query($query, $params);
    }

    public function first($data, $data_not = [], $columns = "*", $order = "", $table = "")
    {
        if (is_array($columns)) $columns = implode(',', $columns);
        $table = !empty($table) ? $table : $this->table;
        
        $query = "select $columns from $table ";
        $params = [];

        if (!empty($data) || !empty($data_not)) {
            $query .= "where ";
            $this->applyWhere($data, $params, $query);
            if (!empty($data_not)) {
                $query .= (!empty($data) ? " && " : "");
                foreach ($data_not as $key => $value) {
                    $this->applyWhere([$key . " !=" => $value], $params, $query, false);
                    $query .= " && ";
                }
                $query = trim($query, " && ");
            }
        }

        $order = !empty($order) ? $order : "$this->order_column $this->order_type";
        $query .= " order by $order limit 1 offset 0";

        $result = $this->query($query, $params);
        if ($result) return $result[0];
        return false;
    }

    /**
     * Flexible query builder with joins
     * $joins = [['table' => 'donors', 'on' => 'cis.donor_id = donors.id', 'type' => 'LEFT']]
     */
    public function queryJoin($joins = [], $where = [], $columns = "*", $order = "", $limit = 10, $offset = 0, $table = "", $params = []) {
        if (is_array($columns)) $columns = implode(',', $columns);
        
        $table = !empty($table) ? $table : $this->table;
        $query = "select $columns from $table ";
        
        foreach ($joins as $j) {
            $type = strtoupper($j['type'] ?? 'JOIN');
            if (($type === 'LEFT' || $type === 'RIGHT') && !str_contains($type, 'JOIN')) {
                $type .= ' JOIN';
            }
            $query .= "$type {$j['table']} ON {$j['on']} ";
        }

        $data = $params;
        if (!empty($where)) {
            $query .= "WHERE ";
            $this->applyWhere($where, $data, $query);
        }

        if (!empty($order)) {
            $query .= " ORDER BY $order ";
        }

        if ($limit) {
            $query .= " LIMIT $limit OFFSET $offset";
        }

        return $this->query($query, $data);
    }

    public function count($where = [], $data_not = [], $table = "")
    {
        $table = !empty($table) ? $table : $this->table;
        $query = "select count(*) as count from $table ";
        $params = [];

        if (!empty($where) || !empty($data_not)) {
            $query .= "where ";
            $this->applyWhere($where, $params, $query);
            if (!empty($data_not)) {
                $query .= (!empty($where) ? " && " : "");
                foreach ($data_not as $key => $value) {
                    $this->applyWhere([$key . " !=" => $value], $params, $query, false);
                    $query .= " && ";
                }
                $query = trim($query, " && ");
            }
        }

        $result = $this->query($query, $params);

        if ($result) {
            return (int)$result[0]->count;
        }
        return 0;
    }

    /**
     * Helper to apply WHERE conditions with operator support
     */
    private function applyWhere($where, &$params, &$query, $includeWhere = false) {
        if (empty($where)) return;
        
        foreach ($where as $key => $value) {
            if (is_numeric($key)) {
                $query .= " $value && ";
            } else {
                self::$whereCount++;
                $parts = explode(" ", trim($key));
                $column = $parts[0];
                $operator = strtoupper(count($parts) > 1 ? $parts[1] : "=");
                
                if (($operator === 'IN' || $operator === 'NOT IN') && is_string($value) && strpos(trim($value), "(") === 0) {
                    $query .= "$column $operator $value && ";
                } else {
                    $cleanKey = str_replace('.', '_', $column);
                    $paramName = "w" . self::$whereCount . "_" . $cleanKey;
                    
                    $query .= "$column $operator :$paramName && ";
                    $params[$paramName] = $value;
                }
            }
        }
        $query = trim($query, " && ");
    }

    public function insert($data, $table = "")
    {
        // Support both calling styles:
        // 1) insert(['col' => 'val'], 'table_name')  (Model helper)
        // 2) insert('INSERT ...', [':param' => 'val']) (Database helper signature)
        if (is_string($data)) {
            $query = $data;
            $params = is_array($table) ? $table : [];
            return $this->DatabaseInsert($query, $params);
        }

        $table = !empty($table) ? $table : $this->table;

        // Filter by allowedColumns only for the primary table
        if ($table === $this->table && !empty($this->allowedColumns)) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }

        if (empty($data)) return false;

        $keys = array_keys($data);
        $query = "insert into $table (" . implode(",", $keys) . ") values (:" . implode(",:", $keys) . ")";

        return $this->DatabaseInsert($query, $data);
    }

    public function delete($id, $id_column = 'id', $table = "")
    {
        $table = !empty($table) ? $table : $this->table;
        $data[$id_column] = $id;
        $query = "delete from $table where $id_column = :$id_column ";
        $this->query($query, $data);
        return false;
    }

    public function deleteWhere($where, $table = "")
    {
        $table = !empty($table) ? $table : $this->table;
        $query = "delete from $table ";
        $params = [];

        if (!empty($where)) {
            $query .= " WHERE ";
            $this->applyWhere($where, $params, $query);
        }

        return $this->query($query, $params);
    }

    public function update($id, $data, $id_column = 'id', $table = "")
    {
        $table = !empty($table) ? $table : $this->table;

        if ($table === $this->table && !empty($this->allowedColumns)) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }
        
        if (empty($data)) return false;

        $keys = array_keys($data);
        $query = "update $table set ";

        foreach ($keys as $key) {
            $query .= $key . " = :" . $key . ", ";
        }

        $query = trim($query, ", ");
        $query .= " where $id_column = :$id_column";
        $data[$id_column] = $id;
        return $this->query($query, $data);
    }

    public function max($column, $where = [], $table = "")
    {
        $table = !empty($table) ? $table : $this->table;
        $query = "select max($column) as m from $table ";
        $params = [];

        if (!empty($where)) {
            $query .= "where ";
            $this->applyWhere($where, $params, $query);
        }

        $result = $this->query($query, $params);
        return $result ? $result[0]->m : null;
    }

    public function min($column, $where = [], $table = "")
    {
        $table = !empty($table) ? $table : $this->table;
        $query = "select min($column) as m from $table ";
        $params = [];

        if (!empty($where)) {
            $query .= "where ";
            $this->applyWhere($where, $params, $query);
        }

        $result = $this->query($query, $params);
        return $result ? $result[0]->m : null;
    }

    public function updateWhere($data, $where, $table = "") {
        $table = !empty($table) ? $table : $this->table;
        
        // Only filter by allowedColumns if we are updating the default table
        if ($table === $this->table && !empty($this->allowedColumns)) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }

        if (empty($data)) return false; // Avoid syntax error with empty set

        $query = "update $table set ";
        $params = [];

        foreach ($data as $key => $value) {
            $query .= "$key = :set_$key, ";
            $params["set_$key"] = $value;
        }
        $query = trim($query, ", ");

        if (!empty($where)) {
            $query .= " WHERE ";
            $this->applyWhere($where, $params, $query);
        }

        return $this->query($query, $params);
    }
}