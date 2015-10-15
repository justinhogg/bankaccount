<?php
/**
 * Description of CurrentAccount
 *
 * @author jhogg
 */

namespace Cilex\Bank;

class CurrentAccount extends \Cilex\Bank\Account
{
    protected $overdraftLimit   = 0.00;
    
    public function __construct($accountNumber) 
    {
        parent::__construct($accountNumber);
    }
    
    /**
     * withdraw - withdraws funds from the account
     *
     * @param double $withdrawAmount amount to withdraw
     * @return boolean
     */
    public function withdraw($withdrawAmount) {
        
        if($this->hasFunds($withdrawAmount)) {
            
            $this->updateBalance($withdrawAmount);
            
            return true;
        }
        
        return false;
    }
    
    /**
     * hasFunds - returns whether the account has funds to withdraw
     *
     * @param double $withdrawAmount - amount of money to withdraw from the funds
     *
     * @return boolean
     */
    public function hasFunds($withdrawAmount = 0.00)
    {
        if ($withdrawAmount < $this->getBalance()) {
            return true;
        } elseif ($this->hasOverdraft() && $withdrawAmount < ($this->getBalance()+$this->overdraftLimit)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * setOverdraftLimit - sets the limit the account can go over
     *
     * @param int $value
     * @return boolean
     */
    public function setOverdraftLimit($value)
    {
        if (is_double($value)) {
            $this->overdraftLimit = $value;
        
            return true;
        }
        
        return false;
    }
    
    /**
     * getOverdraftLimit - returns the current overdraft limit
     *
     * @return double
     */
    public function getOverdraftLimit()
    {
        return (double) $this->overdraftLimit;
    }
    
    /**
     * hasOverdraft - whether this account has a overdraft
     *
     * @return boolean
     */
    public function hasOverdraft()
    {
        return ($this->overdraftLimit > 0)? true : false;
    }
}
