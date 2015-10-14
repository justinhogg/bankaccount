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
     * @covers Cilex\Bank\CurrentAccount::__construct
     */
    public function testConstruct()
    {
        $this->assertEquals('1234567890', $this->object->getAccountNumber());
    }
    
    /**
     * @covers Cilex\Bank\CurrentAccount::withdraw
     * @covers Cilex\Bank\CurrentAccount::hasFunds
     * @covers Cilex\Bank\Account::deposit
     * @covers Cilex\Bank\Account::updateBalance
     * @covers Cilex\Bank\Account::getBalance
     */
    public function testWithdrawFunds()
    {
        $this->object->deposit(10.00);
        
        $this->assertTrue($this->object->withdraw(5.25));
        
        $this->assertEquals(4.75, $this->object->getBalance());
    }
    
    /**
     * @covers Cilex\Bank\CurrentAccount::withdraw
     * @covers Cilex\Bank\CurrentAccount::hasFunds
     * @covers Cilex\Bank\Account::deposit
     * @covers Cilex\Bank\Account::getBalance
     */
    public function testWithdrawFundsGreaterThanBalance()
    {
        $this->object->deposit(10.00);
        
        $this->assertFalse($this->object->withdraw(15.00));
        
        $this->assertEquals(10.00, $this->object->getBalance());
    }
    
    /**
     * @covers Cilex\Bank\CurrentAccount::withdraw
     * @covers Cilex\Bank\CurrentAccount::hasFunds
     * @covers Cilex\Bank\CurrentAccount::setOverdraftLimit
     * @covers Cilex\Bank\Account::getBalance
     */
    public function testWithdrawFundsGreaterThanBalanceWithOverdraft()
    {
        $this->object->setOverdraftLimit(10.00);
        
        $this->assertTrue($this->object->withdraw(5.00));
        
        $this->assertEquals(-5.00, $this->object->getBalance());
    }
    
    /**
     * @covers Cilex\Bank\CurrentAccount::hasFunds
     * @covers Cilex\Bank\Account::deposit
     */
    public function testHasFunds()
    {
        $this->object->deposit(10.00);
        
        $this->assertTrue($this->object->hasFunds());
    }
    
    /**
     * @covers Cilex\Bank\CurrentAccount::hasFunds
     */
    public function testHasNoFunds()
    {
        $this->assertFalse($this->object->hasFunds(50.00));
    }
    
    /**
     * @covers Cilex\Bank\CurrentAccount::hasFunds
     * @covers Cilex\Bank\CurrentAccount::setOverdraftLimit
     */
    public function testHasNoFundsButOverdraft()
    {
        $this->object->setOverdraftLimit(100.00);
        
        $this->assertTrue($this->object->hasFunds(50.00));
    }
    
    /**
     * @covers Cilex\Bank\CurrentAccount::hasOverdraft
     */
    public function testhasNoOverdraft()
    {
        $this->assertFalse($this->object->hasOverdraft());
    }
    
    /**
     * @covers Cilex\Bank\CurrentAccount::hasOverdraft
     * @covers Cilex\Bank\CurrentAccount::setOverdraftLimit
     */
    public function testhasOverdraft()
    {
        $this->object->setOverdraftLimit((double) 150.00);
        
        $this->assertTrue($this->object->hasOverdraft());
    }
    
    /**
     * @covers Cilex\Bank\CurrentAccount::getOverdraftLimit
     */
    public function testGetDefaultOverdraftLimit()
    {
        $this->assertEquals(0, $this->object->getOverdraftLimit());
    }
    
    /**
     * @covers Cilex\Bank\CurrentAccount::getOverdraftLimit
     * @covers Cilex\Bank\CurrentAccount::setOverdraftLimit
     */
    public function testGetOverdraftLimit()
    {
        $this->object->setOverdraftLimit((double) 150.00);
        
        $this->assertEquals(150.00, $this->object->getOverdraftLimit());
    }
    
    /**
     * @covers Cilex\Bank\CurrentAccount::setOverdraftLimit
     */
    public function testSetOverdraftLimit()
    {
        $this->assertTrue($this->object->setOverdraftLimit((double) 100.00));
    }
    
    /**
     * @covers Cilex\Bank\CurrentAccount::setOverdraftLimit
     */
    public function testSetBadOverdraftLimit()
    {
        $this->assertFalse($this->object->setOverdraftLimit('100'));
    }
}
   
