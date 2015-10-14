<?php
/**
 *
 * @author jhogg
 */
namespace Cilex\Bank;

abstract class Account {
    
    protected $accountNumber;
    
    private $totalBalance;
    
    private $accountOpen  = false;
    
    abstract public function withdraw();
    
    public function __construct($accountNumber) {
        $this->accountNumber = $accountNumber;
    }
    
    public function deposit($value)
    {
        $this->totalBalance + $value;
    }
    
    public function getBalance()
    {
        return (float) $this->totalBalance;
    }
    
    public function open() 
    {
        $this->accountOpen = true;
    }
    
    public function close()
    {
        $this->accountOpen = false;
    }
    
    public function isOpen()
    {
        return (bool) $this->accountOpen; 
    }
    
}
