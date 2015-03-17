<?php

namespace Omnipay\Fasapay;

use Omnipay\Fasapay\Helpers\Security;
use Omnipay\Tests\GatewayTestCase;

/**
 * Class GatewayTest
 * @package Omnipay\Fasapay
 *
 */
class GatewayTest extends GatewayTestCase
{
    /**
     * @var \Omnipay\Fasapay\Gateway
     */
    protected $gateway;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testPurchaseSimpleMode()
    {
        $purchaseOptions = array(
            'amount' => 1000.0,
            'currency' => 'IDR',
            'transactionId' => '1311059195',
            'accountTo' => 'FP000001',
            'accountFrom' => 'FP000002',
            'store' => 'MyStore',
            'item' => 'MyItem',
            'feeMode' => 'FiS',
            'returnUrl' => 'http://success.com',
            'successMethod' => 'GET',
            'cancelUrl' => 'http://cancel.com',
            'failMethod' => 'GET',
            'notifyUrl' => 'http://notify.com',
            'statusMethod' => 'POST',
            'comments' => 'No comment',
        );

        $request = $this->gateway->purchase($purchaseOptions);
        $requestData = $request->getData();
        $response = $request->send();

        //Response validation
        $this->assertTrue($response->isRedirect());
        $this->assertTrue($response->isSuccessful());
    }

    public function testPurchaseAdvanceMode()
    {
        $purchaseOptions = array(
            'accountTo' => 'FP000001',
            'store' => 'MyStore',
            'item' => 'MyItem',
            'amount' => 1000.0,
            'currency' => 'IDR',
            'comments' => 'No comment',
            'transactionId' => '1311059195',
        );

        $request = $this->gateway->purchase($purchaseOptions);
        $requestData = $request->getData();
        $response = $request->send();

        //Response validation
        $this->assertTrue($response->isRedirect());
        $this->assertTrue($response->isSuccessful());
    }

    public function testCompletePurchaseSimpleModeSuccess()
    {
        $responseParams = array(
            'fp_paidto' => 'FP000001',
            'fp_paidby' => 'FP000002',
            'fp_amnt' => 1000,
            'fp_fee_amnt' => 1000,
            'fp_currency' => 'IDR',
            'fp_batchnumber' => 'DDDADS234234',
            'fp_store' => null,
            'fp_timestamp' => date('Y-m-d H:i:s'),
            'fp_merchant_ref' => '1311059195'

        );
        $request = $this->gateway->completePurchase($responseParams);
        $response = $request->sendData($responseParams);

        //Response validation
        $this->assertTrue($response->isSuccessful());
        $this->assertSame($response->getTransactionReference(), $responseParams['fp_batchnumber']);
    }

    public function testCompletePurchaseAdvanceModeSuccess()
    {
        $secret = 1000;

        $responseParams = array(
            'fp_paidto' => 'FP000001',
            'fp_paidby' => 'FP000002',
            'fp_amnt' => 1000,
            'fp_fee_amnt' => 1000,
            'fp_currency' => 'IDR',
            'fp_batchnumber' => 'DDDADS234234',
            'fp_store' => null,
            'fp_timestamp' => date('Y-m-d H:i:s'),
            'fp_merchant_ref' => '1311059195',
        );

        $hash = Security::getHash(array($responseParams['fp_paidto'],
            $responseParams['fp_paidby'],
            $responseParams['fp_store'],
            $responseParams['fp_amnt'],
            $responseParams['fp_batchnumber'],
            $responseParams['fp_currency'],
            $secret));

        $responseParams['fp_hash'] = $hash;

        $request = $this->gateway->completePurchase($responseParams);
        $request->setSecret($secret);
        $response = $request->sendData($responseParams);

        //Response validation
        $this->assertTrue($response->isSuccessful());
        $this->assertSame($response->getTransactionReference(), $responseParams['fp_batchnumber']);
    }

    /**
     * @expectedException        \Exception
     * @expectedExceptionMessage Invalid response
     */
    public function testCompletePurchaseAdvanceModeInvalidHash()
    {
        $responseParams = array(
            'fp_paidto' => 'FP000001',
            'fp_paidby' => 'FP000002',
            'fp_amnt' => 1000,
            'fp_fee_amnt' => 1000,
            'fp_currency' => 'IDR',
            'fp_batchnumber' => 'DDDADS234234',
            'fp_store' => 'my store',
            'fp_timestamp' => date('Y-m-d H:i:s'),
            'fp_merchant_ref' => '1311059195',
            'fp_hash' => 'xxxx'
        );

        $request = $this->gateway->completePurchase($responseParams);
        $request->setSecret(5000);
        $request->sendData($responseParams);
    }

    /**
     * @expectedException        \Exception
     * @expectedExceptionMessage Secret is required
     */
    public function testCompletePurchaseAdvanceModeMissingSecret()
    {
        $responseParams = array(
            'fp_paidto' => 'FP000001',
            'fp_paidby' => 'FP000002',
            'fp_amnt' => 1000,
            'fp_fee_amnt' => 1000,
            'fp_currency' => 'IDR',
            'fp_batchnumber' => 'DDDADS234234',
            'fp_store' => 'my store',
            'fp_timestamp' => date('Y-m-d H:i:s'),
            'fp_merchant_ref' => '1311059195',
            'fp_hash' => 'xxxx'
        );

        $request = $this->gateway->completePurchase($responseParams);
        $request->sendData($responseParams);
    }
}
