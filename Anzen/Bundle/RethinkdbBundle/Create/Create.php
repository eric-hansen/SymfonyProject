<?php
namespace Anzen\Bundle\RethinkdbBundle\Db;

use Anzen\Bundle\RethinkdbBundle;

class Create {
    public function __construct($db, $host, $port, $auth_key){
        $this->r = new RethinkdbBundle($db, $host, $port, $auth_key);
    }
    
    public function getR(){
        return $this->r;
    }
    
    public function run($r=null){
        return ($r == null) ? $this->r->run() : $r->run();
    }
}
