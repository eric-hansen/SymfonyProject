<?php
/**
 * Unit test for connection handler.
 */
namespace AnzenSolutions\Bundle\RethinkdbBundle\Tests\Handler;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DbTest extends KernelTestCase {
    /**
     * Need to get a reference to the connection when the test is being set up.
     * Standard practice is to terminate the connection afterwards, but the namespace does it for us on deconstruct.
     */
    public function setUp(){
        self::bootKernel();
        
        $this->db = static::$kernel->getContainer()->get('anzen_solutions_rethinkdb.db');
    }
    
    public function testCreate(){
    }
}