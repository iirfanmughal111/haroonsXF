<?php

namespace devsell\BitcoinPayments\Payment;

use XF\Entity\PurchaseRequest;
use XF\Mvc\Controller;
use XF\Payment\AbstractProvider;
use XF\Payment\CallbackState;
use XF\Purchasable\Purchase;

/*
 * Provider: Coinbase Commerce
 * Also referenced as: CbCommerce, CoinbaseCommerce, Coinbase
 */

class CoinbaseCommerce extends AbstractProvider
{
    protected $cbVersion = '2018-03-22';

    public function getTitle()
    {
        return 'Coinbase Commerce';
    }

    public function getApiEndpoint()
    {
        return 'https://api.commerce.coinbase.com';
    }

    public function verifyConfig(array &$options, &$errors = [])
    {
        if (empty($options['api_key']))
        {
            $errors[] = \XF::phrase('ncp_you_must_provide_an_api_key');
            return false;
        }

        if (empty($options['webhook_secret']))
        {
            $errors[] = \XF::phrase('ncp_you_must_provide_a_webhook_secret');
            return false;
        }

        return true;
    }

    protected function getPaymentParams(PurchaseRequest $purchaseRequest, Purchase $purchase)
    {
        $paymentProfile = $purchase->paymentProfile;
        $apiKey = $paymentProfile->options['api_key'];

        return [
            'purchaseRequest' => $purchaseRequest,
            'paymentProfile' => $paymentProfile,
            'purchaser' => $purchase->purchaser,
            'purchase' => $purchase,
            'purchasableTypeId' => $purchase->purchasableTypeId,
            'purchasableId' => $purchase->purchasableId,
            'apiKey' => $apiKey,
            'cost' => $purchase->cost
        ];
    }

    public function initiatePayment(Controller $controller, PurchaseRequest $purchaseRequest, Purchase $purchase)
    {
        $paymentRepo = \XF::repository('XF:Payment');
        $apiKey = $purchase->paymentProfile->options['api_key'];

        try
        {
            $client = $this->getHttpClient();

            $chargeParams = [
                'name' => $purchase->title,
                'description' => $purchase->description,
                'pricing_type' => 'fixed_price',
                'local_price' => [
                    'amount' => $purchase->cost,
                    'currency' => $purchase->currency
                ],
                'metadata' => [
                    'user_id' => $purchase->purchaser->user_id,
                    'purchaseableTypeId' => $purchase->purchasableTypeId,
                    'purchaseableId' => $purchase->purchasableId,
                    'request_key' => $purchaseRequest->request_key
                ],
                'redirect_url' => $purchase->returnUrl,
                'cancel_url' => $purchase->cancelUrl
            ];

            $charge = \GuzzleHttp\json_decode($client->post($this->getApiEndpoint() . '/charges/', [
                    'form_params' => $chargeParams,
                    'headers' => ['X-CC-Api-Key' => [$apiKey]]
                ] + $this->getHeaders())->getBody()->getContents(), true);
            $chargeData = $charge['data'];
            $transactionId = $chargeData['id'];
        } catch (\GuzzleHttp\Exception\ClientException $e)
        {
            $error = \GuzzleHttp\json_decode($e->getResponse()->getBody()->getContents(), true);
            $message = isset($error['error']['message']) ? $error['error']['message'] : '';

            throw $controller->exception($controller->noPermission($message));
        } catch (\GuzzleHttp\Exception\RequestException $e)
        {
            \XF::logException($e, false, "Coinbase error: ");

            throw $controller->exception($controller->error(\XF::phrase('something_went_wrong_please_try_again')));
        }

        $paymentRepo->logCallback(
            $purchaseRequest->request_key,
            $this->providerId,
            $transactionId,
            'info',
            'Charge created',
            [
                'charge' => $charge
            ],
            $purchase->purchaser->user_id
        );

        //$viewParams = $this->getPaymentParams($purchaseRequest, $purchase) + ['charge' => $chargeData, 'paymentUrl' => $chargeData['hosted_url']];
        //return $controller->view('devsell/BitcoinPayments:Purchase\CbCommerceInitiate', 'ncp_payment_initiate_cbcommerce', $viewParams);

        return $controller->redirect($chargeData['hosted_url']);
    }

