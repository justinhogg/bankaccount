<?php
/**
 * Description of OverdraftServiceTest
 *
 * @author jhogg
 */

namespace Cilex\Tests\Bank;

use Cilex\Bank\OverdraftService;

class OverdraftServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Cilex\Bank\OverdraftService
     */
    protected $object;

     /**
     * @var class
     */
    protected $class;

    /**
     *
     * @var array
     */
    protected $attributes;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $mockObject = $this->getMock('\Cilex\Bank\CurrentAccount', array(), array('1234567890'));
        //set up test object
        $this->object = new OverdraftService($mockObject);
    }
    
    /**
     * Tears down the fixture.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * Tests whether the constructor instantiates the correct dependencies.
     * @covers Cilex\Bank\OverdraftService::__construct
     */
    public function testConstruct()
    {
        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
    
    /**
     * @covers Cilex\Bank\OverdraftService::getName
     */
    public function testGetName()
    {
        $this->assertEquals('overdraft', $this->object->getName());
    }
    
    /**
     * @covers Cilex\Bank\OverdraftService::getLimit
     */
    public function testGetDefaultLimit()
    {
        $this->assertEquals(0.00, $this->object->getLimit());
    }
    
    /**
     * @covers Cilex\Bank\OverdraftService::getlimit
     * @covers Cilex\Bank\OverdraftService::setlimit
     */
    public function testGetLimit()
    {
        $this->object->setLimit((double) 105);
        
        $this->assertEquals(105.00, $this->object->getLimit());
    }
    
    /**
     * @covers Cilex\Bank\OverdraftService::setlimit
     */
    public function testSetLimit()
    {
        $this->assertTrue($this->object->setLimit((double) 105.25));
    }
    
    /**
     * @covers Cilex\Bank\OverdraftService::setlimit
     */
    public function testSetBadLimit()
    {
        $this->assertFalse($this->object->setLimit('123'));
    }
    
    /**
     * @covers Cilex\Bank\OverdraftService::isEnabled
     * @covers Cilex\Bank\OverdraftService::setlimit
     */
    public function testIsEnabled()
    {
        $this->object->setLimit((double) 105.25);
        
        $this->assertTrue($this->object->isEnabled());
    }
    
    /**
     * @covers Cilex\Bank\OverdraftService::isEnabled
     */
    public function testIsNotEnabled()
    {
        $this->assertFalse($this->object->isEnabled());
    }
}
   
