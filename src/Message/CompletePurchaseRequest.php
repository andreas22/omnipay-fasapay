<?php

namespace Omnipay\Fasapay\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Fasapay\Helpers\Security;

/**
 * Dummy Authorize Request
 */
class CompletePurchaseRequest extends AbstractRequest
{
    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    public function getSecret()
    {
        return $this->getParameter('secret');
    }
    public function getData()
    {
        return $this->httpRequest->request->all();
    }

    public function sendData($data)
    {
        // Validation is only for advance mode

        $this->validateHash($data);

        return $this->response = new CompletePurchaseResponse($this, $data);
    }

    /**
     * Check if response is valid, only for advance mode
     *
     * @example data = [fp_paidto, fp_paidby, fp_store, fp_amnt, fp_batchnumber, fp_currency]
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function validateHash(array $data)
    {
        $fpHash = isset($data['fp_hash']) ? $data['fp_hash'] : null;

        if (empty($fpHash)) {
            return true;
        }

        $parameters = $this->getParameters();
        $secret = isset($parameters['secret']) ? $parameters['secret'] : '';

        if (empty($secret)) {
            throw new \Exception("Secret key is required!");
        }

        $response = array(
            $data['fp_paidto'],
            $data['fp_paidby'],
            $data['fp_store'],
            $data['fp_amnt'],
            $data['fp_batchnumber'],
            $data['fp_currency'],
            $secret
        );

        $hash = Security::getHash($response);

        if (strcmp($fpHash, $hash) !== 0) {
            throw new \Exception("Invalid response! Secret key is wrong!");
        }

        return true;
    }
}
