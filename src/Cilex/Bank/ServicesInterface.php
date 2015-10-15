<?php
/**
 *
 * @author Justin Hogg <justin@thekordas.com.com>
 */

namespace Cilex\Bank;

interface ServicesInterface {
    
    /**
     * Constructor
     * @param \Cilex\Bank\Account $account
     */
    public function __construct(\Cilex\Bank\Account $account);
    
    /**
     * getName - returns the name of the service
     *
     * @return string
     */
    public function getName();
    
    /**
     * isEnabled - returns whether the service is enabled
     * 
     * @return boolean
     */
    public function isEnabled();
}
