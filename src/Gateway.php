<?php

namespace Omnipay\Fasapay;

use Omnipay\Common\AbstractGateway;

/**
 * Fasapay Gateway
 *
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Fasapay';
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Fasapay\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Fasapay\Message\PurchaseRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Fasapay\Message\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Fasapay\Message\CompletePurchaseRequest', $parameters);
    }
}
