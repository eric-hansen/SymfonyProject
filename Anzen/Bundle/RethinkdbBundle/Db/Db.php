<?php
namespace Anzen\Bundle\RethinkdbBundle\Db;

use Anzen\Bundle\RethinkdbBundle\Create;

class Db {
    public function __construct(Create $rdb){
        $this->r = $rdb->getR();
    }
    
    public function create($name){
        $this->r->dbCreate($name)->run();
    }
    
    public function _use($db){
        $this->r->useDb($db);
    }
    
    public function drop($db){
        $this->r->dbDrop($db);
    }
    
    
}
