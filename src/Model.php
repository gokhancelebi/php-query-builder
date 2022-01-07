<?php

namespace App;

# define relationships and table name for the model
class Model
{
    protected $query_type = "";
    protected $table = "";
    protected $limit = "";
    protected $offset = "";
    protected $orderBy = "";
    protected $order = "";
    protected $where = "";
    protected $join = "";
    protected $groupBy = "";
    protected $having = "";
    protected $select = [];
    protected $dataToBind = [];
    protected $last_query = "";
    protected $result = null;


    public function __construct()
    {
        $this->db = new DB();
    }

    # get last query
    public function lastQuery()
    {
        return $this->last_query;
    }

    # build query from values
    public function buildQuery()
    {
        if (count($this->select) == 0) {
            $this->select = "*";
        } else {
            $this->select = implode(",", $this->select);
        }

        if ($this->query_type == "") {
            $this->query_type = "SELECT";
        }

        if ($this->query_type == "DELETE"){
            $this->select = "";
        }

        $sql = "{$this->query_type} {$this->select} FROM {$this->table} {$this->join} {$this->where} {$this->groupBy} {$this->having} {$this->orderBy} {$this->limit} {$this->offset}";

        return $sql;
    }

    # add where clause to query
    public function where($param1, $param2 = null, $param3 = null)
    {
        if ($param2 == null) {
            foreach ($param1 as $key => $value) {
                $this->where .= " AND {$key} = :{$key}";
                $this->addDataTobind($key, $value);
            }
            return $this;
        }

        if ($param3 != null) {
            # add new where query to end of where
            $this->where .= " WHERE {$param1} {$param2} :{$param1}";
            $this->dataToBind[$param1] = $param3;

            return $this;
        }

        # add new where query to end of where
        $this->where .= " WHERE {$param1} = :{$param1}";
        $this->dataToBind[$param1] = $param2;

        return $this;

    }

    #clear query and values
    public function clearQuery()
    {
        $this->query_type = "";
        $this->table = "";
        $this->limit = "";
        $this->offset = "";
        $this->orderBy = "";
        $this->order = "";
        $this->where = "";
        $this->join = "";
        $this->groupBy = "";
        $this->having = "";
        $this->select = [];
        $this->dataToBind = [];
        $this->last_query = "";
        $this->result = null;
    }

    #add values to bind
    public function addDataTobind($key, $value)
    {
        $this->dataToBind[":" . $key] = $value;
    }

    # add select values
    public function addSelect($select)
    {
        if (is_array($select)) {
            foreach ($select as $value) {
                $this->select[] = $value;
            }
        } else {
            $this->select[] = $select;
        }
    }

    public function find($id)
    {
        $this->where("id", $id);
        return $this->first();
    }

    # get first record from query
    function first()
    {
        $this->limit(1);
        $sql = $this->buildQuery();
        $values = $this->dataToBind;
        $this->last_query = $sql;
//        $this->clearQuery();
        return $this->db->query($sql, $values)->fetch();
    }

    # add limit to the query
    public function limit($limit)
    {
        $this->limit = " LIMIT {$limit}";
        return $this;
    }

    public function run(){
        $sql = $this->buildQuery();
        $values = $this->dataToBind;
        $this->last_query = $sql;
        $this->clearQuery();
        return $this->db->query($sql, $values)->rowCount();
    }

    public function delete(){
        $this->query_type = "DELETE";
        return $this->run();
    }
}