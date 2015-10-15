<?php
/**
 * Description of OverdraftService
 *
 * @author Justin Hogg <justin@thekordas.com>
 */

namespace Cilex\Bank;

class OverdraftService implements \Cilex\Bank\ServicesInterface {
    
    private $account;
    
    private $limit  = 0.00;
    
    /**
     * Constructor
     * @param \Cilex\Bank\Account $account
     */
    public function __construct(\Cilex\Bank\Account $account){
        //Set the account
        $this->account = $account;
    }
    
    /**
     * getName - returns the name of the service
     * 
     * @return string
     */
    public function getName()
    {
        return 'overdraft';
    }
    
    /**
     * isEnabled - returns whether the service is enabled
     * 
     * @return boolean
     */
    public function isEnabled(){
        return ($this->limit > 0)? true : false;
    }
    
    /**
     * setLimit - sets the limit for the overdraft
     *
     * @param double $value
     * @return boolean
     */
    public function setLimit($value)
    {
        if (is_double($value)) {
            $this->limit = $value;
        
            return true;
        }
        
        return false;
    }
    
    /**
     * getLimit - returns the current overdraft limit
     *
     * @return double
     */
    public function getLimit()
    {
        return (double) $this->limit;
    }
}
