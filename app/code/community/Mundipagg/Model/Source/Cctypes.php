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

class Mundipagg_Model_Source_Cctypes
{
    public function toOptionArray() 
    {
        return array(
            array('value' => 'VI', 'label' => 'Visa'),
            array('value' => 'MC', 'label' => 'Mastercard'),
            array('value' => 'AE', 'label' => 'Amex'),
            array('value' => 'DI', 'label' => 'Diners'),
            array('value' => 'EL', 'label' => 'Elo'),
            array('value' => 'HI', 'label' => 'Hipercard'),
        );
    }

    public function getCcTypeForLabel($label){
        $ccTypes = $this->toOptionArray();
        foreach($ccTypes as $cc){
            if($cc['value'] == $label){
                return $cc['label'];
            }
        }
    }
}