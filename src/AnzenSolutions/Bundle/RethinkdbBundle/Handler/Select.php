<?php
namespace AnzenSolutions\Bundle\RethinkdbBundle\Handler;

use AnzenSolutions\Bundle\RethinkdbBundle\Handler\Base;

class Select extends Base {
    public function __construct(Connect &$conn){
        parent::__construct($conn);
    }
    
    /**
     * Get one or more records matching 'value' for index.
     * To use a different key than 'id', set $all to True.
     *
     * @param string $value The value to check the key against
     * @param bool $all Use getAll() instead of get() [required to when using a key other than id]
     * @param array $opts Options to pass to the function(s)
     */
    public function get($value, $all=false, array $opts = array()){
        $this->conn->query = ($all == false) ? $this->conn->query->get($value) : $this->conn->query->getAll($value, $opts);
        
        return $this;
    }
    
    /**
     * Returns the number of rows retrieved via get*() calls.
     */
    public function count(){
        $this->conn->query = $this->conn->query->count();
        
        return $this->run();
    }
    
    /**
     * Selects specific data from the gathered results (i.e.: in SELECT col1,col2 you would pass array('col1', 'col2'))
     *
     * @param mixed $selectors Either a string of 1, or array of many, keys to retrieve from the resultset.
     */
    public function pluck($selectors){
        $this->conn->query = $this->conn->query->pluck($selectors);
        
        return $this;
    }
    
    /**
     * Same thing as $this->pluck() but retrieves all keys EXCEPT these.
     */
    public function without($selectors){
        $this->conn->query = $this->conn->query->without($selectors);
        
        return $this;
    }
    
    /**
     * Wrapper for the *At() methods.
     *
     * @param integer $index The position to run the At() command at
     * @param string $action Which At() command to run (i.e.: "splice" == spliceAt())
     * @param mixed $params Some at() commands require additional parameters, pass them here
     */
    public function at($index, $action, $params=null){
        switch($action){
            case "insert":
                $this->conn->query = $this->conn->query->insertAt($index, $params);
                break;
            
            case "splice":
                $this->conn->query = $this->conn->query->spliceAt($index, $params);
                break;
            
            case "delete":
                $this->conn->query = $this->conn->query->deleteAt($index, $params);
                break;
            
            case "change":
                $this->conn->query = $this->conn->query->changeAt($index, $params);
                break;
        }
        
        return $this;
    }
    
    /**
     * Retrieves the nth record when multiple are available.
     *
     * @param integer $n Retrieve record at position n (base 1??)
     */
    public function nth($n){
        $this->conn->query = $this->conn->query->nth($n);
        
        return $this;
    }
}