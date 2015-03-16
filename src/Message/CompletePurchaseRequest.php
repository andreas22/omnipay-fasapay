<?php

namespace Omnipay\Fasapay\Message;

use Guzzle\Http\ClientInterface;
use Omnipay\Common\Message\AbstractRequest;

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

        $this->validate($data);

        return $this->response = new CompletePurchaseResponse($this, $data);
    }

    /*
     * Check if response is valid, only for advance mode
     */
    public function validate($data)
    {
        $fpHash = isset($data['fp_hash']) ? $data['fp_hash'] : null;

        if(empty($fpHash))
        {
            return true;
        }

        $parameters = $this->getParameters();
        $secret = isset($parameters['secret']) ? $parameters['secret'] : '';

        if(empty($secret))
        {
            throw new \Exception("Secret is required");
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

        $string = implode(':', $response);
        $hash = hash('sha256', $string);

        if(strcmp($fpHash, $hash) !== 0)
        {
            throw new \Exception("Invalid response");
        }

        return true;
    }
}
