<?php
namespace AnzenSolutions\Bundle\RethinkdbBundle\Handler;

use r;

class Connect {
    public $query;
    
    public function __construct($db, $host='localhost', $port=28015, $auth_key="",$timeout=5){
        if(!isset($this->conn))
            $this->conn = r\connect($host, $port, $db, $auth_key, $timeout);
        
        if(!isset($this->tbl))
            $this->tbl = "";
        
        $this->query = null;
    }
    
    /**
     * Used mainly for some trickier stuff like DateTime.
     *
     * Clones the current class object, resets the table and query chain and returns it.
     */
    public function newConn(){
        $tmp = clone $this;
        $tmp->tbl = "";
        $tmp->query = null;
        $tmp->conn = $this->conn;
        
        return $tmp;
    }
    
    public function table($tbl){
        /**
         * Ifn o table was previously set, set it now.
         * Save current state after establishing table for when run() is called.
         */
        if(empty($this->tbl)){
            $this->tbl = $tbl;
            $this->query = r\table($tbl);
            $this->old_state = $this->query;
        }
    }
    
    public function getConn($rconn=false){
        return ($rconn == false) ? $this : $this->conn;
    }
    
    public function getName(){
        return "connect";
    }
    
    /**
     * Reconnects or closes connection.
     *
     * @param bool $reconn If enabled, reconnect, otherwise just close the connection itself.
     * @param bool $noreplywait If enabled, wait for all RDB connections it is handling to end before closing the main connections.
     */
    public function close($reconn=false, $noreplywait=true){
        if($reconn)
            $this->conn->reconnect($noreplywait);
        else
            $this->conn->close($noreplywait);
        
        return $this;
    }
}