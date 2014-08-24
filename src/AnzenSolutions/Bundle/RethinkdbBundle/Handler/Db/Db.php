<?php
namespace AnzenSolutions\Bundle\RethinkdbBundle\Services\Db;

class Db {
    public function __construct(Connect $conn){
        $this->conn = $conn;
    }
    
    public function _list(){
        return $this->conn->call("dbList", array(), true);
    }
}