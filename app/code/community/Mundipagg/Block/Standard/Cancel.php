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

class Mundipagg_Block_Standard_Cancel extends Mage_Core_Block_Template
{
	/**
     * Internal constructor
     * Set template for redirect
     *
     */
	public function __construct() 
    {
		parent::_construct();
        $this->setTemplate('mundipagg/cancel.phtml');
    }

    /**
    * Get Error Description
    * @return string
    **/ 
    public function getErrorDescription() 
    {
        $session = Mage::getSingleton('checkout/session');
        $session->setQuoteId($session->getMundipaggStandardQuoteId(true));

        if ($session->getLastRealOrderId()) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($session->getLastRealOrderId());
            
            return $order->getPayment()->getAdditionalInformation('ErrorDescription'); 
        }
    }
}