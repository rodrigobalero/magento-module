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
class Mundipagg_Block_Adminhtml_Form_Field_Installments extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    protected function _prepareToRender()
    {
        $this->addColumn('installment_boundary', array(
            'label' => Mage::helper('mundipagg')->__('Amount (incl.)'),
            'style' => 'width:100px',
        ));
        $this->addColumn('installment_frequency', array(
            'label' => Mage::helper('mundipagg')->__('Maximum Number of Installments'),
            'style' => 'width:100px',
        ));
        $this->addColumn('installment_interest', array(
            'label' => Mage::helper('mundipagg')->__('Interest Rate (%)'),
            'style' => 'width:100px',
        ));
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('mundipagg')->__('Add Installment Boundary');
    }
}
