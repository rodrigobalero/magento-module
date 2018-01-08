<?php

class Uecommerce_Mundipagg_Helper_OrderStatus extends Mage_Core_Helper_Abstract
{
    /**
     * Deal with captured status
     * from notification post
     */
    public function processCapturedStatus($order, $amountToCapture, $transactionKey, $orderReference, $helperLog)
    {
        try {
            $return = $this->captureTransaction($order, $amountToCapture, $transactionKey);
        } catch (Exception $e) {
            $errMsg = $e->getMessage();
            $returnMessage = "OK | #{$orderReference} | {$transactionKey} | ";
            $returnMessage .= "Can't capture transaction: {$errMsg}";
            $helperLog->info($returnMessage);
            $helperLog->info("Current order status: " . $order->getStatusLabel());
            return $returnMessage;
        }
        if ($return instanceof Mage_Sales_Model_Order_Invoice) {
            Mage::helper('mundipagg')->sendNewInvoiceEmail($return,$order);

            $returnMessage = "OK | #{$orderReference} | {$transactionKey} | " . self::TRANSACTION_CAPTURED;
            $helperLog->info($returnMessage);
            $helperLog->info("Current order status: " . $order->getStatusLabel());
            return $returnMessage;
        }
        if ($return === self::TRANSACTION_CAPTURED) {
            $returnMessage = "OK | #{$orderReference} | {$transactionKey} | Transaction captured.";
            $helperLog->info($returnMessage);
            $helperLog->info("Current order status: " . $order->getStatusLabel());
            return $returnMessage;
        }
        // cannot capture transaction
        $returnMessage = "KO | #{$orderReference} | {$transactionKey} | Transaction can't be captured: ";
        $returnMessage .= $return;
        $helperLog->info($returnMessage);
        $helperLog->info("Current order status: " . $order->getStatusLabel());

        return $returnMessage;
    }

    /**
     * Deal with paid/overpaid status
     * from notification post
     */
    public function processPaidStatus($order, $helperLog, $returnMessageLabel, $status, $capturedAmountInCents, $data)
    {
        if ($order->canUnhold()) {
            $order->unhold();
            $helperLog->info("{$returnMessageLabel} | unholded.");
            $helperLog->info("Current order status: " . $order->getStatusLabel());
        }
        if (!$order->canInvoice()) {
            $returnMessage = "OK | {$returnMessageLabel} | Can't create invoice. Transaction status '{$status}' processed.";
            $helperLog->info($returnMessage);
            $helperLog->info("Current order status: " . $order->getStatusLabel());
            return $returnMessage;
        }
        // Partial invoice
        $epsilon = 0.00001;
        if ($order->canInvoice() && abs($order->getGrandTotal() - $capturedAmountInCents * 0.01) > $epsilon) {
            $baseTotalPaid = $order->getTotalPaid();
            // If there is already a positive baseTotalPaid value it's not the first transaction
            if ($baseTotalPaid > 0) {
                $baseTotalPaid += $capturedAmountInCents * 0.01;
                $order->setTotalPaid(0);
            } else {
                $baseTotalPaid = $capturedAmountInCents * 0.01;
                $order->setTotalPaid($baseTotalPaid);
            }
            $accOrderGrandTotal = sprintf($order->getGrandTotal());
            $accBaseTotalPaid = sprintf($baseTotalPaid);
            // Can invoice only if total captured amount is equal to GrandTotal
            if ($accBaseTotalPaid == $accOrderGrandTotal) {
                $result = $this->createInvoice($order, $data, $baseTotalPaid, $status);
                return $result;
            } elseif ($accBaseTotalPaid > $accOrderGrandTotal) {
                $order->setTotalPaid(0);
                $result = $this->createInvoice($order, $data, $baseTotalPaid, $status);
                return $result;
            } else {
                $order->save();
                $returnMessage = "OK | {$returnMessageLabel} | ";
                $returnMessage .= "Captured amount isn't equal to grand total, invoice not created.";
                $returnMessage .= "Transaction status '{$status}' received.";
                $helperLog->info($returnMessage);
                $helperLog->info("Current order status: " . $order->getStatusLabel());
                return $returnMessage;
            }
        }
        // Create invoice
        if ($order->canInvoice() && abs($capturedAmountInCents * 0.01 - $order->getGrandTotal()) < $epsilon) {
            $result = $this->createInvoice($order, $data, $order->getGrandTotal(), $status);
            return $result;
        }
        $returnMessage = "Order {$order->getIncrementId()} | Unable to create invoice for this order.";
        $helperLog->error($returnMessage);
        $helperLog->info("Current order status: " . $order->getStatusLabel());
        $returnMessage = "KO | {$returnMessage}";

        return $returnMessage;
    }

    /**
     * Deal with underpaid status
     * from notification post.
     */
    public function processUnderpaidStatus($order, $helperLog, $returnMessageLabel, $capturedAmountInCents, $status)
    {
        if ($order->canUnhold()) {
            $helperLog->info("{$returnMessageLabel} | unholded.");
            $order->unhold();
        }
        $order->addStatusHistoryComment('MP - Captured offline amount of R$' . $capturedAmountInCents * 0.01, false);
        $order->setState(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT, 'underpaid');
        $order->setBaseTotalPaid($capturedAmountInCents * 0.01);
        $order->setTotalPaid($capturedAmountInCents * 0.01);
        $order->save();
        $returnMessage = "OK | {$returnMessageLabel} | Transaction status '{$status}' processed. Order status updated.";
        $helperLog->info($returnMessage);
        $helperLog->info("Current order status: " . $order->getStatusLabel());

        return $returnMessage;
    }

