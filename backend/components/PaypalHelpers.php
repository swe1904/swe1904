<?php
/**
 * Created by PhpStorm.
 * User: rahulsinghmatharu
 * Date: 17/02/15
 * Time: 10:51 AM
 */

namespace app\components;

// Wrapper methods for all PayPal integration

use PayPal\Api\PaymentExecution;

use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\CreditCardToken;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PaypalHelpers {
    /**
     * Helper method for getting an APIContext for all calls
     * @return ApiContext
     */
    public static function getApiContext()
    {
//        $clientId = 'Ae4ZO_UEryuNXSqpPgTrjzpg-u5ec9h_CIqMILdjGkdO9nflwaK4McxZpUf0UKusARpGCTMUAHxnYHTI';
//        $clientSecret = 'EPkPvo2_D_hPQpSmGSvHtkNCmjIeY5lRiTrazauGAjyM0NxqOpamoNz0hTDgGlQO5vcT4Ylnv3AkBm54';
        $clientId = 'Af3FOaFevUTEXDEr-7S-5vuieWj7Ro-b-33BZLnNgQFgpuR3vfnVl3h-zONwAag-rlr3UPL1CRdRW77e';
        $clientSecret = 'EA7BpYRPZ1lQyf7lFLiea2cvQlCxKxYpVa2wTXRVe96BoPONHgiVj22H8IEso8wNEJS1jkyHVKnHb9R9';

        // #### SDK configuration
        // Register the sdk_config.ini file in current directory
        // as the configuration source.
        /*
        if(!defined("PP_CONFIG_PATH")) {
            define("PP_CONFIG_PATH", __DIR__);
        }
        */


        // ### Api context
        // Use an ApiContext object to authenticate
        // API calls. The clientId and clientSecret for the
        // OAuthTokenCredential class can be retrieved from
        // developer.paypal.com

        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                $clientId,
                $clientSecret
            )
        );

        // Comment this line out and uncomment the PP_CONFIG_PATH
        // 'define' block if you want to use static file
        // based configuration

        $apiContext->setConfig(
            array(
                'mode' => 'sandbox',
                'log.LogEnabled' => true,
                'log.FileName' => '../PayPal.log',
                'log.LogLevel' => 'DEBUG', // PLEASE USE `FINE` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
                'validation.level' => 'log',
                'cache.enabled' => true,
                // 'http.CURLOPT_CONNECTTIMEOUT' => 30
                // 'http.headers.PayPal-Partner-Attribution-Id' => '123123123'
            )
        );

        // Partner Attribution Id
        // Use this header if you are a PayPal partner. Specify a unique BN Code to receive revenue attribution.
        // To learn more or to request a BN Code, contact your Partner Manager or visit the PayPal Partner Portal
        // $apiContext->addRequestHeader('PayPal-Partner-Attribution-Id', '123123123');

        return $apiContext;
    }

    /**
     * Save a credit card with paypal
     *
     * This helps you avoid the hassle of securely storing credit
     * card information on your site. PayPal provides a credit card
     * id that you can use for charging future payments.
     *
     * @param array $params	credit card parameters
     */

    public static function saveCard($params) {

        $card = new CreditCard();
        $card->setType($params['type']);
        $card->setNumber($params['number']);
        $card->setExpireMonth($params['expire_month']);
        $card->setExpireYear($params['expire_year']);
        $card->setCvv2($params['cvv2']);

        $card->create(self::getApiContext());
        return $card->getId();
    }

    /**
     *
     * @param string $cardId credit card id obtained from
     * a previous create API call.
     */
    public static function getCreditCard($cardId) {
        return CreditCard::get($cardId, self::getApiContext());
    }


    /**
     * Create a payment using a previously obtained
     * credit card id. The corresponding credit
     * card is used as the funding instrument.
     *
     * @param string $creditCardId credit card id
     * @param string $total Payment amount with 2 decimal points
     * @param string $currency 3 letter ISO code for currency
     * @param string $paymentDesc
     */
    public static function makePaymentUsingCC($creditCardId, $total, $currency, $paymentDesc) {

        $ccToken = new CreditCardToken();
        $ccToken->setCreditCardId($creditCardId);

        $fi = new FundingInstrument();
        $fi->setCreditCardToken($ccToken);

        $payer = new Payer();
        $payer->setPaymentMethod("credit_card");
        $payer->setFundingInstruments(array($fi));

        // Specify the payment amount.
        $amount = new Amount();
        $amount->setCurrency($currency);
        $amount->setTotal($total);
        // ###Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. Transaction is created with
        // a `Payee` and `Amount` types
        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setDescription($paymentDesc);

        $payment = new Payment();
        $payment->setIntent("sale");
        $payment->setPayer($payer);
        $payment->setTransactions(array($transaction));

        $payment->create(self::getApiContext());
        return $payment;
    }

    /**
     * Create a payment using the buyer's paypal
     * account as the funding instrument. Your app
     * will have to redirect the buyer to the paypal
     * website, obtain their consent to the payment
     * and subsequently execute the payment using
     * the execute API call.
     *
     * @param string $total	payment amount in DDD.DD format
     * @param string $currency	3 letter ISO currency code such as 'USD'
     * @param string $paymentDesc	A description about the payment
     * @param string $returnUrl	The url to which the buyer must be redirected
     * 				to on successful completion of payment
     * @param string $cancelUrl	The url to which the buyer must be redirected
     * 				to if the payment is cancelled
     * @return \PayPal\Api\Payment
     */

    public static function makePaymentUsingPayPal($total, $currency, $paymentDesc, $returnUrl, $cancelUrl) {

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        // Specify the payment amount.
        $amount = new Amount();
        $amount->setCurrency($currency);
        $amount->setTotal($total);

        // ###Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. Transaction is created with
        // a `Payee` and `Amount` types
        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setDescription($paymentDesc);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($returnUrl);
        $redirectUrls->setCancelUrl($cancelUrl);

        $payment = new Payment();
        $payment->setRedirectUrls($redirectUrls);
        $payment->setIntent("sale");
        $payment->setPayer($payer);
        $payment->setTransactions(array($transaction));

        $payment->create(self::getApiContext());
        return $payment;
    }


    /**
     * Completes the payment once buyer approval has been
     * obtained. Used only when the payment method is 'paypal'
     *
     * @param string $paymentId id of a previously created
     * 		payment that has its payment method set to 'paypal'
     * 		and has been approved by the buyer.
     *
     * @param string $payerId PayerId as returned by PayPal post
     * 		buyer approval.
     */
    public static function executePayment($paymentId, $payerId) {

        $payment = self::getPaymentDetails($paymentId);
        $paymentExecution = new PaymentExecution();
        $paymentExecution->setPayerId($payerId);
        $payment = $payment->execute($paymentExecution, self::getApiContext());

        return $payment;
    }

    /**
     * Retrieves the payment information based on PaymentID from Paypal APIs
     *
     * @param $paymentId
     *
     * @return Payment
     */
    public static function getPaymentDetails($paymentId) {
        $payment = Payment::get($paymentId, self::getApiContext());
        return $payment;
    }

    /**
     * Utility method that returns the first url of a certain
     * type. Returns empty string if no match is found
     *
     * @param array $links
     * @param string $type
     * @return string
     */
    public static function getLink(array $links, $type) {
        foreach($links as $link) {
            if($link->getRel() == $type) {
                return $link->getHref();
            }
        }
        return "";
    }

} 