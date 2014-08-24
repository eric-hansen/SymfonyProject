<?php
namespace AnzenSolutions\Bundle\RethinkdbBundle\Handler;

class Db {
    public $query;
    
    public function __construct(\AnzenSolutions\Bundle\RethinkdbBundle\Handler\Connect &$conn){
        $this->conn = $conn;
        $this->query = $conn->query;
    }
    
    public function getConn($rconn=false){
        return $this->conn->getConn($rconn);
    }
    
    public function _list(){
        return $this->conn->call("dbList", array(), true);
    }
    
    public function _use($newDb){
        return $this->conn->call("useDb", $newDb, true);
    }
    
    public function create($dbName){
        return $this->conn->call("dbCreate", $dbName, true);
    }
    
    public function drop($dbName){
        return $this->conn->call("dbDrop", $dbName, true);
    }
}