<?php
/**
 *
 * @author jhogg
 */
namespace Cilex\Bank;

abstract class Account {
    
    const TYPE_CURRENT  = 'current';
    const TYPE_SAVINGS  = 'savings';
    
    private $accountNumber;
    
    private $totalBalance = 0.00;
    
    private $accountOpen  = false;
    
    
    public function __construct($accountNumber) {
        $this->accountNumber = $accountNumber;
    }
    
    /**
     * getAccountNumber - returns the account number
     * 
     * @return mixed string|int
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }
    
    /**
     * getBalance - returns the balance of the account
     * 
     * @return double
     */
    public function getBalance()
    {
        return (double) $this->totalBalance;
    }
    
    /**
     * open - sets the account open flag to true
     */
    public function open() 
    {
        $this->accountOpen = true;
    }
    
    /**
     * close - sets the account open flag to false
     */
    public function close()
    {
        $this->accountOpen = false;
    }
    
    /**
     * isOpen - returns whether the account is open or not
     * @return boolean
     */
    public function isOpen()
    {
        return (bool) $this->accountOpen; 
    }
    
    /**
     * deposit - deposit an amount into the account
     *
     * @param double $amount - the value to deposit
     * @return boolean
     */
    public function deposit($amount)
    {
        if (is_double($amount)) {
            $this->totalBalance = $this->totalBalance + (double) $amount;
        
            return true;
        }
        
        return false;
    }
    
    /**
     * updateBalance - update the balance of the account
     *
     * @param double $amount - the value to subtract off balance
     * @return boolean
     */
    protected function updateBalance($amount) {
        $this->totalBalance  = $this->totalBalance - (double) $amount;
    }
    
    /**
     * withdraw - withdraws funds from the account
     *
     * @param double $withdrawAmount amount to withdraw
     * @return boolean
     */
    abstract public function withdraw($withdrawAmount);
    
    /**
     * hasFunds - returns whether the account has funds
     * 
     * @retuen boolean
     */
    abstract public function hasFunds();
}
