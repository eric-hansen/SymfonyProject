<?php
/**
 * Unit test for connection handler.
 */
namespace AnzenSolutions\Bundle\RethinkdbBundle\Tests\Handler;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ConnectTest extends KernelTestCase {
    /**
     * Need to get a reference to the connection when the test is being set up.
     * Standard practice is to terminate the connection afterwards, but the namespace does it for us on deconstruct.
     */
    public function setUp(){
        self::bootKernel();
        
        $this->conn = static::$kernel->getContainer()->get('anzen_solutions_rethinkdb_connect');
        $this->rconn = $this->conn->getConn(true);
    }
    
    public function testConnect(){
        $conn = isset($this->conn) ? $this->conn : array();
        
        $this->assertNotNull($conn);
        $this->assertNotEmpty($conn);
    }
    
    public function testClose(){
        // Only run this test if the connection is open
        $open = $this->rconn->isOpen();
        $this->assertTrue($open);
        
        $this->conn->close();
        
        // Now ensure the connection is closed
        $open = $this->rconn->isOpen();
        $this->assertFalse($open);
    }
    
    public function tearDown(){
        // Only close the connection if its still open (shouldn't be at this point)
        if($this->rconn->isOpen())
            $this->conn->close();
    }
}