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

$prefix = Mage::getConfig()->getTablePrefix();

$installer->run(" 
	
CREATE TABLE IF NOT EXISTS `".$prefix."mundipagg_card_on_file` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `entity_id` int(10) NOT NULL,
  `address_id` int(10) NOT NULL,
  `cc_type` varchar(20) DEFAULT '',
  `credit_card_mask` varchar(20) NOT NULL,
  `expires_at` date DEFAULT NULL,
  `token` varchar(50) NOT NULL DEFAULT '',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `entity_id` (`entity_id`),
  KEY `expires_at` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

UPDATE ".$prefix."core_config_data SET `value` = 0 WHERE path = 'payment/mundipagg_standard/active';

UPDATE ".$prefix."core_config_data SET `value` = 0 WHERE path = 'payment/mundipagg_standard/active';	

UPDATE ".$prefix."sales_flat_quote_payment SET `method` = 'mundipagg_creditcard' WHERE method = 'mundipagg_standard';	

UPDATE ".$prefix."sales_flat_order_payment SET `method` = 'mundipagg_creditcard' WHERE method = 'mundipagg_standard';	

");

$mageVersion = Mage::helper('mundipagg/version')->convertVersionToCommunityVersion(Mage::getVersion());

if (version_compare($mageVersion, '1.6.0', '>')) {           
	// UnderPaid
	$status = Mage::getModel('sales/order_status')->load('underpaid', 'status');

	if (!$status->getStatus()) { 
		$status = Mage::getModel('sales/order_status');
		$status->setStatus('underpaid');
		$status->setLabel('Underpaid');
		$status->assignState('pending');
		$status->save();
	}

	// OverPaid
	$status = Mage::getModel('sales/order_status')->load('overpaid', 'status');

	if (!$status->getStatus()) { 
		$status = Mage::getModel('sales/order_status');
		$status->setStatus('overpaid');
		$status->setLabel('Overpaid');
		$status->assignState('processing');
		$status->save();
	}
}

$installer->endSetup(); 
