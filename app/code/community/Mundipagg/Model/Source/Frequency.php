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

class Mundipagg_Model_Source_Frequency extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    public function getAllOptions()
    {
        return array(
            array('value' => '0', 'label' => '...'),
            array('value' => 'Daily', 'label' => Mage::helper('mundipagg')->__('Daily')),
            array('value' => 'Weekly', 'label' => Mage::helper('mundipagg')->__('Weekly')),
            array('value' => 'Monthly', 'label' => Mage::helper('mundipagg')->__('Monthly')),
            array('value' => 'Quarterly', 'label' => Mage::helper('mundipagg')->__('Quarterly')),
            array('value' => 'Biannual', 'label' => Mage::helper('mundipagg')->__('Biannual')),
            array('value' => 'Yearly', 'label' => Mage::helper('mundipagg')->__('Yearly'))
        );
    }

    public function toOptionArray()
    {
        return $this->getAllOptions();
    }
}
