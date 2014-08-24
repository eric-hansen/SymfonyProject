<?php

namespace AnzenSolutions\Bundle\RethinkdbBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AnzenSolutionsRethinkdbBundle extends Bundle
{
    /**
     * Instance of class, mainly used for ORM-style purposes.
     */
    public function __construct($conn="", $tbl="", $query=null, $db="", $host="", $port=28015, $auth_key=""){
        $this->tbl = $tbl;
        $this->query = $query;
        
        if(!empty($db))
            return $this->connect($db, $host, $port, $auth_key);
    }
    
    /**
     * Connects to RethinkDB server.
     *
     * @param string $db Database to connect to
     * @param string $host Hostname of server
     * @param int $port Port number host is listening on (not the web interface)
     * @param string $auth_key Authentication key (i.e.: API/passphrase) for increased security.
     * @return AnzenSolutionsRethinkdbBundle Instance of self.
     */
    public function connect($db, $host='localhost', $port=28015, $auth_key=""){
        if(!isset($this->conn))
            $this->conn = r\connect($host, $port, $db, $auth_key);
        
        if(!isset($this->tbl))
            $this->tbl = "";
        
        if(!isset($this->query))
            $this->query = null;
        
        return new self($this->conn, $this->tbl, $this->query);
    }
    
    public function table($tbl="", $get_outdated=false){
        if(empty($tbl)){
            if(!$this->query)
                $this->query = r\table($this->tbl, $get_outdated);
        } else{
            $this->tbl = $tbl;
        }
        
        return $this;
    }
    
    public function close($reconn=false, $noreplywait=true){
        if($reconn)
            $this->conn->reconnect($noreplywait);
        else
            $this->conn->close($noreplywait);
        
        return $this;
    }
    
    public function run($remove_query=false){
        $res = $this->query->run($this->conn);
        
        if($remove_query)
            $this->query = null;
        
        return $res;
    }
    
    public function call($method, array $args = array(), $run=false){
        $call = call_user_func_array('r\\' . $method, $args);
        
        return ($run == false) ? $call : $call->run($this->conn);
    }
}
