<?php
/**
 * Payment
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    GETLANCERV3
 * @subpackage Model
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Models;

class Payment extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = false;
    public function processPayment($id, $body, $type, $model = '')
    {
        global $_server_domain_url;
        $modelName = 'Models' . '\\' . $type;
        $payment_response = array();
        if ($body['payment_gateway_id'] == \Constants\PaymentGateways::PayPal) {
            require_once APP_PATH . DIRECTORY_SEPARATOR . 'server' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Slim/plugins' . DIRECTORY_SEPARATOR . 'PayPal' . DIRECTORY_SEPARATOR .'PayPal' . DIRECTORY_SEPARATOR . 'functions.php';
            $apiContext = getApiContext();
            $body['success_url'] = $_server_domain_url . '/api/v1/paypal/process_payment?id=' . $id . '&model=' . $type. '&morphModel=' . $model;;
            $data_response = $modelName::find($body['id']);
            $payment = createPayment($id, $body);      
            if (is_object($payment)){      
              if (!empty($payment) && empty($payment->message)) {
                if ($payment->getState() == 'created') {
                    if ($body['class'] == 'Appointment') {
                        $data_response->paypal_status = $payment->getState();
                        $data_response->update();
                    }
                    $payment->status = 'Initiated';
                    if (!empty($payment->getApprovalLink())) {
                        $response = array(
                            'data' => $data_response,
                            'redirect_url' => $payment->getApprovalLink() ,
                            'payment_response' => $payment->toArray() ,
                            'error' => array(
                                'code' => 0,
                                'message' => 'redirect to payment url',
                                'fields' => ''
                            )
                        );
                    } else {
                        $response = array(
                            'data' => $data_response,
                            'payment_response' => $payment->toArray() ,
                            'error' => array(
                                'code' => 0,
                                'message' => 'Initiated Payment without error code',
                                'fields' => ''
                            )
                        );
                    }
                } elseif ($payment->getState() == 'approved') {

                    if ($payment->getIntent() == 'sale') {
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
                    } elseif ($payment->getIntent() == 'authorize') {
                        $transactions = $payment->getTransactions();
                        $relatedResources = $transactions[0]->getRelatedResources();
                        $authorization = $relatedResources[0]->getAuthorization();
                        $payment_response = array(
                            'status' => 'Captured',
                            'authorization_id' => $authorization->getId() ,
                            'paypal_status' => $authorization->getState()
                        );
                    }
                    if (!empty($model)) {
                        $modelName::processCaptured($payment_response, $id, $model);
                    } else {
                        $modelName::processCaptured($payment_response, $id);
                    }                    
                    $data_response = $modelName::find($body['id']);
                    $response = array(
                        'data' => $data_response,
                        'payment_response' => $payment->toArray() ,
                        'error' => array(
                            'code' => 0,
                            'message' => 'order successfully completed'
                        )
                    );
                } else {
                    $response = array(
                        'data' => '',
                        'payment_response' => $payment_response,
                        'error' => array(
                            'code' => 1,
                            'message' => 'Payment could not be completed.Please try again...',
                            'fields' => ''
                        )
                    );
                }
            } else {
                $response = array(
                    'data' => $payment->data,
                    'payment_response' => $payment->message,
                    'error' => array(
                        'code' => 1,
                        'message' => 'Payment could not be completed.Please try again...',
                        'fields' => ''
                    )
                );
            }
        } else {
            $response = array(
                'error'=> array(
                    "code" => 1,
                    "message" => $payment['error']['message'],
                    "raw_message" => "",
                    "fields" => $payment['error']['fields']
                )
            );
        }
        } else {
            $response = array(
                'data' => '',
                'payment_response' => $payment_response,
                'error' => array(
                    'code' => 1,
                    'message' => 'Payment could not be completed.Please try again...',
                    'fields' => ''
                )
            );
        }
        return $response;
    }
}
