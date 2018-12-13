<?php
$app->POST('/api/v1/users/resend_otp', function ($request, $response, $args) {
    global $authUser;
    $result = array();
    $user = Models\User::find($authUser->id);
    if ($user) {
        if ($user->is_mobile_number_verified != 1) {
            $mobile = $user->mobile_code . '' . $user->phone_number;
            $digits = 4;
            $user->mobile_number_verification_otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
            $user->save();
            if (isPluginEnabled('SMS/SMS')) {
                $message = array(
                    'user_id' => $user->id,
                    'message_type' => 'SMS_MOBILE_NUMBER_VERIFICATION'
                );
                $sms = Models\Sms::sendSMS($message, $user->id , 1);
                if (!$sms) {
                    return renderWithJson($result, 'Sms could not be Send', '', '', 1, 422);
                }
            }
            return renderWithJson($result, 'OTP Sent Successfully');
        } else {
            return renderWithJson($result, 'OTP is already verified', '', '', 1, 422);
        }
    }
    return renderWithJson($result, 'Invalid Request Send', '', '', 1, 422);
})->add(new ACL('canCreateResendOtp'));


$app->POST('/api/v1/users/{userId}/verify_otp', function ($request, $response, $args) {
    global $authUser;
    $result = array();
    $body = $request->getParsedBody();
    $user = Models\User::find($request->getAttribute('userId'));
    if ($user) {
        if ($user->is_mobile_number_verified == false) {
            if ($user->mobile_number_verification_otp == $body['otp']) {
                /* Update OTP is verified */
                Models\User::where('id', '=', $user->id)->update(['is_mobile_number_verified' => true]);
                return renderWithJson($result, 'OTP verified successfully');
            } else {
                return renderWithJson($result, 'Invalid OTP', '', '', 1, 422);
            }
        } else {
            return renderWithJson($result, 'OTP is already verified', '', '', 1, 422);
        }
    } else {
        return renderWithJson($result, 'Invalid Request Send', '', '', 1, 404);
    }
})->add(new ACL('canVerifyOtp'));
