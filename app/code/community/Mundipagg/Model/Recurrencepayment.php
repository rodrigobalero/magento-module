<?php

class Mundipagg_Model_RecurrencePayment extends Mundipagg_Model_Standard
{
   /**
     * Availability options
     */
    protected $_code = 'mundipagg_recurrencepayment';
    protected $_formBlockType = 'mundipagg/standard_form';
    protected $_infoBlockType = 'mundipagg/info';
    protected $_isGateway = true;
    protected $_canOrder  = true;
    protected $_canAuthorize = true;
    protected $_canCapture = true;
    protected $_canCapturePartial = false;
    protected $_canRefund = true;
    protected $_canVoid = true;
    protected $_canUseInternal = true;
    protected $_canUseCheckout = true;
    protected $_canUseForMultishipping = true;
    protected $_canSaveCc = false;
    protected $_canFetchTransactionInfo = false;
    protected $_canManageRecurringProfiles = false;
    protected $_allowCurrencyCode = array('BRL', 'USD', 'EUR');
    protected $_isInitializeNeeded = true;

    /**
     * @access public
     * @param array $data
     * @return Mundipagg_Model_Standard
     */
    public function assignData($data)
    {
        $info = $this->getInfoInstance();

        // Reset interests first
        $this->resetInterest($info);

        $cctype = $data[$this->_code.'_1_1_cc_type'];
        $parcelsNumber = 1;
        if (
            isset($data[$this->_code.'_token_1_1']) &&
            $data[$this->_code.'_token_1_1'] != 'new'
        ) {
            $cardonFile = Mage::getModel('mundipagg/cardonfile')->load($data[$this->_code.'_token_1_1']);
            $cctype = Mage::getSingleton('mundipagg/source_cctypes')->getCcTypeForLabel($cardonFile->getCcType());
        }

        /**
         * @var $interest Mundipagg_Helper_Installments
         */
        $interest = Mage::helper('mundipagg/installments')->getInterestForCard($parcelsNumber , $cctype);
        $interestInformation = array();
        $interestInformation[$this->_code.'_1_1'] = new Varien_Object();
        $interestInformation[$this->_code.'_1_1']->setInterest(str_replace(',','.',$interest));

        if ($interest > 0) {
            $info->setAdditionalInformation('mundipagg_interest_information', array());
            $info->setAdditionalInformation('mundipagg_interest_information',$interestInformation);
            $this->applyInterest($info, $interest);

        } else {
            // If none of Cc parcels doens't have interest we reset interest
            $this->resetInterest($info);
        }

        parent::assignData($data);
    }

    /**
     * Prepare info instance for save
     *
     * @return Mage_Payment_Model_Abstract
     */
    public function prepareSave()
    {
        parent::prepareSave();
    }

    /**
     * Instantiate state and set it to state object
     *
     * @param string $paymentAction
     * @param Varien_Object
     */
    public function initialize($paymentAction, $stateObject)
    {
        $standard = Mage::getModel('mundipagg/standard');

        switch($standard->getConfigData('payment_action')) {
            case 'order':
                $this->setCreditCardOperationEnum('AuthAndCapture');

                $paymentAction = $orderAction = 'order';
                break;

            case 'authorize':
                $this->setCreditCardOperationEnum('AuthOnly');

                $paymentAction = $orderAction = 'authorize';
                break;

            case 'authorize_capture':
                $this->setCreditCardOperationEnum('AuthAndCaptureWithDelay');

                $paymentAction = $orderAction = 'authorize_capture';
                break;
        }

        $payment = $this->getInfoInstance();
        $order = $payment->getOrder();

        switch ($paymentAction) {
            case Mage_Payment_Model_Method_Abstract::ACTION_AUTHORIZE:
                parent::authorize($payment, $order->getBaseTotalDue());
                break;

            case Mage_Payment_Model_Method_Abstract::ACTION_AUTHORIZE_CAPTURE:
                parent::authorize($payment, $order->getBaseTotalDue());
                break;

            case $orderAction:
                parent::order($payment, $order->getBaseTotalDue());
                break;

            default:
                parent::order($payment, $order->getBaseTotalDue());
                break;
        }
    }
}