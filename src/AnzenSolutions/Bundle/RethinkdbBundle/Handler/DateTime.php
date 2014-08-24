<?php
namespace AnzenSolutions\Bundle\RethinkdbBundle\Handler;

use AnzenSolutions\Bundle\RethinkdbBundle\Handler\Base;

class DateTime extends Base {
    public function __construct(Connect &$conn){
        parent::__construct($conn);
        
        // Not sure if this is needed...but, better safe than sorry.  Since DateTime works with Time objects
        $this->conn = $this->_new();
    }
    
    public function now(){
        return $this->call("now");
    }
    
    public function time(array $params = array()){
        return $this->call("time", $params);
    }
    
    public function epoch($seconds=null){
        return ($seconds === null) ? time() : call("epochTime", $seconds);
    }
    
    public function iso8601($date, array $args = array()){
        return $this->call("iso8601", array_merge(array($date), $args));
    }
    
    public function between($start, $end, $start_bound="closed", $end_bound="open"){
        return $this->conn->query->during($start, $end, array('left_bound' => $start_bound, 'right_bound' => $end_bound));
    }
}