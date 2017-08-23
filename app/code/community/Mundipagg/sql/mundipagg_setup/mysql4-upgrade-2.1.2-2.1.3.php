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

    $installer = $this;

    $installer->startSetup();

    Mage::getConfig()->saveConfig('payment/mundipagg_standard/apiUrlStaging','https://sandbox.mundipaggone.com/sale/');
    Mage::getConfig()->reinit();
    Mage::getConfig()->cleanCache();
    Mage::app()->reinitStores();

    $installer->endSetup();
    