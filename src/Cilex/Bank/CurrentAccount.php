<?php
/**
 * Description of CurrentAccount
 *
 * @author Justin Hogg <justin@thekordas.com>
 */

namespace Cilex\Bank;

class CurrentAccount extends \Cilex\Bank\Account
{
    private $overdraft  = null;
    
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
        } elseif ($this->hasOverdraft() && $withdrawAmount < ($this->getBalance()+$this->overdraft->getLimit())) {
            return true;
        }
        
        return false;
    }
    
    /**
     * setOverdraft - sets the overdraft for this account
     *
     * @param \Cilex\Bank\OverdraftService $overdraft
     * @param double $amount
     * @return boolean
     */
    public function setOverdraft(\Cilex\Bank\OverdraftService $overdraft, $amount) {
        
        $this->overdraft = $overdraft;
        return $this->overdraft->setLimit($amount);
    }

    /**
     * hasOverdraft - whether this account has a overdraft
     *
     * @return boolean
     */
    public function hasOverdraft()
    {
        return ($this->overdraft !== null) ? $this->overdraft->isEnabled(): false;
    }
    
    /**
     * getOverdraft - get the overdraft limit
     *
     * @return double
     */
    public function getOverdraftLimit()
    {
        return ($this->overdraft !== null) ? $this->overdraft->getLimit(): 0.00;
    }
}
