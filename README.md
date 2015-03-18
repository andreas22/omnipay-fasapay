# Omnipay: Fasapay

**Fasapay driver for the Omnipay PHP payment processing library**

[![Build Status](https://travis-ci.org/andreas22/omnipay-fasapay.svg?branch=master)](https://travis-ci.org/andreas22/omnipay-fasapay)
[![Latest Stable Version](https://poser.pugx.org/andreas22/omnipay-fasapay/v/stable.svg)](https://packagist.org/packages/andreas22/omnipay-fasapay) 
[![Total Downloads](https://poser.pugx.org/andreas22/omnipay-fasapay/downloads.svg)](https://packagist.org/packages/andreas22/omnipay-fasapay) 
[![Latest Unstable Version](https://poser.pugx.org/andreas22/omnipay-fasapay/v/unstable.svg)](https://packagist.org/packages/andreas22/omnipay-fasapay) 
[![License](https://poser.pugx.org/andreas22/omnipay-fasapay/license.svg)](https://packagist.org/packages/andreas22/omnipay-fasapay)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements Fasapay support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "andreas22/omnipay-fasapay": "1.*"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## F.a.q

* What gateways are provided by this package

    Fasapay

* How fasapay works?

    A client fills the form on your side and then submits the form. Then the client will be redirected to Fasapay
    checkout page to complete the payment. Once the payment has been completed the client has the option to return 
    back to your website and at the same time a callback is send from Fasapay to your notify url.

* What are simple and advance modes?

    Fasapay supports 2 modes, simple mode where you need to provide all the information like urls and advance mode
    where you need to setup a store first in which you will specify success, fail, status url and a secret key
    for verifying the callback origin for security reasons.

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

## Sample Codes

###Purchase Request (Simple Mode)

Create a file to handle the client purchase request form data called purchase-form.php and copy/paste the below code.

    <?php
    include_once 'vendor/autoload.php';

    use Omnipay\Omnipay;

    $gateway = Omnipay::create('Fasapay');

     // Example form data
     $purchaseOptions = array(
         'accountTo' => 'FPX6553',
         'accountFrom' => 'FPX6685',
         'item' => 'MyItem',
         'amount' => 1000.0,
         'currency' => 'IDR',
         'comments' => 'No comment',
         'transactionId' => '1311059195',
         'returnUrl' => 'http://requestb.in/zo1agozo',  //Success url
         'successMethod' => 'GET',
         'cancelUrl' => 'http://requestb.in/zo1agozo',  //Cancel url
         'failMethod' => 'GET',
         'notifyUrl' => 'http://requestb.in/1l8z6pl1',  //Callback url - server to server
         'statusMethod' => 'POST',
     );
    
     $response = $gateway->purchase($purchaseOptions)->setTestMode(true)->send();
    
     // Process response
     if ($response->isSuccessful()) {
         // Payment was successful
         echo 'SUCCESS';
     }
     elseif ($response->isRedirect()) {
         // Redirect to offsite payment gateway
         $response->redirect();
     }
     else {
         // Payment failed
         echo 'FAILED :: ' .$response->getMessage();
     }
    ?>

###Purchase Callback (Simple Mode)

Create a file that will handle the (notifyUrl) callback from Fasapay called callback.php and copy/paste the below code.
 
    <?php
     include_once 'vendor/autoload.php';
    
     use Omnipay\Omnipay;
    
     $gateway = Omnipay::create('Fasapay');
    
     // Send purchase request
     $response = $gateway->completePurchase()->send();
    
     // Process response
     if ($response->isSuccessful())
     {
         echo '[success] TransactionReference=' . $response->getTransactionReference();
     }
     else
     {
         echo 'Fail';
     }
     ?>

###Purchase Request (Advance Mode)

 **For advance mode you need to setup a store in Fasapay first!**

 **Fasapay sandbox back office url: http://sandbox.fasapay.com/register/create**

 Create a file to handle the client purchase request form data called form.php and copy/paste the below code.
    
    <?php
    include_once 'vendor/autoload.php';
    
    use Omnipay\Omnipay;
    
    $gateway = Omnipay::create('Fasapay');
    
    // Example form data
    $purchaseOptions = array(
      'accountTo' => 'FPX6553', //Client that will send you the money
      'store' => 'MyStore',
      'item' => 'MyItem',
      'amount' => 1000.0,
      'currency' => 'IDR',
      'comments' => 'No comment',
      'transactionId' => '1311059195',
    );
    
    $response = $gateway->purchase($purchaseOptions)->setTestMode(true)->send();
    
    // Process response
    if ($response->isSuccessful()) {
      // Payment was successful
      echo 'SUCCESS';
    }
    elseif ($response->isRedirect()) {
      // Redirect to offsite payment gateway
      $response->redirect();
    }
    else {
      // Payment failed
      echo 'FAILED :: ' .$response->getMessage();
    }
    ?>

###Purchase Callback (Advance Mode)

**The secret key specified during the store creation is required!**

Create a file that will handle the (notifyUrl) callback from Fasapay called callback.php and copy/paste the below code.
  
    <?php
    $secret = 'xxxxx';
    
    include_once 'vendor/autoload.php';
    
    use Omnipay\Omnipay;
    
    $gateway = Omnipay::create('Fasapay');
    
    // Send purchase request
    $response = $gateway->completePurchase()->setSecret($secret)->send();
    
    // Process response
    if ($response->isSuccessful())
    {
        echo '[success] TransactionReference=' . $response->getTransactionReference();
    }
    else
    {
        echo 'Fail';
    }
    ?>


## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/thephpleague/omnipay-dummy/issues),
or better yet, fork the library and submit a pull request.
