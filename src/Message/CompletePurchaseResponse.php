<?php

namespace Omnipay\Fasapay\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Dummy Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return true;
    }

    public function getTransactionReference()
    {
        return isset($this->data['fp_batchnumber']) ? $this->data['fp_batchnumber'] : null;
    }
}
