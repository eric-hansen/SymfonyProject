<?php
/**
 * Unit test for connection handler.
 */
namespace AnzenSolutions\Bundle\RethinkdbBundle\Tests\Handler;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DateTimeTest extends KernelTestCase {
    /**
     * Need to get a reference to the connection when the test is being set up.
     * Standard practice is to terminate the connection afterwards, but the namespace does it for us on deconstruct.
     */
    public function setUp(){
        self::bootKernel();
        
        $this->dt = static::$kernel->getContainer()->get('anzen_solutions_rethinkdb.datetime');
    }
    
    public function testNow(){
        $now = $this->dt->now()->run($this->dt->conn->getConn(true))->toNative();
        
        $this->assertEquals(time(), (int)$now['epoch_time']);
    }
}