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

class Mundipagg_Block_Adminhtml_Sales_Order_Invoice_View extends Mage_Adminhtml_Block_Sales_Order_Invoice_View
{
	public function __construct()
    {
    	parent::__construct();

    	$this->_removeButton('void');

    	$orderPayment = $this->getInvoice()->getOrder()->getPayment();

        if ($this->_isAllowedAction('creditmemo') ) {
            if (($orderPayment->canRefundPartialPerInvoice()
                && $this->getInvoice()->canRefund()
                && $orderPayment->getAmountPaid() > $orderPayment->getAmountRefunded())
                || ($orderPayment->canRefund() && !$this->getInvoice()->getIsUsedForRefund())) {
                $this->_addButton('capture', array( // capture?
                    'label'     => Mage::helper('sales')->__('Credit Memo'),
                    'class'     => 'go',
                    'onclick'   => 'setLocation(\''.$this->getCreditMemoUrl().'\')'
                    )
                );
            }
        }
    }
}
