<?php

namespace Omnipay\Fasapay\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Dummy Authorize Request
 */
class PurchaseRequest extends AbstractRequest
{
    public $liveEndpoint = 'https://sci.fasapay.com/';

    public $sandboxEndpoint = 'https://sandbox.fasapay.com/sci/';

    public function getData()
    {
        $data = array(
            'fp_acc' => $this->getParameter('accountTo'),
            'fp_acc_from' => $this->getParameter('accountFrom'),
            'fp_store' => $this->getParameter('store'),
            'fp_item"' => $this->getParameter('item'),
            'fp_amnt' => $this->getAmount(),
            'fp_currency' => $this->getCurrency(),
            'fp_fee_mode' => $this->getParameter('feeMode'),
            'fp_success_url' => $this->getReturnUrl(),
            'fp_success_method' => $this->getParameter('successMethod'),
            'fp_fail_url' => $this->getCancelUrl(),
            'fp_fail_method' => $this->getParameter('failMethod'),
            'fp_status_url' => $this->getNotifyUrl(),
            'fp_status_method' => $this->getParameter('statusMethod'),
            'fp_comments' => $this->getParameter('comments'),
            'fp_merchant_ref' => $this->getTransactionId(),
        );

        return $data;
    }

    public function sendData($data)
    {
        return new PurchaseResponse($this, $data, $this->getEndpoint());
    }

    // Getters

    public function getEndpoint()
    {
        return ((bool)$this->getTestMode()) ? $this->sandboxEndpoint : $this->liveEndpoint;
    }

    public function getAccountTo()
    {
        return $this->getParameter('accountTo');
    }

    public function getAccountFrom()
    {
        return $this->getParameter('accountFrom');
    }

    public function getStore()
    {
        return $this->getParameter('store');
    }

    public function getItem()
    {
        return $this->getParameter('item');
    }

    public function getFeeMode()
    {
        return $this->getParameter('feeMode');
    }

    public function getSuccessMethod()
    {
        return $this->getParameter('successMethod');
    }

    public function getFailMethod()
    {
        return $this->getParameter('failMethod');
    }

    public function getStatusMethod()
    {
        return $this->getParameter('statusMethod');
    }

    public function getComments()
    {
        return $this->getParameter('comments');
    }

    // Setters

    public function setAccountTo($value)
    {
        return $this->setParameter('accountTo', $value);
    }

    public function setAccountFrom($value)
    {
        return $this->setParameter('accountFrom', $value);
    }

    public function setStore($value)
    {
        return $this->setParameter('store', $value);
    }

    public function setItem($value)
    {
        return $this->setParameter('item', $value);
    }

    public function setFeeMode($value)
    {
        return $this->setParameter('feeMode', $value);
    }

    public function setSuccessMethod($value)
    {
        return $this->setParameter('successMethod', $value);
    }

    public function setFailMethod($value)
    {
        return $this->setParameter('failMethod', $value);
    }

    public function setStatusMethod($value)
    {
        return $this->setParameter('statusMethod', $value);
    }

    public function setComments($value)
    {
        return $this->setParameter('comments', $value);
    }
}
