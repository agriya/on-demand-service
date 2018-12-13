<?php
/**
 * GET paymentGatewayGet
 * Summary: Get  payment gateways
 * Notes: Filter payment gateway.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/payment_gateways', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $count = PAGE_LIMIT;
        if (!empty($queryParams['limit'])) {
            $count = $queryParams['limit'];
        }
        $paymentGateways = Models\PaymentGateway::Filter($queryParams)->paginate($count)->toArray();
        $data = $paymentGateways['data'];
        unset($paymentGateways['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $paymentGateways
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canListPaymentGateway'));

/**
 * GET Paymentgateways list.
 * Summary: Paymentgateway list.
 * Notes: Paymentgateways list.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/payment_gateways/list', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $data = array();
    try {
        if (isPluginEnabled('PayPal/PayPal')) {
            $data['PayPalREST'] = array(
                'paypalrest_enabled' => true
            );
        }
        return renderWithJson($data);
    } catch (Exception $e) {
        return renderWithJson($result, 'No Paymentgateway found.Please try again.', $e->getMessage(), 1, 422);
    }
});

/**
 * PUT paymentGatewayspaymentGatewayIdPut
 * Summary: Update Payment gateway by its id
 * Notes: Update Payment gateway.
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/payment_gateways/{paymentGatewayId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $paymentGateway = Models\PaymentGateway::find($request->getAttribute('paymentGatewayId'));
    foreach ($args as $key => $arg) {
        if (!is_array($arg)) {
            $paymentGateway->{$key} = $arg;
        }
    }
    try {
        $paymentGateway->save();
        $result['data'] = $paymentGateway->toArray();
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Payment gateway could not be updated. Please, try again.', '', 1);
    }
})->add(new ACL('canUpdatePaymentGateway'));
$app->PUT('/api/v1/payment_gateway_settings/{paymentGatewayId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $array_name = !empty($args['is_live_mode']) ? 'live_mode_value' : 'test_mode_value';  
    try {
        if (!empty($args[$array_name])) {
            foreach($args[$array_name] as $key => $value)
            {
                $payment_gateway_setting = Models\PaymentGatewaySetting::where('payment_gateway_id',$request->getAttribute('paymentGatewayId'))->where('name', $key)->first();
                if(!empty($payment_gateway_setting))
                {
                    $payment_gateway_setting->$array_name = $value;
                    $payment_gateway_setting->update();
                }
            }
        }         
        if (isset($args['is_live_mode'])) {
            $is_test = empty($args['is_live_mode']) ? 1 : 0;
            Models\PaymentGateway::where('id', $request->getAttribute('paymentGatewayId'))->update(array(
                "is_test_mode" => $is_test
            ));
        }
        $payment_gateway_settings = Models\PaymentGateway::with('payment_settings')->find($request->getAttribute('paymentGatewayId'));
        if(!empty($payment_gateway_settings))
        {
            $result['data'] = $payment_gateway_settings->toArray();
            return renderWithJson($result);
        }else{
            return renderWithJson('No record found', '', '', 1, 404);
        }        
    } catch (Exception $e) {
        return renderWithJson($result, 'Payment gateway could not be updated. Please, try again.', $e->getMessage(), '', 1, 422);
    }
});
/**
 * GET paymentGatewayGet
 * Summary: Get  payment gateways
 * Notes: Filter payment gateway.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/payment_gateways/{paymentGatewayId}', function ($request, $response, $args) {
    $result = array();
    $paymentGateway = Models\PaymentGateway::with('payment_settings')->find($request->getAttribute('paymentGatewayId'));
    if (!empty($paymentGateway)) {
        $result['data'] = $paymentGateway->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
});
/**
 * GET TransactionGet
 * Summary: Get all transactions list.
 * Notes: Get all transactions list.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/transactions', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $transactions = Models\Transaction::Filter($queryParams)->paginate()->toArray();
        $data = $transactions['data'];
        unset($transactions['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $transactions
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canListAllTransactions'));
/**
 * GET UsersUserIdTransactionsGet
 * Summary: Get user transactions list.
 * Notes: Get user transactions list.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/users/{userId}/transactions', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $transactions = Models\Transaction::Filter($queryParams);
        if (!empty($request->getAttribute('userId'))) {
            $user_id = $request->getAttribute('userId');
            $transactions = $transactions->where(function ($q) use ($user_id) {
                $q->where('user_id', $user_id)->orWhere('to_user_id', $user_id);
            });
        }
        $transactions = $transactions->paginate()->toArray();
        $data = $transactions['data'];
        unset($transactions['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $transactions
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canListUserTransactions'));
