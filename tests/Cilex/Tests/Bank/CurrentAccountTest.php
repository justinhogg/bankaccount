<?php
/**
 * Description of CurrentAccountTest
 *
 * @author Justin Hogg <justin@thekordas.com>
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
     *
     * @var class
     */
    protected $mockOverdraftObject;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        //set up test object
        $this->object = new CurrentAccount('1234567890');
        $this->mockOverdraftObject = $this->getMock('\Cilex\Bank\OverdraftService', array('setLimit', 'getLimit', 'isEnabled'),array($this->object));
        
        switch($this->getName()) {
            case 'testWithdrawFundsGreaterThanBalanceWithOverdraft':
            case 'testHasNoFundsButOverdraft':
            case 'testhasOverdraft':    
                $this->mockOverdraftObject->expects($this->once())->method('setLimit')->will($this->returnValue(true));
                $this->mockOverdraftObject->expects($this->any())->method('getLimit')->will($this->returnValue(10.00));
                $this->mockOverdraftObject->expects($this->once())->method('isEnabled')->will($this->returnValue(true));
                $this->object->setOverdraft($this->mockOverdraftObject, 10.00);
                break;
            case 'testSetOverdraftLimit':
                $this->mockOverdraftObject->expects($this->once())->method('setLimit')->will($this->returnValue(true));
                break;
            case 'testSetBadOverdraftLimit':
                $this->mockOverdraftObject->expects($this->once())->method('setLimit')->will($this->returnValue(false));
                break;
        }
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
     * @covers Cilex\Bank\CurrentAccount::setOverdraft
     * @covers Cilex\Bank\Account::getBalance
     */
    public function testWithdrawFundsGreaterThanBalanceWithOverdraft()
    {
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
     * @covers Cilex\Bank\CurrentAccount::setOverdraft
     */
    public function testHasNoFundsButOverdraft()
    {
        $this->assertTrue($this->object->hasFunds(5.00));
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
     * @covers Cilex\Bank\CurrentAccount::setOverdraft
     */
    public function testhasOverdraft()
    {
        $this->assertTrue($this->object->hasOverdraft());
    }
    
    /**
     * @covers Cilex\Bank\CurrentAccount::setOverdraft
     */
    public function testSetOverdraftLimit()
    {
        $this->assertTrue($this->object->setOverdraft($this->mockOverdraftObject, 10.00));
    }
    
    /**
     * @covers Cilex\Bank\CurrentAccount::setOverdraft
     */
    public function testSetBadOverdraftLimit()
    {
        $this->assertFalse($this->object->setOverdraft($this->mockOverdraftObject, '100'));
    }
}
   
