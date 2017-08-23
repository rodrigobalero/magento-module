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

    class Mundipagg_Model_System_Config_Backend_Installments extends Mage_Core_Model_Config_Data
    {
        /**
         * Process data after load
         */
        protected function _afterLoad()
        {
            $value = $this->getValue();
            $value = Mage::helper('mundipagg/installments')->makeArrayFieldValue($value);
            $this->setValue($value);
        }

        /**
         * Prepare data before save
         */
        protected function _beforeSave()
        {
            $value = $this->getValue();
            $value = Mage::helper('mundipagg/installments')->makeStorableArrayFieldValue($value);
            $this->setValue($value);
        }
    }
