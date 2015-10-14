<?php
/**
 * Description of CurrentAccountTest
 *
 * @author jhogg
 */

namespace Cilex\Tests\Bank;

use Cilex\Bank\CurrentAccount;

class CurrentAccountTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Cilex\Bank\CurrentAccount
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
        //set up test object
        $this->object = new CurrentAccount('1234567890');
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
     */
    public function testConstruct()
    {
        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
    
    public function testOpenNewAccount()
    {
        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
    
    public function testOpenNewAccountWithOverdraft()
    {
        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
    
    public function testOpenNewBadAccount()
    {
        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
    /**
     * @covers Cilex\Bank\CurrentAccount::open
     * @covers Cilex\Bank\CurrentAccount::close
     * @covers Cilex\Bank\CurrentAccount::isClosed
     */
    public function testCloseAccount()
    {   
        //open account
        $this->object->open();
        
        $this->assertTrue($this->object->isOpen());
        
        //close object
        $this->object->close();
        
        $this->assertFalse($this->object->isOpen());
    }
    
    /**
     * @covers Cilex\Bank\CurrentAccount::isClosed
     */
    public function testAccountisClosedByDefault()
    {   
        $this->assertFalse($this->object->isOpen());
    }
    
    public function testCloseBadAccount()
    {     
        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
    
    public function testGetBalance()
    {
        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
    
    public function testDeposit()
    {
        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
    
    public function testDepositInvalidValue()
    {
        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
    
    public function testWithdrawFunds()
    {
        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
    
    public function testWithdrawFundsGreaterThanBalance()
    {
        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
    
    public function testWithdrawFundsGreaterThanBalanceWithOverdraft()
    {
        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
    
    public function testSetOverdraft()
    {
        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
   