    /**
     * @param \XF\Http\Request $request
     *
     * @return CallbackState
     */
    public function setupCallback(\XF\Http\Request $request)
    {
        $state = new CallbackState();

        $inputRaw = $request->getInputRaw();
        $state->inputRaw = $inputRaw;
        $state->headers = getallheaders();
        $state->signature = isset($_SERVER['HTTP_X_CC_WEBHOOK_SIGNATURE']) ? $_SERVER['HTTP_X_CC_WEBHOOK_SIGNATURE'] : '';

        $input = @json_decode($inputRaw, true);
        $filtered = \XF::app()->inputFilterer()->filterArray($input ?: [], [
            'id' => 'uint',
            'scheduled_for' => 'str',
            'event' => 'array'
        ]);

        $event = $filtered['event'];

        if ($event)
        {
            $state->requestKey = isset($event['data']['metadata']['request_key']) ? $event['data']['metadata']['request_key'] : '';
            $state->transactionId = isset($event['data']['id']) ? $event['data']['id'] : '';
            $state->eventType = isset($event['type']) ? $event['type'] : '';
            $state->event = $event;
        }

        return $state;
    }

    public function validateCallback(CallbackState $state)
    {
        // Coinbase guidelines:
        // Respond with 200 to acknowledge receipt of webhook.
        // Any other code, or non-acknowledgement -> resending of webhook hourly

        if (!$state->signature)
        {
            $state->logType = 'error';
            $state->logMessage = 'Webhook received from Coinbase does not contain a signature.';
            $state->httpCode = 500;

            return false;
        }

        if (!$this->verifyCallbackSignature($state->inputRaw, $state->signature, $state->getPaymentProfile()->options['webhook_secret']))
        {
            $state->logType = 'error';
            $state->logMessage = 'Webhook received from Coinbase could not be verified as being valid. Secret mismtach.';
            $state->httpCode = 500;

            return false;
        }

        $skippableEvents = ['charge:created', 'charge:failed', 'charge:pending'];

        if ($state->eventType && in_array($state->eventType, $skippableEvents))
        {
            $state->logType = 'info';
            $state->logMessage = 'Coinbase: Event "' . htmlspecialchars($state->eventType) . '" processed. No action required.';
            $state->httpCode = 200;
            return false;
        }

        if (!$state->eventType || !$state->requestKey || !$state->event)
        {
            $state->logType = 'error';
            $state->logMessage = 'Webhook received from Coinbase could not be verified as being valid. Invalid payload.';
            $state->httpCode = 500;

            return false;
        }

        $paymentProfile = $state->getPaymentProfile();
        $purchaseRequest = $state->getPurchaseRequest();

        if (!$paymentProfile || !$purchaseRequest)
        {
            $state->logType = 'error';
            $state->logMessage = 'Invalid purchase request or payment profile.';
            return false;
        }

        return true;
    }

    public function getPaymentResult(CallbackState $state)
    {
        switch ($state->eventType)
        {
            case 'charge:confirmed':
            case 'charge:delayed':
                $state->paymentResult = CallbackState::PAYMENT_RECEIVED;
                break;
        }
    }

    public function prepareLogData(CallbackState $state)
    {
        $state->logDetails = $state->event;
        $state->logDetails['eventType'] = $state->eventType;
    }

    public function supportsRecurring(\XF\Entity\PaymentProfile $paymentProfile, $unit, $amount, &$result = self::ERR_NO_RECURRING)
    {
        return false;
    }

    protected function verifyCallbackSignature($payload, $sigHeader, $secret)
    {
        $computedSignature = \hash_hmac('sha256', $payload, $secret);

        if (!\XF\Util\Php::hashEquals($sigHeader, $computedSignature))
        {
            return false;
        }

        return true;
    }

    protected function getHttpClient()
    {
        $client = \XF::app()->http()->client();
        return $client;
    }

    protected function getHeaders()
    {
        return ['headers' => [
            'Content-Type' => 'application/json',
            'X-CC-Version' => $this->cbVersion
        ]];
    }
}