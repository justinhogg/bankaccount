<?php
/**
 * Description of CurrentAccount
 *
 * @author jhogg
 */

namespace Cilex\Bank;

class CurrentAccount extends \Cilex\Bank\Account
{
    protected $overdraftValue   = 0;
    
    public function __construct($accountNumber, $overdraftValue = 0) 
    {
        parent::__construct($accountNumber);
        
        $this->setOverdraftValue($overdraftValue);
    }
    
    public function withdraw() {
        parent::withdraw();
    }
    
    public function setOverdraftValue($value)
    {
        $this->overdraftValue = $value;
    }
    
    public function getOverdraftValue()
    {
        return $this->overdraftValue;
    }
    
    public function hasOverdraft()
    {
        return ($this->overdraftValue > 0)? true : false;
    }
}
