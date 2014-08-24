<?php
/**
 * Simple class that handles a lot of routine stuff with the RDB connection.
 * This can probably be done a lot better, but hey.
 */
namespace AnzenSolutions\Bundle\RethinkdbBundle\Handler;

class Base {
    public $conn;
    public $query;
    
    public function __construct(Connect &$conn){
        $this->conn = $conn->getConn();
    }
    
    /**
     * Initiates a requst to a new table, overriding any previous query structure.
     *
     * @param string $newTbl The new table name to use
     */
    final public function table($newTbl){
        $this->conn->table($newTbl);
        
        return $this;
    }
    
    /**
     * Executes the built up query and returns any results.
     *
     * @param bool $remove_query Will clear the query cache if enabled, otherwise leaves it for further use
     * @param bool $force_removal Overrides the logic of $remove_query (if res == null then don't clear).  Similar to rm -rf /
     * @return mixed PHP data result (converted via toNative())
     */
    final public function run($remove_query=true, $force_remove=false){
        $res = is_null($this->conn->query) ? null : $this->conn->query->run($this->conn->getConn(true));
        
        if($force_remove || ($remove_query && $res !== null)){
            $this->conn->query = $this->conn->old_state;
        }
        
        return is_null($res) ? $res : $res->toNative();
    }
    
    /**
     * Establishes a RDB call in the r namespace.
     *
     * @param string $method The r method to call (i.e.: "table" for r\table(...))
     * @param mixed $args Defined as string but will be auto-converted to an array.  Arguments for $method
     * @param bool $run If true, run the call otherwise just build it fro the query
     */
    final protected function call($method, $args = "", $run=false){
        if(!is_array($args))
            $args = array($args);
        
        $call = call_user_func_array('r\\' . $method, $args);
        
        return ($run == false) ? $call : $call->run($this->conn->conn);
    }
    
    /**
     * Synchronizes the data.
     */
    public function sync(){
        $this->conn->query = $this->conn->query->sync();
        
        return $this->run();
    }
    
    public function branch($test, $true, $false){
        return $this->call("branch", array($test, $true, $false));
    }
    
    public function expr($data){
        return $this->call("expr", $data);
    }
    
    public function info(){
        $this->conn->query = $this->conn->query->info();
        
        return $this;
    }
    
    public function _new(){
        return $this->conn->newConn();
    }
}