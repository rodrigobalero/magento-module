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

");

$installer->endSetup();