    /**
     * Deal with NotAuthorized status
     * from notification post.
     */
    public function processNotAuthorizedStatus($order, $transactionData, $returnMessageLabel, $helperLog)
    {
        $helper = Mage::helper('mundipagg');
        $grandTotal = $order->getGrandTotal();
        $grandTotalInCents = $helper->formatPriceToCents($grandTotal);
        $amountInCents = $transactionData['AmountInCents'];

        // if not authorized amount equal to order grand total, order must be canceled
        if (sprintf($amountInCents) != sprintf($grandTotalInCents)) {
            $returnMessage = "OK | {$returnMessageLabel} | Order grand_total not equal to transaction AmountInCents";
            $helperLog->info($returnMessage);
            $helperLog->info("Current order status: " . $order->getStatusLabel());
            return $returnMessage;
        }

        try {
            // set flag to prevent send back a cancelation to Mundi via API
            $this->setCanceledByNotificationFlag($order, true);
            $this->tryCancelOrder($order);
        } catch (Exception $e) {
            $returnMessage = "OK | {$returnMessageLabel} | {$e->getMessage()}";
            $helperLog->info($returnMessage);
            $helperLog->info("Current order status: " . $order->getStatusLabel());
            return $returnMessage;
        }

        $returnMessage = "OK | {$returnMessageLabel} | Order canceled: total amount not authorized";
        $helperLog->info($returnMessage);
        $helperLog->info("Current order status: " . $order->getStatusLabel());

        return $returnMessage;
    }

    /**
     * Deal with canceled, refunded and voided
     * status from notification post.
     */
    public function processCanceledStatus($order, $returnMessageLabel, $helperLog, $status)
    {
        if ($order->canUnhold()) {
            $helperLog->info("{$returnMessageLabel} unholded.");
            $order->unhold();
        }

        $success = false;
        $invoices = array();
        $canceledInvoices = array();

        foreach ($order->getInvoiceCollection() as $invoice) {
            // We check if invoice can be refunded
            if ($invoice->canRefund()) {
                $invoices[] = $invoice;
            }
            // We check if invoice has already been canceled
            if ($invoice->isCanceled()) {
                $canceledInvoices[] = $invoice;
            }
        }

        // Refund invoices and Credit Memo
        if (!empty($invoices) || !empty($canceledInvoices)) {
            $service = Mage::getModel('sales/service_order', $order);
            foreach ($invoices as $invoice) {
                $this->closeInvoice($invoice);
                $this->createCreditMemo($invoice, $service);
            }
            $this->closeOrder($order);
            $success = true;
        }

        if (empty($invoices) && empty($canceledInvoices)) {
            // Cancel order
            $order->cancel()->save();
            $helperLog->info("{$returnMessageLabel} | Order canceled.");
            $helperLog->info("Current order status: " . $order->getStatusLabel());
            // Return
            $success = true;
        }

        if ($success) {
            $returnMessage = "{$returnMessageLabel} | Order status '{$status}' processed.";
            $helperLog->info($returnMessage);
            $helperLog->info("Current order status: " . $order->getStatusLabel());
            return "OK | {$returnMessage}";
        } else {
            $returnMessage = "{$returnMessageLabel} | Unable to process transaction status '{$status}'.";
            $helperLog->info($returnMessage);
            $helperLog->info("Current order status: " . $order->getStatusLabel());
            return "KO | {$returnMessage}";
        }
    }

    /**
     * Deal with AuthorizedPendingCaptured status
     * from notification post.
     */
    public function processAuthorizedPendingCaptureStatus($order, $helperLog, $status)
    {
        $returnMessage = "OK | Order #{$order->getIncrementId()} | Transaction status '{$status}' received from post notification.";
        $helperLog->info($returnMessage);
        $helperLog->info("Current order status: " . $order->getStatusLabel());

        return $returnMessage;
    }

    /**
     * Deal with WithError status
     * from notification post.
     */
    public function processWithErrorStatus($order, $helperLog, $returnMessageLabel)
    {
        try {
            Uecommerce_Mundipagg_Model_Standard::transactionWithError($order, false);
            $returnMessage = "OK | {$returnMessageLabel} | Order changed to WithError status";
        } catch (Exception $e) {
            $returnMessage = "KO | {$returnMessageLabel} | {$e->getMessage()}";
        }
        $helperLog->info($returnMessage);
        $helperLog->info("Current order status: " . $order->getStatusLabel());

        return $returnMessage;
    }

    /**
     * Deal with other status
     * from notification post.
     */
    public function processNotRecognizedStatus($exception, $helperLog)
    {
        $returnMessage = "Internal server error | {$exception->getCode()} - ErrMsg: {$exception->getMessage()}";
        //Log error
        $helperLog->error($exception, true);
        //Mail error
        $this->mailError(print_r($exception->getMessage(), 1));

        return "KO | {$returnMessage}";
    }

    public function processDefaultStatus($order,$status, $helperLog)
    {
        $returnMessage = "Order #{$order->getIncrementId()} | unexpected transaction status: {$status}";
        $helperLog->info($returnMessage);
        return "OK | {$returnMessage}";
    }
}