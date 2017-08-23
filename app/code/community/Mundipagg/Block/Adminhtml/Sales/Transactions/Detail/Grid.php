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

class Mundipagg_Block_Adminhtml_Sales_Transactions_Detail_Grid extends Mage_Adminhtml_Block_Sales_Transactions_Detail_Grid
{
	/**
     * Retrieve Transaction addtitional info
     *
     * FIX: Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS inside getAdditionalInformation()
     * was causing no Transaction Details display
     *
     * @return array
     */
    public function getTransactionAdditionalInfo()
    {
    	$info = Mage::registry('current_transaction')->getAdditionalInformation();
        return (is_array($info)) ? $info : array();
    }
}