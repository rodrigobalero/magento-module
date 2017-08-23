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

class Mundipagg_Model_Quote_Address_Interest extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    /** 
     * Constructor that should initiaze 
     */
    public function __construct()
    {
        $this->setCode('mundipagg_interest');
    }

	public function collect(Mage_Sales_Model_Quote_Address $address)
	{
        if($address->getQuote()->isVirtual()){
            if ($address->getData('address_type') == 'shipping') return $this;
        }else{
            if ($address->getData('address_type') == 'billing') return $this;
        }

        $this->_setAddress($address);
        $addressObj = $this->_getAddress();
        $totals = $addressObj->_totals;
        
        parent::collect($address);

        $quote = $address->getQuote();
        $amount = $quote->getMundipaggInterest();
        
        if($amount > 0) {
            $this->_setBaseAmount(0.00);
            $this->_setAmount(0.00);

            $quote->getPayment()->setPaymentInterest($amount);
            $address->setMundipaggInterest($amount);
            
            $this->_setBaseAmount($amount);
            $this->_setAmount($amount);
            
            
            $shippingAmount = $totals['shipping_amount'];
            $baseSubtotal = $totals['base_subtotal'];
            $totalOrderAmount = $baseSubtotal + $shippingAmount + $amount;
            $address->setGrandTotal($totalOrderAmount);
            $address->setBaseGrandTotal($totalOrderAmount);
            $address->save();
        } else {
            $this->_setBaseAmount(0.00);
            $this->_setAmount(0.00);
            
            $quote->getPayment()->setPaymentInterest(0.00);
            $address->setMundipaggInterest(0.00);
        }

		return $this;
	}


    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        if ($address->getMundipaggInterest() != 0)
        {
            $address->addTotal(array
            (
                'code' => $this->getCode(),
                'title' => Mage::helper('mundipagg')->__('Interest'),
                'value' => $address->getMundipaggInterest()
            ));
            $this->collect($address);
        }
    }
}
