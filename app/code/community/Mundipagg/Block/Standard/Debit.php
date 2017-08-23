<?php
/**
 * MundiPagg Embeddables
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MundiPagg.
 * It is also available through the world-wide-web at this URL:
 * http://www.mundipagg.com/
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mundipagg.com/ for more information
 *
 * @category   MundiPagg
 * @package    Mundipagg
 * @copyright  Copyright (c) 2017 MundiPagg(http://www.mundipagg.com/)
 * @license    http://www.mundipagg.com/
 */

/**
 * Mundipagg Payment module
 *
 * @category   MundiPagg
 * @package    Mundipagg
 * @author     MundiPagg Embeddables Team
 */

class Mundipagg_Block_Standard_Debit extends Mage_Payment_Block_Form
{
    protected function _construct() 
    {
        parent::_construct();

    	$this->setTemplate('mundipagg/debit.phtml');
    }

    /**
     * Debit Types
     */
    public function getDebitTypes() 
    {
        $debitTypes = Mage::getStoreConfig('payment/mundipagg_debit/debit_types');
        
        if ($debitTypes != '') {
            $debitTypes = explode(",", $debitTypes);
        }
        else {
            $debitTypes = array();
        }
        
        return $debitTypes;
    }
}