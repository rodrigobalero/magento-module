<?php

class Mundipagg_FcontrolController extends Mundipagg_Controller_Abstract {

	const FINGERPRINT_URL_SANDBOX    = 'https://static.fcontrol.com.br/fingerprint/hmlg-fcontrol-ed.min.js';
	const FINGERPRINT_URL_PRODUCTION = 'https://static.fcontrol.com.br/fingerprint/fcontrol.min-ed.js';

	public function getConfigAction() {
		$environment = Mundipagg_Model_Source_FControlEnvironment::getEnvironment();
		$configStrPrefix = 'payment/mundipagg_standard';

		if ($environment == Mundipagg_Model_Source_FControlEnvironment::SANDBOX) {
			$configStrKey = "{$configStrPrefix}/fcontrol_key_sandbox";

		} else {
			$configStrKey = "{$configStrPrefix}/fcontrol_key_production";
		}

		if ($environment == Mundipagg_Model_Source_FControlEnvironment::SANDBOX) {
			$url = self::FINGERPRINT_URL_SANDBOX;

		} else {
			$url = self::FINGERPRINT_URL_PRODUCTION;
		}

		$response = array();
		$response['sessionId'] = $this->getSessionId();
		$response['key'] = Mage::getStoreConfig($configStrKey);
		$response['scriptUrl'] = $url;

		try {
			return $this->jsonResponse($response);

		} catch (Exception $e) {
		}

	}

	public function reportErrorAction() {
		if ($this->requestIsValid() == false) {
			echo $this->getResponseForInvalidRequest();

			return false;
		}

		$api = new Mundipagg_Model_Api();
		$helperLog = new Mundipagg_Helper_Log(__METHOD__);
		$message = $this->getRequest()->getPost('message');

		try {
			$helperLog->error($message, true);
			$api->mailError($message);

		} catch (Exception $e) {

		}
	}

	public function logFpAction() {
		$helperLog = new Mundipagg_Helper_Log(__METHOD__);
		$helperUtil = new Mundipagg_Helper_Util();
		$event = $this->getRequest()->getPost('event');
		$data = $this->getRequest()->getPost('data');
		$data = json_decode($data);
		$data = $helperUtil->jsonEncodePretty($data);
		$data = stripslashes($data);
		$message = "Fingerprint {$event}:\n{$data}\n";

		try {
			$helperLog->info($message);
			
		} catch (Exception $e) {
		}

	}

}