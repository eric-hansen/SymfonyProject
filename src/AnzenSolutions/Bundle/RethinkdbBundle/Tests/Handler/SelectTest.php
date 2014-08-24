<?php
/**
 * Unit test for connection handler.
 */
namespace AnzenSolutions\Bundle\RethinkdbBundle\Tests\Handler;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SelectTest extends KernelTestCase {
    /**
     * Need to get a reference to the connection when the test is being set up.
     * Standard practice is to terminate the connection afterwards, but the namespace does it for us on deconstruct.
     */
    public function setUp(){
        self::bootKernel();
        
        $this->select = static::$kernel->getContainer()->get('anzen_solutions_rethinkdb.select');
    }
    
    public function testGet(){
        $this->select->table("blah");
        $res = $this->select->get('0ece81fe-25d1-4366-9f47-c32f77fab60d')->run();
        
        $this->assertNotNull($res);
        
        $res = $this->select->get(2, true, array('index' => 'do'));
        $this->assertGreaterThan(0, $res->count());
    }
    
    public function testNewCopy(){
        $copy = $this->select->_new();
        
        // These should be empty with a successfully new connection request
        $this->assertEmpty($copy->query);
        $this->assertEmpty($copy->tbl);
    }
    
    public function testNthRecord(){
        $this->select->table("blah");
        $res = $this->select->get(2, true, array('index' => 'do'))->run();
        $nth = $this->select->get(2, true, array('index' => 'do'))->nth(1)->run();
        
        $this->assertEquals($res[1], $nth);
    }
}