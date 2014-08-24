<?php
namespace AnzenSolutions\Bundle\RethinkdbBundle\Handler\Connect;

class Connect {
    public function __construct($arg1, $arg2, $arg3, $db, $host, $port, $key){
        $this->conn = array('blah'); // new \AnzenSolutions\Bundle\RethinkdbBundle($arg1, $arg2, $arg3, $db, $host, $port, $key);
    }
    
    public function conn(){
        return $this->conn;
    }
    
    public function getName(){
        return "connect";
    }
}