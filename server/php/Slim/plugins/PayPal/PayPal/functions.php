<?php
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\CreditCard;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentCard;
use PayPal\Api\Transaction;
use PayPal\Api\Address;
use PayPal\Api\ShippingAddress;
use PayPal\Api\RedirectUrls;
use PayPal\Api\CreditCardToken;
use PayPal\Api\Refund;
use PayPal\Api\RefundRequest;
use PayPal\Api\Sale;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Authorization;
use PayPal\Api\Capture;

/**
 * Helper method for getting an APIContext for all calls
 * @param string $clientId Client ID
 * @param string $clientSecret Client Secret
 * @return PayPal\Rest\ApiContext
 */
function getApiContext()
{
    $paymentGatewaySettings = Models\PaymentGatewaySetting::where('payment_gateway_id', \Constants\PaymentGateways::PayPal)->get();
    foreach ($paymentGatewaySettings as $value) {
        $payPal['sandbox'][$value->name] = $value->test_mode_value;
        $payPal['live'][$value->name] = $value->live_mode_value;
    }
    $sanbox_mode = 'live';
    $payment_gateways = Models\PaymentGateway::select('is_test_mode')->where('id', \Constants\PaymentGateways::PayPal)->first();
    if (!empty($payment_gateways->is_test_mode)) {
        $sanbox_mode = 'sandbox';
    }
    if ($sanbox_mode == 'sandbox') {
        $payPal = $payPal['sandbox'];
    } else {
        $payPal = $payPal['live'];
    }
    $apiContext = new ApiContext(new OAuthTokenCredential($payPal['paypal_client_id'], $payPal['paypal_client_Secret']));
    $apiContext->setConfig(array(
        'mode' => $sanbox_mode,
        'log.LogEnabled' => false,
        'log.FileName' => APP_PATH . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'logs/PayPal.log',
        'log.LogLevel' => 'DEBUG', // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
        'cache.enabled' => false,
        'cache.FileName' => APP_PATH . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'cache/auth.cache'
    ));
    return $apiContext;
}
function createPayment($id, $body)
{
    global $authUser;
    try {
        $apiContext = getApiContext();
        $payer = new Payer();
        if (empty($body['user_credit_card_id']) && !empty($body['credit_card_type'])) {
            $user_details = Models\User::where('id', $body['user_id'])->with('user_profile')->first();
            $body['first_name'] = $user_details->user_profile->first_name;
            $body['last_name'] = $user_details->user_profile->last_name;
            $card = new CreditCard();
            $card->setType($body['credit_card_type'])
                    ->setNumber($body['credit_card_number'])
                    ->setExpireMonth($body['expire_month'])
                    ->setExpireYear($body['expire_year'])
                    ->setCvv2($body['cvv2'])
                    ->setFirstName($body['first_name'])
                    ->setLastName($body['last_name']);

            $fi = new FundingInstrument();
            $fi->setCreditCard($card);                

            $payer->setPaymentMethod("credit_card")
                    ->setFundingInstruments(array($fi));
            if (!empty($body['is_store_this_card'])) {                
                createVault($body);
            }
        } elseif (!empty($body['user_credit_card_id'])) {
            $creditCardToken = new CreditCardToken();
            $vaults = Models\UserCreditCard::where('id', $body['user_credit_card_id'])->where('user_id', $body['user_id'])->first();
            $creditCardToken->setCreditCardId($vaults['token']);
            $fi = new FundingInstrument();
            $fi->setCreditCardToken($creditCardToken);
            $payer->setPaymentMethod("credit_card")->setFundingInstruments(array(
                $fi
            ));
        } else {
            $payer->setPaymentMethod('paypal');
        }
        $price = $body['amount'];
        $item1 = new Item();
        $item1->setName(substr($body['name'], 0, 100))->setDescription(substr($body['description'], 0, 100))->setCurrency(SITE_CURRENCY_CODE)->setQuantity(1)->setPrice($price);
        $itemLists[] = $item1;
        $itemList = new ItemList();
        $itemList->setItems($itemLists);
        $amount = new Amount();
        $body['amount'] = !empty($body['amount']) ? (double)$body['amount'] : 0;
        $amount->setCurrency(SITE_CURRENCY_CODE)->setTotal((double)$body['amount']);
        $transaction = new Transaction();
        $transaction->setAmount($amount)->setItemList($itemList)->setDescription(substr($body['description'], 0, 100))->setInvoiceNumber(uniqid());
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($body['success_url'])->setCancelUrl($body['cancel_url']);
        $payment = new Payment();
        if (!empty($body['appointment_status_id']) && $body['appointment_status_id'] == \Constants\ConstAppointmentStatus::PreApproved) {
            $payment->setIntent("sale")->setPayer($payer)->setRedirectUrls($redirectUrls)->setTransactions(array(
                $transaction
            ));
        } elseif (!empty($body['appointment_status_id']) && $body['appointment_status_id'] == \Constants\ConstAppointmentStatus::PaymentPending) {
            $payment->setIntent("authorize")->setPayer($payer)->setRedirectUrls($redirectUrls)->setTransactions(array(
                $transaction
            )); 
        } else {
            $payment->setIntent("sale")->setPayer($payer)->setRedirectUrls($redirectUrls)->setTransactions(array(
                $transaction
            ));
        }
        $payment->create($apiContext);
        $payment->message = '';
        return $payment;
    } catch (PayPal\Exception\PayPalConnectionException $ex) {
        $result['error']['code'] = 1;
        $result['error']['message'] = "Payment could not be created " . $ex->getMessage();
        $result['error']['fields'] = '';
        if(!empty($ex->getData())){
           $details = json_decode($ex->getData(),true);
           if(!empty($details['details'])){
               $result['error']['fields'] = $details['details'];
           }elseif(!empty($details)){
               $result['error']['fields'][] = array(
                   'field' => $details['name'],
                   'issue' => $details['message']
               );
           }
        } 
        return $result;
    } catch (Exception $ex) {
        $result['error']['code']  = 1;
        $result['error']['message'] = 'Payment could not be created' . $ex->getMessage();
        $result['error']['fields'] = '';
        return $result;
    }
}
function executePayment($payID, $payerID, $token, $id, $model, $morphModel = '')
{
    $_server_domain_url;
    $results = array();
    $model_name = 'Models' . '\\' . $model;
    $data_response = $model_name::find($id);
    if (!empty($data_response)) {
        try {
            $apiContext = getApiContext();
            $data = array();
            $returnUrls = getReturnURL($model, $data_response);
            $payment = Payment::get($payID, $apiContext);
            $execution = new PaymentExecution();
            $execution->setPayerId($payerID);
            $payment->execute($execution, $apiContext);
            if ($payment->getIntent() == 'sale' && $payment->getState() == 'approved') {
                $transactions = $payment->getTransactions();
                $relatedResources = $transactions[0]->getRelatedResources();
                $sale = $relatedResources[0]->getSale();
                $fee = $sale->transaction_fee->value;
                $capture_id = $sale->getId();
                $payment_response = array(
                    'status' => 'Captured',
                    'paykey' => $payment->getId() ,
                    'capture_id' => $capture_id,
                    'fee' => $fee,
                    'paypal_status' => $payment->getState()
                );
            } elseif ($payment->getIntent() == 'authorize' && $payment->getState() == 'approved') {
                $transactions = $payment->getTransactions();
                $relatedResources = $transactions[0]->getRelatedResources();
                $authorization = $relatedResources[0]->getAuthorization();
                $payment_response = array(
                    'status' => 'Captured',
                    'authorization_id' => $authorization->getId() ,
                    'paypal_status' => $authorization->getState()
                );
            }
            if (!empty($morphModel)) {
                $model_name::processCaptured($payment_response, $id, $morphModel);
            } else {
                $model_name::processCaptured($payment_response, $id);
            }            
            $payment_response['data']['returnUrl'] = $returnUrls['success_url'];
            return $payment_response;
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            $results['data']['returnUrl'] = $returnUrls['failure_url'];
        } catch (Exception $ex) {
            $results['data']['returnUrl'] = $returnUrls['failure_url'];
        }
    } else {
        $results['data']['returnUrl'] = $returnUrls['failure_url'];
    }
    return $results;
}
function getPaypalDetails()
{
    try {
        $paypal_gateway_response = array(
            'error' => array(
                'code' => 0
            ) ,
            'paypal_enabled' => true
        );
        return $paypal_gateway_response;
    } catch (Exception $e) {
        return array(
            'error' => 1,
            'error_message' => $e->getMessage()
        );
    }
}
function authorizePayment($id)
{
    try {
        $apiContext = getApiContext();
        $authorization = new Authorization();
        $result = $authorization->get($id, $apiContext);
        return $result;
    } catch (Exception $e) {
        return array(
            'error' => 1,
            'error_message' => $e->getMessage()
        );
    }
}
function capturePayment($authorization, $pay_amount)
{
    try {
        $apiContext = getApiContext();
        $amt = new Amount();
        $amt->setCurrency(SITE_CURRENCY_CODE)->setTotal((double)$pay_amount);
        $capture = new Capture();
        $capture->setAmount($amt);
        $capture->setIsFinalCapture(true);
        $getCapture = $authorization->capture($capture, $apiContext);
        return $getCapture;
    } catch (Exception $e) {
        return array(
            'error' => 1,
            'error_message' => $e->getMessage()
        );
    }
}
function voidPayment($authorization_id)
{
    try {
        $apiContext = getApiContext();
        $authorization = new Authorization();
        $getAuth = $authorization->get($authorization_id, $apiContext);
        $voidedAuth = $getAuth->void($apiContext);
        return $voidedAuth;
    } catch (Exception $e) {
        return array(
            'error' => 1,
            'error_message' => $e->getMessage()
        );
    }
}
function refundPayment($appointment)
{
    $amt = new Amount();
    $amt->setCurrency(SITE_CURRENCY_CODE)->setTotal((double)$appointment->refunded_amount);
    $refund = new Refund();
    $refund->setAmount($amt);
    $apiContext = getApiContext();
    try {
        $sale = new Sale();
        $sale->setId($appointment->capture_id);
        $refundedSale = $sale->refund($refund, $apiContext);
        return $refundedSale;
    } catch (Exception $ex) {
        return array(
            'error' => 1,
            'error_message' => $ex->getMessage()
        );
    }
}
function createVault($vault_data) {
    global $authUser;
    try {
        $result = array();
        $apiContext = getApiContext();
        $card = new CreditCard();
        $card->setType($vault_data['credit_card_type'])
            ->setNumber($vault_data['credit_card_number'])
            ->setExpireMonth($vault_data['expire_month'])
            ->setExpireYear($vault_data['expire_year'])
            ->setCvv2($vault_data['cvv2'])
            ->setFirstName($vault_data['first_name'])
            ->setLastName($vault_data['last_name']);
        $card->create($apiContext);
        $userCreditCard = new Models\UserCreditCard;
        $userCreditCard->user_id = $vault_data['user_id'];
        $userCreditCard->credit_card_type = $card->getType();
        if(!empty($vault_data['name_on_the_card'])){
            $userCreditCard->name_on_the_card = $vault_data['name_on_the_card'];
        }else{
            $userCreditCard->name_on_the_card = '';            
        }
        $userCreditCard->credit_card_expire = $card->getExpireMonth().'/'.$card->getExpireYear();
        $userCreditCard->token = $card->getId();
        $userCreditCard->masked_card_number = $card->getNumber();
        $userCreditCard->cvv2 = $card->getCvv2();
        $userCreditCard->payment_gateway_id = \Constants\PaymentGateways::PayPal;
        $userCreditCard->save();       
        if (!empty($userCreditCard)) {
            $result['data'] = $userCreditCard;
            $result['error']['code'] = 0;
            $result['error']['message'] = "Card details has been added successfully";
        } else {
            $result['error']['code'] = 1;
            $result['error']['message'] = "Card details could not be saved";
        }
    } catch (PayPal\Exception\PayPalConnectionException $ex) {
        $result['error']['code'] = 1;      
        $result['error']['message'] = "Card details could not be saved " . $ex->getMessage();
        $result['error']['fields'] = !empty($ex->getData()) ? json_decode($ex->getData(), true)['details'] : array();        
    } catch (Exception $ex) {
        $result['error']['code'] = 1;
        $result['error']['message'] = "Card details could not be saved " . $ex->getMessage();
    }
    return $result;
}
function deleteVault($vaultDetail) {
    $results = array();
    try {                   
        $apiContext = getApiContext();
        $card = CreditCard::get($vaultDetail['token'], $apiContext);
        if ($card->delete($apiContext)) {
            Models\UserCreditCard::where('id', $vaultDetail['id'])->delete();
            if ($vaultDetail['is_primary'] == 1) {
                $vaultOne = Models\UserCreditCard::where('user_id', $vaultDetail['user_id'])->first();
                if ($vaultOne) {
                    Models\UserCreditCard::where('id', $vaultOne['id'])->update(['is_primary' => 1]);
                }
            }
            $results['error']['code'] = 0;
            $results['error']['message'] = 'Card detail has been deleted successfully.';
        }
    } catch (PayPal\Exception\PayPalConnectionException $ex) {
        $results['error']['code'] = 1;
        $results['error']['message'] = "Card details could not be saved ". $ex->getMessage();
    } catch (Exception $ex) {
        $results['error']['code'] = 1;
        $results['error']['message'] = "Card details could not be saved " . $ex->getMessage();
    }
    return $results;
}