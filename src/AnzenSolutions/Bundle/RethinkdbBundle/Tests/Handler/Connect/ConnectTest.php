<?php
namespace AnzenSolutions\Bundle\RethinkdbBundle\Tests\Handler;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ConnectTest extends KernelTestCase {
    public function setUp(){
        self::bootKernel();
        
        $this->conn = static::$kernel->getContainer()->get('anzen_solutions_rethinkdb_connect')->conn();
        //$this->conn = new \AnzenSolutions\Bundle\RethinkdbBundle\Services\Connect(null, null, null)
    }
    
    public function testConnect(){
        static::bootKernel();
        
        $conn = isset($this->conn) ? $this->conn : array();
        
        print_r($conn);
    }
}