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
     * @author     Thanks to Fillipe Almeida Dutra
     */

    $installer = Mage::getResourceModel('sales/setup', 'default_setup');

    $installer->startSetup();

// Interests
    $installer->addAttribute('quote', 'mundipagg_base_interest',
        array(
            'label' => 'Base Interest',
            'type'  => 'decimal',
        )
    );

    $installer->addAttribute('quote', 'mundipagg_interest',
        array(
            'label' => 'Interest',
            'type'  => 'decimal',
        )
    );

    $installer->addAttribute('order', 'mundipagg_base_interest',
        array(
            'label' => 'Base Interest',
            'type'  => 'decimal',
        )
    );

    $installer->addAttribute('order', 'mundipagg_interest',
        array(
            'label' => 'Interest',
            'type'  => 'decimal',
        )
    );

    $installer->addAttribute('invoice', 'mundipagg_base_interest',
        array(
            'label' => 'Base Interest',
            'type'  => 'decimal',
        )
    );

    $installer->addAttribute('invoice', 'mundipagg_interest',
        array(
            'label' => 'Interest',
            'type'  => 'decimal',
        )
    );

    $installer->addAttribute('creditmemo', 'mundipagg_base_interest',
        array(
            'label' => 'Base Interest',
            'type'  => 'decimal',
        )
    );

    $installer->addAttribute('creditmemo', 'mundipagg_interest',
        array(
            'label' => 'Interest',
            'type'  => 'decimal',
        )
    );

    $installer->endSetup();