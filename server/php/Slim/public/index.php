<?php
/**
 * Base API
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    Base
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
 
require_once '../lib/bootstrap.php';
use Carbon\Carbon as Carbon;
/**
 * POST usersRegisterPost
 * Summary: new user
 * Notes: Post new user.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/users/register', function ($request, $response, $args) {
    global $_server_domain_url;
    $args = $request->getParsedBody();
    $result = array();
    if(empty(IS_ALLOW_TO_REGISTER_SERVICE_PROVIDER) && !empty($args) && $args['role_id'] == \Constants\ConstUserTypes::ServiceProvider){
        return renderWithJson($result, 'Invalid user type.', '', '', 1, 422);
    }
    if (!empty($args['password'])) {
        $args['password'] = getCryptHash($args['password']);
    }
    $args['is_active'] = 1;
    if (USER_IS_EMAIL_VERIFICATION_FOR_REGISTER) {
        $args['activate_hash'] = rand(1, 100);
    }
    if (USER_IS_ADMIN_ACTIVATE_AFTER_REGISTER || USER_IS_EMAIL_VERIFICATION_FOR_REGISTER) {
        $args['is_active'] = 0;
    }
    if (!empty($args['referral_username'])) {
        $referral_user_details = Models\User::where('reference_code', $args['referral_username'])->first();
        if (!empty($referral_user_details)) {
            $args['referred_by_user_id'] = $referral_user_details->id;
            if (isPluginEnabled('Referral/Referral')) {
                $args['affiliate_pending_amount'] = AFFILIATE_REFERRAL_AMOUNT_NEW_USER;
            }
        }
        unset($args['referral_username']);
    }
    $bytes = random_bytes(5);
    $ref_code = bin2hex($bytes);
    $check_reference_code_count = Models\User::where('reference_code', $ref_code)->count();
    if ($check_reference_code_count == 0) {
        $args['reference_code'] = $ref_code;
    } else {
        $bytes = random_bytes(5);
        $args['reference_code'] = bin2hex($bytes);
    }
    //Captcha verification
    if (!empty($args['g-recaptcha-response'])) {
        $captcha = $args['g-recaptcha-response'];
        if (captchaCheck($captcha) == false) {
            return renderWithJson($result, 'Captcha Verification failed.', '', '', 1, 422);
        }
    }
    $args['username'] = strstr($args['email'], "@", true);
    if (isset($args['first_name']) && isset($args['last_name'])) {
        $args['username'] = $args['first_name'] . " " . $args['last_name'];
    }
    if (!empty($args['country']['iso2'])) {
        $country_id = findCountryIdFromIso2($args['country']['iso2']);
        $args['country_id'] = $country_id;
    } elseif (isset($args['country']['iso2'])) {
        $args['country_id'] = '';
    }
    if (!empty($args['state']['name']) && !empty($args['country_id'])) {
        $state_id = findOrSaveAndGetStateId($args['state']['name'], $args['country_id']);
        $args['state_id'] = $state_id;
    } elseif (isset($args['state']['name'])) {
        $args['state_id'] = '';
    }
    if (!empty($args['city']['name']) && !empty($args['country_id']) && !empty($args['state_id'])) {
        $city_id = findOrSaveAndGetCityId($args['city']['name'], $args['country_id'], $args['state_id']);
        $args['city_id'] = $city_id;
    } elseif (isset($args['city']['name'])) {
        $args['city_id'] = '';
    }
    $digits = 4;
    //@boopathi @anandam @todo - we need to change this when we getting sms account creditials... for now we give sample verify code ad 1111
    //$args['mobile_number_verification_otp'] = rand(pow(10, $digits - 1), pow(10, $digits) - 1); 
    $args['mobile_number_verification_otp'] = 1111;
    $user = new Models\User($args);
    $userProfile = new Models\UserProfile($args);
    $validationErrorFields = $user->validate($args);
    if (is_object($validationErrorFields)) {
        $validationErrorFields = $validationErrorFields->toArray();
    }
    $validationErrorFields = empty($validationErrorFields) ? [] : $validationErrorFields;
    /*if(!empty($args['phone'])) {
    if(checkAlreadyMobileExists($args['phone'])) {
    $validationErrorFields['phone'] = array ('unique');
    }
    }*/
    if (checkAlreadyEmailExists($args['email'])) {
        $validationErrorFields['email'] = array(
            'unique'
        );
    }
    if(!empty($args['phone_number']) && !empty($args['mobile_code'])){
        try {
            $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            $swissNumberProto = $phoneUtil->parse($args['phone_number'], $args['mobile_code']);
            if(!$phoneUtil->isValidNumber($swissNumberProto)) {
                $validationErrorFields['mobile'] = 'Invalid mobile number and mobile code.';
            } else {
                $mobile = $phoneUtil->format($swissNumberProto, \libphonenumber\PhoneNumberFormat::E164);
                $mobile_number = $mobile_without_code = $swissNumberProto->getNationalNumber();
                $mobile_country_code = str_replace($mobile_without_code, '', $mobile);
                $user->mobile_code = $mobile_country_code;
                $user->phone_number = $mobile_number;
            }  
        } catch(\libphonenumber\NumberParseException $e){
            $validationErrorFields['mobile'] = array ('Couldn’t authenticate the mobile number and code.');
        }				
    } 
    if(!empty($args['secondary_phone_number']) && !empty($args['secondary_mobile_code'])){
        try {
            $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            $swissNumberProto = $phoneUtil->parse($args['secondary_phone_number'], $args['secondary_mobile_code']);
            if(!$phoneUtil->isValidNumber($swissNumberProto)) {
                $validationErrorFields['secondary_mobile'] = 'Invalid mobile number and mobile code.';
            } else {
                $mobile = $phoneUtil->format($swissNumberProto, \libphonenumber\PhoneNumberFormat::E164);
                $mobile_number = $mobile_without_code = $swissNumberProto->getNationalNumber();
                $mobile_country_code = str_replace($mobile_without_code, '', $mobile);
                $user->secondary_mobile_code = $mobile_country_code;
                $user->secondary_phone_number = $mobile_number;
            }  
        } catch(\libphonenumber\NumberParseException $e){
            $validationErrorFields['secondary_mobile'] = array ('Couldn’t authenticate the mobile number and code.');
        }				
    } 
    $profileValidationErrorFields = $userProfile->validate($args);
    if (is_object($profileValidationErrorFields)) {
        $profileValidationErrorFields = $profileValidationErrorFields->toArray();
    }
    $profileValidationErrorFields = empty($profileValidationErrorFields) ? [] : $profileValidationErrorFields;
    try {
        if (empty($validationErrorFields) && empty($profileValidationErrorFields)) {
            if ($user->save()) {
                $userProfile->fill($args);
                $userProfile->user_id = $user->id;
                if ($args['role_id'] == \Constants\ConstUserTypes::ServiceProvider) {
                    $appointment_settings_data['user_id'] = $user->id;
                    $appointment_settings_data['is_active'] = 0;
                    $appointment_settings = Models\AppointmentSetting::saveAppointment($appointment_settings_data);
                    $appointment_settings->save();
                    $userProfile->language_id = (!empty($args['language_id'])) ? $args['language_id'] : null;
                    if(!empty($args['category_id'])){
                        $user->category_id = $args['category_id'];
                        $user->update();
                    }else{
                        $get_category = Models\Category::where('is_active',1)->first();
                        if(!empty($get_category)) {
                            $user->category_id = $get_category->id;
                            $user->update();
                        }
                    }
                }                
                $userProfile->save();
                if (isPluginEnabled('Mailchimp/Mailchimp')) {
                    Models\User::updateMailchimpListID($user->id);
                }
                $user = Models\User::with('user_profile')->find($user->id);
                return Models\User::email_conditions($user, 'register', $request);
            } else {
                return renderWithJson($result, 'Unable to add. Please try again.', '', '', 1, 422);
            }
        } else {
            $validationErrorFields = array_merge($validationErrorFields, $profileValidationErrorFields);
            return renderWithJson($result, 'Unable to add. Please try again.', '', $validationErrorFields, 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Unable to add. Please try again.', $e->getMessage(), '', 1, 422);
    }
});
/**
 * PUT usersUserIdActivationHashPut
 * Summary: User activation
 * Notes: Send activation hash code to user for activation. \n
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/users/activation/{userId}/{hash}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    $user = Models\User::Filter($queryParams)->where(['id' => $request->getAttribute('userId') , 'is_active' => 0])->first();
    try {
        if (!empty($user)) {
            if (md5($user->id) == $args['hash']) {
                $user->is_active = 1;
                $user->save();
                $result['data'] = $user->toArray();
                return renderWithJson($result);
            } else {
                return renderWithJson($result, 'The activation link is Invalid.', '', '', 1, 422);
            }
        } else {
            return renderWithJson($result, 'Invalid user deatails.', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'User activation failed! Please try again.', $e->getMessage(), '', 1, 422);
    }
});
$app->GET('/api/v1/users/{userId}/activate/{hash}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    $user = Models\User::Filter($queryParams)->where(['id' => $request->getAttribute('userId')])->first();
    if (!$user) {
        return renderWithJson($result, 'Invalid User', '', '', 1, 422);
    }
    if ($user) {
        if (md5($user->id . '-' . \Constants\Security::salt) != $request->getAttribute('hash')) {
            return renderWithJson($result, 'Invalid Activation Request', '', '', 1, 422);
        }
        $user->is_email_confirmed = 1;
        $user->activate_hash = rand(1, 100);
        if (!USER_IS_ADMIN_ACTIVATE_AFTER_REGISTER) {
            $user->is_active = 1;
        }
        if ($user->save()) {
            return renderWithJson($result, 'User activated Successfully');
        }
    }
});
$app->POST('/api/v1/users/resend_activation_link', function ($request, $response, $args) {
    global $authUser, $_server_domain_url;
    $result = array();
    $user = Models\User::with('user_profile')->find($authUser->id);
    if ($user) {
        if (!empty($user->user_profile->first_name) || !empty($user->user_profile->last_name)) {
            $username = $user->user_profile->first_name .' '.$user->user_profile->last_name;
        } else {
            $username = $user->email; 
        }     
        if ($user->is_email_confirmed != 1) {
            $activation_link = $_server_domain_url . '/users/' . $user->id . '/activate/' . md5($user->id . '-' . \Constants\Security::salt);
            $emailFindReplace = array(
                '##USERNAME##' => $username,
                '##CONTACT_MAIL##' => SITE_CONTACT_EMAIL,                
                '##ACTIVATION_URL##' => $activation_link,
                '##FROM_EMAIL##' => SITE_FROM_EMAIL
            );
            sendMail('Activation Request', $emailFindReplace, $user->email);
            return renderWithJson($result, 'Activation mail has been sent to your mail inbox.');
        } else {
            return renderWithJson($result, 'Email is already verified', '', '', 1, 422);
        }
    } else {
        return renderWithJson($result, 'Invalid Request Send', '', '', 1, 404);
    }
})->add(new ACL('canResendActvationLink'));
/**
 * POST usersLoginPost
 * Summary: User login
 * Notes: User login information post
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/users/login', function ($request, $response, $args) {
    $body = $request->getParsedBody();
    $result = array();
    $log_user = Models\User::with('user_profile', 'attachment', 'listing_photo')->where(['email' => $body['email']])->where('is_deleted', 0)->first();
    try {
        if (!empty($log_user)) {
            if (USER_IS_EMAIL_VERIFICATION_FOR_REGISTER == 1) {
                if ($log_user->is_email_confirmed == 0) {
                    return renderWithJson($result, 'Email Verification Failed', '', '', 1, 422);
                }
            }
            if (!empty($log_user['is_active'])) {
                $password = crypt($body['password'], $log_user['password']);
                if ($password == $log_user['password']) {
                    $token = array(
                        'token' => getToken($log_user->id)
                    );
                    insertUserToken($log_user->id, $token['token'], $request);
                    Models\User::save_user_login($request);
                    $result = $token + $log_user->toArray();
                    return renderWithJson($result);
                } else {
                    return renderWithJson($result, 'The password is incorrect. Please, try again', '', '', 1, 422);
                }
            } else {
                return renderWithJson($result, 'Your account isn’t activated yet.', '', '', 1, 422);
            }
        } else {
            return renderWithJson($result, 'The account doesn’t exist. Please enter a valid email.', '', '', 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Oops! Login failed. Your username and password combination is incorrect. Please, try again.', $e->getMessage(), '', 1, 422);
    }
});
/**
 * Get userSocialLoginGet
 * Summary: Social Login for twitter
 * Notes: Social Login for twitter
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/users/social_login', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array ();
    try {
        if (!empty($queryParams['type']) && in_array($queryParams['type'], array('twitter'))) {
            include '../lib/vendors/Providers/' . $queryParams['type'] . '.php';
            $provider = Models\Provider::where('name', ucfirst($queryParams['type']))->first();
            $class_name = 'Providers_' . $provider['name'];
            $adapter = new $class_name();
            $response = $adapter->getRequestToken($provider);
            return renderWithJson($response);
        } else {
            return renderWithJson($result, 'No record found', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Please choose one provider.', $e->getMessage(), '', 1, 422);
    }
});
/**
 * POST userSocialLoginPost
 * Summary: User Social Login
 * Notes:  Social Login
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/users/social_login', function ($request, $response, $args) {
    $body = $request->getParsedBody();
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        if (!empty($queryParams['type'])) {
            include  APP_PATH . '/server/php/Slim/lib/vendors/Providers/' . $queryParams['type'] . '.php';
            $provider = Models\Provider::where('name', ucfirst($queryParams['type']))->first();
            $body['secret_key'] = $provider['secret_key'];
            $body['api_key'] = $provider['api_key'];
            $class_name = 'Providers_' . $provider['name'];
            $adapter = new $class_name();
            if (!empty($body['code'])) {
                $access_token = $adapter->getAccessToken($body);
                if ($access_token) {
                    $profile = $adapter->getUserProfile($access_token, $provider);
                    if (!empty($profile->email)) {
                        $response = social_login($adapter, $provider, $profile);
                    } else {
                        $response = array(
                            'is_email_missing' => 1,
                            'type' => $queryParams['type'],
                            'access_token' => $access_token
                        );
                    }
                } else {
                    return renderWithJson($result, 'Could not login. Please try again later.', '', 1, 422);
                }
            } else if (!empty($body['access_token'])) {
                $profile = $adapter->getUserProfile($body['access_token'], $provider);
                if (!empty($body['email'])) {
                    $profile->email = $body['email'];
                }
                if (!empty($body['role_id'])) {
                    $profile->role_id = $body['role_id'];
                }
                $response = social_login($adapter, $provider, $profile);
            }
            return renderWithJson($response);
        } else {
            return renderWithJson($result, 'Please choose one provider.', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Oops! Login failed. Your username and password combination is incorrect.', $e->getMessage(), '', 1, 422);
    } 
});
/**
 * POST usersForgotPasswordPost
 * Summary: User forgot password
 * Notes: User forgot password
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/users/forgot_password', function ($request, $response, $args) {
    $result = array();
    $args = $request->getParsedBody();
    $user = Models\User::where('email', $args['email'])->first();
    if (!empty($user)) {
        $validationErrorFields = $user->validate($args);
        if (empty($validationErrorFields) && !empty($user)) {
            $password = uniqid();
            $user->password = getCryptHash($password);
            try {
                $user->save();
                $emailFindReplace = array(
                    '##USERNAME##' => $user['email'],
                    '##PASSWORD##' => $password,
                );
                sendMail('Forgot Password', $emailFindReplace, $user['email']);
                return renderWithJson($result, 'The new password was sent to your mail', '', '', 0, 200);
            } catch (Exception $e) {
                return renderWithJson($result, 'Sorry! The email address cannot be found', $e->getMessage(), '', 1, 422);
            }
        } else {
            return renderWithJson($result, 'Sorry! Something seems to be wrong!', '', $validationErrorFields, 1, 422);
        }
    } else {
        return renderWithJson($result, 'The user cannot be found', '', '', 1, 404);
    }
});
/**
 * PUT UsersuserIdChangePasswordPut .
 * Summary: update change password
 * Notes: update change password
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/users/{userId}/change_password', function ($request, $response, $args) {
    global $authUser;
    $result = array();
    $args = $request->getParsedBody();
    $queryParams = $request->getQueryParams();
    $user = Models\User::Filter($queryParams)->find($request->getAttribute('userId'));
    if (!empty($user)) {
        $validationErrorFields = $user->validate($args);
        $password = crypt($args['password'], $user['password']);
        if (empty($validationErrorFields)) {
            if ($password == $user['password']) {
                $change_password = $args['new_password'];
                $user->password = getCryptHash($change_password);
                try {
                    $user->save();
                    $emailFindReplace = array(
                        '##PASSWORD##' => $args['new_password'],
                        '##USERNAME##' => $user['username']
                    );
                    sendMail('Change Password', $emailFindReplace, $user['email']);
                    $result['data'] = $user->toArray();
                    return renderWithJson($result);
                } catch (Exception $e) {
                    return renderWithJson($result, 'Sorry! The password couldn’t be changed.', $e->getMessage(), '', 1, 422);
                }
            } else {
                return renderWithJson($result, 'Your old password is incorrect, please try again', '', '', 1, 422);
            }
        } else {
            return renderWithJson($result, 'Sorry! The password couldn’t be changed.', '', $validationErrorFields, 1, 422);
        }
    } else {
        return renderWithJson($result, 'The user ID submitted is invalid', '', 1, 404);
    }
})->add(new ACL('canChangePassword'));
/**
 * POST AdminChangePasswordToUser .
 * Summary: update change password
 * Notes: update change password
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/users/change_password', function ($request, $response, $args) {
    global $authUser;
    $result = array();
    $args = $request->getParsedBody();
    $user = Models\User::find($args['user_id']);
    $validationErrorFields = $user->validate($args);
    $validationErrorFields['unique'] = array();
    if (!empty($args['new_password']) && !empty($args['new_confirm_password']) && $args['new_password'] != $args['new_confirm_password']) {
        array_push($validationErrorFields['unique'], 'Password and confirm password should be same');
    }
    if (empty($validationErrorFields['unique'])) {
        unset($validationErrorFields['unique']);
    }
    if (empty($validationErrorFields)) {
        $change_password = $args['new_password'];
        $user->password = getCryptHash($change_password);
        try {
            $user->save();
            $emailFindReplace = array(
                '##PASSWORD##' => $args['new_password'],
                '##USERNAME##' => $user['username']
            );
            sendMail('Admin Change Password', $emailFindReplace, $user->email);
            $result['data'] = $user->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'User Password could not be updated. Please, try again', '', 1);
        }
    } else {
        return renderWithJson($result, 'User Password could not be updated. Please, try again', $validationErrorFields, 1);
    }
})->add(new ACL('canAdminChangePasswordToUser'));
/**
 * GET usersLogoutGet
 * Summary: User Logout
 * Notes: oauth
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/users/logout', function ($request, $response, $args) {
    $token = "";
    $result = array();
    if (isset($request->getHeaders() ['HTTP_AUTHORIZATION']) && !empty($request->getHeaders() ['HTTP_AUTHORIZATION'])) {
        $token = $request->getHeaders() ['HTTP_AUTHORIZATION'][0];
        if (preg_match('/Bearer\s(\S+)/', $token, $matches)) {
            $token = $matches[1];
        }
    }
    if (!empty($token)) {
        try {
            $accessToken = Models\UserToken::where('token', $token)->first();
            if (!empty($accessToken)) {
                $accessToken->delete();
                return renderWithJson($result, 'You’ve logged out successfully (or) The action was completed successfully.', '', '', 0, 200);
            } else {
                return renderWithJson($result, 'Invalid Token!', '', '', 1, 422);
            }
        } catch (Exception $e) {
            return renderWithJson($result, 'Sorry! There was a problem. Please try again', $e->getMessage(), '', 1, 422);
        }
    } else {
        return renderWithJson($result, 'Invalid Token!', '', '', 1, 422);
    }
});
/**
 * GET UsersGet
 * Summary: Filter  users
 * Notes: Filter users.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/users', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    global $authUser;
    $result = array();
    try {
        $users = Models\User::Filter($queryParams)->where('is_deleted', 0);
        if(isset($queryParams['type']) && $queryParams['type'] == 'favorite'){
            $get_user_favourites = Models\UserFavorite::where('user_id', $authUser->id)->select('provider_user_id')->get()->toArray();
            $users = $users->whereIn('id', $get_user_favourites);
        }        
        if ($authUser && isPluginEnabled('BlockedUser/BlockedUser')) {
            $users = $users->doesntHave('blocker')->doesntHave('blocking');
        }
        $users = $users->paginate();
        if (!empty($authUser) && $authUser->role_id == \Constants\ConstUserTypes::Admin) {
            $user_model = new Models\User;
            $users->makeVisible($user_model->hidden);
        }
        $users = $users->toArray();
        $data = $users['data'];
        unset($users['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $users
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
});
/**
 * GET UsersGet
 * Summary: Filter  users
 * Notes: Filter users.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/me/users', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    global $authUser;
    $result = array();
    try {
        $users = Models\User::Filter($queryParams)->find($authUser->id);
        if (!empty($users)) {
            $result['data'] = $users;
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Sorry! No record found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canViewUser'));
/**
 * POST UserPost
 * Summary: Create New user by admin
 * Notes: Create New user by admin
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/users', function ($request, $response, $args) {
    global $_server_domain_url;
    $args = $request->getParsedBody();
    $result = array();
    if (!empty($args['password'])) {
        $args['password'] = getCryptHash($args['password']);
    }
    $args['is_active'] = 1;
    $args['is_email_confirmed'] = 1;
    if (!empty($args['referral_username'])) {
        $referral_user_details = Models\User::where('reference_code', $args['referral_username'])->first();
        if (!empty($referral_user_details)) {
            $args['referred_by_user_id'] = $referral_user_details->id;
        }
        unset($args['referral_username']);
    }
    $bytes = random_bytes(5);
    $ref_code = bin2hex($bytes);
    $check_reference_code_count = Models\User::where('reference_code', $ref_code)->count();
    if ($check_reference_code_count == 0) {
        $args['reference_code'] = $ref_code;
    } else {
        $bytes = random_bytes(5);
        $args['reference_code'] = bin2hex($bytes);
    }
    $args['username'] = strstr($args['email'], "@", true);
    if (isset($args['first_name']) && isset($args['last_name'])) {
        $args['username'] = $args['first_name'] . " " . $args['last_name'];
    }
    $user = new Models\User($args);
    $userProfile = new Models\UserProfile($args);
    $validationErrorFields = $user->validate($args);
    if (is_object($validationErrorFields)) {
        $validationErrorFields = $validationErrorFields->toArray();
    }
    if (!empty($args['phone'])) {
        if (checkAlreadyMobileExists($args['phone'])) {
            $validationErrorFields['phone'] = array(
                'unique'
            );
        }
    }
    if (checkAlreadyEmailExists($args['email'])) {
        $validationErrorFields['email'] = array(
            'unique'
        );
    }
    if (!empty($args['country']['iso2'])) {
        $country_id = findCountryIdFromIso2($args['country']['iso2']);
        $args['country_id'] = $country_id;
    } elseif (isset($args['country']['iso2'])) {
        $args['country_id'] = '';
    }
    if (!empty($args['state']['name']) && !empty($args['country_id'])) {
        $state_id = findOrSaveAndGetStateId($args['state']['name'], $args['country_id']);
        $args['state_id'] = $state_id;
    } elseif (isset($args['state']['name'])) {
        $args['state_id'] = '';
    }
    if (!empty($args['city']['name']) && !empty($args['country_id']) && !empty($args['state_id'])) {
        $city_id = findOrSaveAndGetCityId($args['city']['name'], $args['country_id'], $args['state_id']);
        $args['city_id'] = $city_id;
    } elseif (isset($args['city']['name'])) {
        $args['city_id'] = '';
    }
    $profileValidationErrorFields = $userProfile->validate($args);
    if (is_object($profileValidationErrorFields)) {
        $profileValidationErrorFields = $profileValidationErrorFields->toArray();
    }
    if(!empty($args['phone_number']) && !empty($args['mobile_code'])){
        try {
            $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            $swissNumberProto = $phoneUtil->parse($args['phone_number'], $args['mobile_code']);
            if(!$phoneUtil->isValidNumber($swissNumberProto)) {
                $validationErrorFields['mobile'] = 'Invalid mobile number and mobile code.';
            } else {
                $mobile = $phoneUtil->format($swissNumberProto, \libphonenumber\PhoneNumberFormat::E164);
                $mobile_number = $mobile_without_code = $swissNumberProto->getNationalNumber();
                $mobile_country_code = str_replace($mobile_without_code, '', $mobile);
                $user->mobile_code = $mobile_country_code;
                $user->phone_number = $mobile_number;
            }  
        } catch(\libphonenumber\NumberParseException $e){
            $validationErrorFields['mobile'] = array ('Couldn’t authenticate the mobile number and code.');
        }				
    } 
    if(!empty($args['secondary_phone_number']) && !empty($args['secondary_mobile_code'])){
        try {
            $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            $swissNumberProto = $phoneUtil->parse($args['secondary_phone_number'], $args['secondary_mobile_code']);
            if(!$phoneUtil->isValidNumber($swissNumberProto)) {
                $validationErrorFields['secondary_mobile'] = 'Invalid mobile number and mobile code.';
            } else {
                $mobile = $phoneUtil->format($swissNumberProto, \libphonenumber\PhoneNumberFormat::E164);
                $mobile_number = $mobile_without_code = $swissNumberProto->getNationalNumber();
                $mobile_country_code = str_replace($mobile_without_code, '', $mobile);
                $user->secondary_mobile_code = $mobile_country_code;
                $user->secondary_phone_number = $mobile_number;
            }  
        } catch(\libphonenumber\NumberParseException $e){
            $validationErrorFields['secondary_mobile'] = array ('Couldn’t authenticate the mobile number and code.');
        }				
    }     
    try {
        if (empty($validationErrorFields) && empty($profileValidationErrorFields)) {
            if ($user->save()) {
                $userProfile->user_id = $user->id;
                $userProfile->save();
                $user = Models\User::with('user_profile')->find($user->id);
                if (!empty($user->user_profile->first_name) || !empty($user->user_profile->last_name)) {
                    $username = $user->user_profile->first_name .' '.$user->user_profile->last_name;
                } else {
                    $username = $user->email; 
                }                
                $emailFindReplace = array(
                    '##USERNAME##' => $username
                );
                sendMail('Welcome Mail', $emailFindReplace, $user['email']);
                $userProfile->fill($args);
                $userProfile->user_id = $user->id;
                if ($args['role_id'] == \Constants\ConstUserTypes::ServiceProvider) {
                    $appointment_settings_data['user_id'] = $user->id;
                    $appointment_settings_data['is_active'] = 0;
                    $appointment_settings = Models\AppointmentSetting::saveAppointment($appointment_settings_data);
                    $appointment_settings->save();
                    $userProfile->language_id = (!empty($args['language_id'])) ? $args['language_id'] : null;
                }
                $userProfile->save();
                if (isPluginEnabled('Mailchimp/Mailchimp')) {
                    Models\User::updateMailchimpListID($user->id);
                }
                return Models\User::email_conditions($user, 'register', $request);
            } else {
                return renderWithJson($result, 'Unable to add. Please try again.', '', '', 1, 422);
            }
        } else {
            if(!empty($profileValidationErrorFields)){
                $validationErrorFields = array_merge($validationErrorFields, $profileValidationErrorFields);
            }
            return renderWithJson($result, 'Unable to add. Please try again.', '', $validationErrorFields, 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Unable to add. Please try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canCreateUser'));
/**
 * GET UseruserIdGet
 * Summary: Get particular user details
 * Notes: Get particular user details
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/users/{userId}', function ($request, $response, $args) {
    global $authUser;
    $queryParams = $request->getQueryParams();
    try {
        $result = array();
        $user = Models\User::Filter($queryParams)->where('id', $request->getAttribute('userId'))->where('is_deleted', 0)->first();
        if (!empty($user)) {
            if (isset($queryParams['type']) && $queryParams['type'] == 'view') {
                $userView = new Models\UserView;
                if (!empty($authUser['id'])) {
                    $userView->user_id = $authUser['id'];
                }
                $userView->viewing_user_id = $user->id;
                $userView->save();
            }
            $user->mobile_code_country_id = $user->secondary_mobile_code_country_id = null;
            if (!empty($user->mobile_code)) {
                $country = Models\Country::select('id')->where('phone', $user->mobile_code)->first();
                $user->mobile_code_country_id = $country['id'];
            }
            if (!empty($user->secondary_mobile_code)) {
                $country = Models\Country::select('id')->where('phone', $user->secondary_mobile_code)->first();
                $user->secondary_mobile_code_country_id = $country['id'];
            }
            $result['data'] = $user->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'The user cannot be found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'The user cannot be found.', $e->getMessage(), '', 1, 422);
    }
});
/**
 * GET AuthUserID
 * Summary: Get particular user details
 * Notes: Get particular user details
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/me', function ($request, $response, $args) {
    global $authUser;
    $result = array();
    try {
        if (!empty($authUser)) {
            $user = Models\User::where('id', $authUser->id)->first();
            $user_model = new Models\User;
            $user->makeVisible($user_model->hidden);
            if (!empty($user)) {
                $result['data'] = $user;
                return renderWithJson($result);
            } else {
                return renderWithJson($result, 'The user cannot be found', '', '', 1, 404);
            }
        } else {
            return renderWithJson($result, 'Authentication failed! Please try again!', '', '', 1, 401);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'The user cannot be found.', $e->getMessage(), '', 1, 422);
    }
});
/**
 * PUT UsersuserIdPut
 * Summary: Update user
 * Notes: Update user
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/users/{userId}', function ($request, $response, $args) {
    global $authUser, $_server_domain_url;
    $args = $request->getParsedBody();
    $result = array();
    $user = Models\User::where(['id' => $request->getAttribute('userId') ])->first();
    if (!empty($user)) {
        $prev_email = $user->email;
        $prev_phone_number = $user->phone_number;
        $prev_first_name = $user->first_name;
        $prev_last_name = $user->last_name;
        $userProfile = Models\UserProfile::where('user_id', $user->id)->first();
        if (!empty($args['country']['iso2'])) {
            $country_id = findCountryIdFromIso2($args['country']['iso2']);
            $args['country_id'] = $country_id;
        } elseif (isset($args['country']['iso2'])) {
            $args['country_id'] = '';
        }
        if (!empty($args['state']['name']) && !empty($args['country_id'])) {
            $state_id = findOrSaveAndGetStateId($args['state']['name'], $args['country_id']);
            $args['state_id'] = $state_id;
        } elseif (isset($args['state']['name'])) {
            $args['state_id'] = '';
        }
        if (!empty($args['city']['name']) && !empty($args['country_id']) && !empty($args['state_id'])) {
            $city_id = findOrSaveAndGetCityId($args['city']['name'], $args['country_id'], $args['state_id']);
            $args['city_id'] = $city_id;
        } elseif (isset($args['city']['name'])) {
            $args['city_id'] = '';
        }
        if (!empty($args['listing_country']['iso2'])) {
            $listing_country_id = findCountryIdFromIso2($args['listing_country']['iso2']);
            $args['listing_country_id'] = $listing_country_id;
        } elseif (isset($args['listing_country']['iso2'])) {
            $args['listing_country_id'] = '';
        }
        if (!empty($args['listing_state']['name']) && !empty($args['listing_country_id'])) {
            $listing_state_id = findOrSaveAndGetStateId($args['listing_state']['name'], $args['listing_country_id']);
            $args['listing_state_id'] = $listing_state_id;
        } elseif (isset($args['listing_state']['name'])) {
            $args['listing_state_id'] = '';
        }
        if (!empty($args['listing_city']['name']) && !empty($args['listing_country_id']) && !empty($args['listing_state_id'])) {
            $listing_city_id = findOrSaveAndGetCityId($args['listing_city']['name'], $args['listing_country_id'], $args['listing_state_id']);
            $args['listing_city_id'] = $listing_city_id;
        } elseif (isset($args['listing_city']['name'])) {
            $args['listing_city_id'] = '';
        }
        if (isset($args['mobile_code_country_id'])) {
            $country = Models\Country::select('iso2')->find($args['mobile_code_country_id']);
            $args['mobile_code'] = $country['iso2'];
        }
        if (isset($args['secondary_mobile_code_country_id']) && !empty($args['secondary_mobile_code_country_id'])) {
            $country = Models\Country::select('iso2')->find($args['secondary_mobile_code_country_id']);
            $args['secondary_mobile_code'] = $country['iso2'];
        } else {
            unset($args['secondary_mobile_code']);
        }
        if (empty($args['secondary_phone_number'])) {
            unset($args['secondary_phone_number']);
        }
        $validationErrorFields = $user->validate($args);
        if (!empty($args['email'])) {
            if (checkAlreadyEmailExists($args['email'], $user->id)) {
                $validationErrorFields['unique'][] = 'email';
            }
        }
		if(!empty($args['phone_number']) && !empty($args['mobile_code'])){
			try {
				$phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
				$swissNumberProto = $phoneUtil->parse($args['phone_number'], $args['mobile_code']);
				if(!$phoneUtil->isValidNumber($swissNumberProto)) {
					$validationErrorFields['mobile'] = 'Invalid mobile number and mobile code.';
				} else {
					$mobile = $phoneUtil->format($swissNumberProto, \libphonenumber\PhoneNumberFormat::E164);
					$mobile_number = $mobile_without_code = $swissNumberProto->getNationalNumber();
					$mobile_country_code = str_replace($mobile_without_code, '', $mobile);
					$args['mobile_code'] = $mobile_country_code;
					$args['phone_number'] = $mobile_number;
				}  
			} catch(\libphonenumber\NumberParseException $e){
				$validationErrorFields['mobile'] = array ('Couldn’t authenticate the mobile number and code.');
			}				
		} 
		if(!empty($args['secondary_phone_number']) && !empty($args['secondary_mobile_code'])){
			try {
				$phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
				$swissNumberProto = $phoneUtil->parse($args['secondary_phone_number'], $args['secondary_mobile_code']);
				if(!$phoneUtil->isValidNumber($swissNumberProto)) {
					$validationErrorFields['secondary_mobile'] = 'Invalid mobile number and mobile code.';
				} else {
					$mobile = $phoneUtil->format($swissNumberProto, \libphonenumber\PhoneNumberFormat::E164);
					$mobile_number = $mobile_without_code = $swissNumberProto->getNationalNumber();
					$mobile_country_code = str_replace($mobile_without_code, '', $mobile);
					$args['secondary_mobile_code'] = $mobile_country_code;
					$args['secondary_phone_number'] = $mobile_number;
				}  
			} catch(\libphonenumber\NumberParseException $e){
				$validationErrorFields['secondary_mobile'] = array ('Couldn’t authenticate the mobile number and code.');
			}				
		}      
        /*if (!empty($args['phone'])) {
        if (checkAlreadyMobileExists($args['phone'], $user->id)) {
        $validationErrorFields['unique'][] = 'phone';
        }
        }*/        
        try {
            if (empty($validationErrorFields)) {
                $old_role_id = $user->role_id;
                $user->fill($args);
                if (isset($args['password'])) {
                    $user->password = getCryptHash($args['password']);
                }
                $user->save();
                if($prev_email != $user['email']){
                    $user->is_email_confirmed = 0; 
                    $user->update();
                    $activation_link = $_server_domain_url . '/#/users/' . $user->id . '/activate/' . md5($user->id . '-' . \Constants\Security::salt);
                    $emailFindReplace = array(
                        '##USERNAME##' => $username,
                        '##CONTACT_MAIL##' => SITE_CONTACT_EMAIL,                        
                        '##ACTIVATION_URL##' => $activation_link,
                        '##FROM_EMAIL##' => SITE_FROM_EMAIL
                    );
                    sendMail('Activation Request', $emailFindReplace, $user->email);                     
                }
                if($prev_phone_number != $user['phone_number']){
                    $user->is_mobile_number_verified = 0; 
                    $user->update();  
                    if (isPluginEnabled('SMS/SMS')) {
                        $message = array(
                            'user_id' => $user->id,
                            'message_type' => 'SMS_MOBILE_NUMBER_VERIFICATION'
                        );
                        Models\Sms::sendSMS($message, $user->id);
                    }                    
                }
                $old_pro_account_status = '';
                if (!empty($userProfile)) {
                    $old_pro_account_status = $userProfile->pro_account_status_id;
                    if (isset($args['user_profile'])) {
                        $userProfile->fill($args['user_profile']);
                    } else {
                        $userProfile->fill($args);
                    }
                    $userProfile->save();
                } else {
                    $userProfile = new Models\UserProfile($args);
                    if (isset($args['user_profile'])) {
                        $userProfile->fill($args['user_profile']);
                    } else {
                        $userProfile->fill($args);
                    }
                    $userProfile->user_id = $user->id;
                    $userProfile->save();
                }
                $new_pro_account_status = $userProfile->pro_account_status_id;
                if (!empty($new_pro_account_status) && !empty($old_pro_account_status) && $authUser['role_id'] == \Constants\ConstUserTypes::Admin) {
                    if ($old_pro_account_status != $new_pro_account_status && $new_pro_account_status == \Constants\ConstProUser::Approved) {
                        Models\User::updateDisplayOrder($user->id);                 
                    }
                }
                if (isPluginEnabled('Mailchimp/Mailchimp') && (!empty($args['role_id']) && $args['role_id'] != $old_role_id) || !empty($args['is_email_subscribed'])) {               
                    Models\User::updateMailchimpListID($user->id);
                }
                if (isPluginEnabled('Mailchimp/Mailchimp') && (!empty($args['is_deleted']) || !empty($args['account_close_reason_id']) || !empty($args['account_close_reason_id']) || empty($args['is_email_subscribed']))) {
                    Models\User::unsubscribeMailchimpListID($user->id);
                }
                $user = Models\User::with('user_profile')->where(['id' => $request->getAttribute('userId') ])->first();
                $result['data'] = $user->toArray();
                return renderWithJson($result);
            } else {
                return renderWithJson($result, 'user could not be updated. Please, try again.', '', $validationErrorFields, 1, 422);
            }
        } catch (Exception $e) {
            return renderWithJson($result, 'user could not be updated. Please, try again.', $e->getMessage(), '', 1, 422);
        }
    } else {
        return renderWithJson($result, 'The user cannot be found.', '', $validationErrorFields, 1, 404);
    }
})->add(new ACL('canUpdateUser'));
$app->GET('/api/v1/listing_statuses', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        $listing_statuses = Models\ListingStatus::Filter($queryParams)->paginate()->toArray();
        $data = $listing_statuses['data'];
        unset($listing_statuses['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $listing_statuses
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
});
/**
 * DELETE UseruserId Delete
 * Summary: DELETE user by admin
 * Notes: DELETE user by admin
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/users/{userId}', function ($request, $response, $args) {
    $result = array();
    $user = Models\User::find($request->getAttribute('userId'));
    $user_id = $request->getAttribute('userId');
    $data = $user;
    if (!empty($user)) {
        try {
            $user->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', '', 1, 422);
        }
    } else {
        return renderWithJson($result, 'Invalid User details.', '', '', 1, 404);
    }
});
$app->PUT('/api/v1/user_profiles', function ($request, $response, $args) {
    global $authUser, $_server_domain_url;
    $result = array();
    $args = $request->getParsedBody();
    $user = Models\User::find($authUser->id);
    unset($args['user_view_count']);
    unset($args['user_favorite_count']);
    unset($args['photo_count']);
    unset($args['services_user_count']);
    unset($args['repeat_client_count']);
    unset($args['completed_appointment_count']);
    unset($args['listing_appointment_count']);
    unset($args['site_revenue_as_service_provider']);
    unset($args['earned_amount_as_service_provider']);
    unset($args['total_spent_amount_as_customer']);    
    $user_profile_data = new Models\UserProfile($args);
    if (!empty($args['country']['iso2'])) {
        $country_id = findCountryIdFromIso2($args['country']['iso2']);
        $args['country_id'] = $country_id;
    } elseif (isset($args['country']['iso2'])) {
        $args['country_id'] = '';
    }
    if (!empty($args['state']['name']) && !empty($args['country_id'])) {
        $state_id = findOrSaveAndGetStateId($args['state']['name'], $args['country_id']);
        $args['state_id'] = $state_id;
    } elseif (isset($args['state']['name'])) {
        $args['state_id'] = '';
    }
    if (!empty($args['city']['name']) && !empty($args['country_id']) && !empty($args['state_id'])) {
        $city_id = findOrSaveAndGetCityId($args['city']['name'], $args['country_id'], $args['state_id']);
        $args['city_id'] = $city_id;
    } elseif (isset($args['city']['name'])) {
        $args['city_id'] = '';
    }
    if (!empty($args['listing_country']['iso2'])) {
        $listing_country_id = findCountryIdFromIso2($args['listing_country']['iso2']);
        $args['listing_country_id'] = $listing_country_id;
    } elseif (isset($args['listing_country']['iso2'])) {
        $args['listing_country_id'] = '';
    }
    if (!empty($args['listing_state']['name']) && !empty($args['listing_country_id'])) {
        $listing_state_id = findOrSaveAndGetStateId($args['listing_state']['name'], $args['listing_country_id']);
        $args['listing_state_id'] = $listing_state_id;
    } elseif (isset($args['listing_state']['name'])) {
        $args['listing_state_id'] = '';
    }
    if (!empty($args['listing_city']['name']) && !empty($args['listing_country_id']) && !empty($args['listing_state_id'])) {
        $listing_city_id = findOrSaveAndGetCityId($args['listing_city']['name'], $args['listing_country_id'], $args['listing_state_id']);
        $args['listing_city_id'] = $listing_city_id;
    } elseif (isset($args['listing_city']['name'])) {
        $args['listing_city_id'] = '';
    }
    if (!empty($user)) {
        $userValidationErrorFields = $user->validate($args);
        if (is_object($userValidationErrorFields)) {
            $userValidationErrorFields = $userValidationErrorFields->toArray();
        }
        $userValidationErrorFields = empty($userValidationErrorFields) ? [] : $userValidationErrorFields;
        $validationErrorFields = $user_profile_data->validate($args);
        if (is_object($validationErrorFields)) {
            $validationErrorFields = $validationErrorFields->toArray();
        }
        $validationErrorFields = empty($validationErrorFields) ? [] : $validationErrorFields;
        if (!empty($args['email'])) {
            if (checkAlreadyEmailExists($args['email'], $user->id)) {
                $userValidationErrorFields['unique'] = array(
                    'email'
                );
            }
        }
        if (!empty(ALLOWED_SERVICE_LOCATIONS)) {
            $allowed_location_status = 0;
            $allowed_locations = json_decode(ALLOWED_SERVICE_LOCATIONS);
            if (!empty($args['country']['iso2']) && !empty($allowed_locations->allowed_countries)) {
                if (!in_array($args['country']['iso2'], array_column(json_decode(json_encode($allowed_locations->allowed_countries), true), 'iso2'))) {
                    $allowed_location_status = 1;
                }
            }
            if (!empty($args['city']['name']) && !empty($allowed_locations->allowed_cities)) {
                if (!in_array($args['city']['name'], array_column(json_decode(json_encode($allowed_locations->allowed_cities), true), 'name'))) {
                    $allowed_location_status = 1;
                }
            }
            if (!empty($allowed_location_status)) {
                $userValidationErrorFields['address'] = array(
                    'Address is not allowed'
                );
                //return renderWithJson($result, 'Address is not allowed', '', 2);
            }
        }
        /*if (!empty($args['is_register_as_service_provider']) && !empty($args['is_register_as_customer'])) {
            if (!IS_ENABLED_SWITCH_ACCOUNT_TYPE) {
                $userValidationErrorFields['role'] = array(
                    'User should not allow to switch account type'
                );
            }
            $user->role_id = \Constants\ConstUserTypes::User;
        } elseif (!empty($args['is_register_as_service_provider'])) {
            $user->role_id = \Constants\ConstUserTypes::ServiceProvider;
        } elseif (!empty($args['is_register_as_customer'])) {
            $user->role_id = \Constants\ConstUserTypes::Customer;
        }*/
        if (isPluginEnabled('Interview/Interview') && !empty($args['is_available_for_interview'])) {
            if ((empty($args['is_available_via_skype_interview']) && empty($args['is_available_via_phone_interview']) && empty($args['is_available_via_in_person_interview']))) {
                $userValidationErrorFields['is_available_for_interview'] = array(
                    'Select anyone of the below oprion is_available_via_skype_interview/is_available_via_phone_interview/is_available_via_in_person_interview'
                );
            }
            if (!empty($args['is_available_via_skype_interview']) && empty($args['im_skype'])) {
                $userValidationErrorFields['im_skype'] = array(
                    'Im skype field required'
                );
            }
        }
        $old_role_id = $user->role_id;
        $role_update = 0;
        if (!empty($args['role_id']) && !empty(IS_ENABLED_SWITCH_ACCOUNT_TYPE) && $args['role_id'] != \Constants\ConstUserTypes::Admin) {
            $role_update = 1;
        } else {
            unset($args['role_id']);

        }
        if (empty($userValidationErrorFields) && empty($validationErrorFields)) {
            $old_phone = $user->phone_number;
            $old_email = $user->email;
            $user->fill($args);
            $user->is_profile_updated = 1;
            $user->update();
            $new_phone = $user->phone_number;
            $new_email = $user->email;
            if ($new_phone != $old_phone) {
                $digits = 4;
                $user->mobile_number_verification_otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
                $user->is_mobile_number_verified = false;
                $user->update();
                if (isPluginEnabled('SMS/SMS')) {
                    $message = array(
                        'user_id' => $user->id,
                        'message_type' => 'SMS_MOBILE_NUMBER_VERIFICATION'
                    );
                    Models\Sms::sendSMS($message, $user->id);
                }
            }
            if($new_email != $old_email){
                $user->is_email_confirmed = false;
                $user->update();
                $activation_link = $_server_domain_url . '/users/' . $user->id . '/activate/' . md5($user->id . '-' . \Constants\Security::salt);
                $emailFindReplace = array(
                    '##USERNAME##' => $username,
                    '##CONTACT_MAIL##' => SITE_CONTACT_EMAIL,                    
                    '##ACTIVATION_URL##' => $activation_link,
                    '##FROM_EMAIL##' => SITE_FROM_EMAIL
                );
                sendMail('Activation Request', $emailFindReplace, $user->email);                
            }
            $user_profile = Models\UserProfile::where('user_id', '=', $user->id)->first();
            try {
                if (!empty($user_profile)) {
                    $old_listing_status_id = $user_profile->listing_status_id;
                    if (isset($args['is_listing_updated'])) {
                        unset($args['is_listing_updated']);
                    }
                    if (!empty($args['listing_title'])) {
                        $args['is_listing_updated'] = true;
                    }
                    $user_profile->fill($args);
                    $user_profile->update();
                } else {
                    if (!isset($args['listing_status_id'])) {
                        $old_listing_status_id = \Constants\ConstListingStatus::Draft;
                    } else {
                        $old_listing_status_id = $user_profile_data->listing_status_id;
                    }
                    $user_profile_data->user_id = $user->id;
                    $user_profile_data->save();
                    $user_profile = $user_profile_data;
                }
                if (isset($args['listing_status_id'])) {
                    if ($old_listing_status_id == \Constants\ConstListingStatus::Draft && in_array($args['listing_status_id'], [\Constants\ConstListingStatus::WaitingForAdminApproval, \Constants\ConstListingStatus::Approved])) {
                        if (ENABLE_AUTO_APPROVAL_FOR_LISTING) {
                            $user_profile->listing_status_id = \Constants\ConstListingStatus::Approved;
                        } else {
                            $user_profile->listing_status_id = \Constants\ConstListingStatus::WaitingForAdminApproval;
                        }
                    } else {
                        unset($args['listing_status_id']);
                    }
                }
                if (isset($args['service_user'])) {
                    Models\ServiceUser::where('user_id', $authUser->id)->delete();
                    foreach ($args['service_user'] as $service_user) {
                        $serviceUser = new Models\ServiceUser;
                        $serviceUser->service_id = $service_user['service_id'];
                        $serviceUser->rate = $service_user['rate'];
                        $serviceUser->cancellation_policy_id = $service_user['cancellation_policy_id'];
                        $serviceUser->user_id = $authUser->id;
                        $serviceUser->save();
                    }
                    $user_profile->services_user_count = count($args['service_user']);
                }
                if (!empty($args['form_field_submissions']) && $user->id) {
                    foreach ($args['form_field_submissions'] as $formFieldSubmissions) {
                        foreach ($formFieldSubmissions as $form_field_id => $value) {
                            $formField = Models\FormField::where('id', $form_field_id)->select('id', 'name')->first();
                            if (!empty($formField)) {
                                $FormFieldSubmission = Models\FormFieldSubmission::where('form_field_id', $formField->id)->where('foreign_id', $user->id)->where('class', 'User')->first();
                                if (empty($FormFieldSubmission)) {
                                    $formFieldSubmission = new Models\FormFieldSubmission;
                                    $formFieldSubmission->response = $value;
                                    $formFieldSubmission->form_field_id = $formField->id;
                                    $formFieldSubmission->foreign_id = $user->id;
                                    $formFieldSubmission->class = 'User';
                                    $formFieldSubmission->save();
                                } else {
                                    $FormFieldSubmission->response = $value;
                                    $FormFieldSubmission->save();
                                }
                            }
                        }
                    }
                }
                if (!empty($role_update) && in_array($user->role_id, [\Constants\ConstUserTypes::ServiceProvider, \Constants\ConstUserTypes::User])) {
                    $appointmentSetting = Models\AppointmentSetting::where('user_id', $user->id)->first();
                    if (empty($appointmentSetting)) {
                        $appointment_settings_data['user_id'] = $user->id;
                        $appointment_settings_data['is_active'] = 0;
                        $appointment_settings = Models\AppointmentSetting::saveAppointment($appointment_settings_data);
                        $appointment_settings->save();                        
                        if(!empty($args['category_id'])) {
                            $user->category_id = $args['category_id'];
                            $user->update();
                        }
                    }
                }
                if (!empty($args['image'])) {
                    saveImage('UserAvatar', $args['image'], $user->id);
                }
                if (!empty($args['image_data'])) {
                    saveImageData('UserAvatar', $args['image_data'], $user->id);
                }
                if (!empty($args['listing_photos'])) {
                    foreach ($args['listing_photos'] as $listing_photo) {
                        if (!empty($listing_photo['image'])) {
                            saveImage('ListingPhoto', $listing_photo['image'], $user->id, true);
                        }
                        if (!empty($listing_photo['image_data'])) {
                            saveImageData('ListingPhoto', $listing_photo['image_data'], $user->id, true);
                        }
                    }
                }
                $user_profile->is_service_profile_updated = 1;
                if (!empty($args['genter_id'])) {
                    $user_profile->is_service_profile_updated = 0;
                }
                $user_profile->update();
                if (isPluginEnabled('Mailchimp/Mailchimp') && (!empty($args['role_id']) && $args['role_id'] != $old_role_id) || !empty($args['is_email_subscribed'])) {
                    Models\User::updateMailchimpListID($user->id);
                }
                if (isPluginEnabled('Mailchimp/Mailchimp') && (!empty($args['is_deleted']) || !empty($args['account_close_reason_id']) || !empty($args['account_close_reason_id']) || empty($args['is_email_subscribed']))) {
                    Models\User::unsubscribeMailchimpListID($user->id);
                }
                $user_profile = Models\UserProfile::with('user', 'listing_city', 'listing_state', 'listing_country', 'user.attachment', 'user.listing_photo')->where('user_id', $user->id)->first()->toArray();
                $result['data'] = $user_profile;
                return renderWithJson($result, 'UserProfile has been updated.');
            } catch (Exception $e) {
                return renderWithJson($result, 'user could not be updated. Please, try again.', '', $e->getMessage(), 1, 422);
            }
        } else {
            $validationErrorFields = array_merge($validationErrorFields, $userValidationErrorFields);
            return renderWithJson($result, 'user could not be updated. Please, try again.', '', $validationErrorFields, 1, 422);
        }
    } else {
        return renderWithJson($result, 'user could not found.', '', '', 1, 404);
    }
})->add(new ACL('canUpdateUserProfile'));
$app->GET('/api/v1/user_profiles', function ($request, $response, $args) {
    global $authUser;
    $queryParams = $request->getQueryParams();
    $user_profile = Models\UserProfile::Filter($queryParams)->where('user_id', '=', $authUser->id)->first();
    if (!$user_profile) {
        $user_profile = new Models\UserProfile(['first_name' => $authUser->username, 'user_id' => $authUser->id]);
        $user_profile->save();
        $user_profile = Models\UserProfile::Filter($queryParams)->where('user_id', '=', $authUser->id)->first();
    }
    return renderWithJson($user_profile->toArray());
})->add(new ACL('canViewUserProfile'));
/**
 * GET ProvidersGet
 * Summary: all providers lists
 * Notes: all providers lists
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/providers', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $providers = Models\Provider::Filter($queryParams)->paginate()->toArray();
        $data = $providers['data'];
        unset($providers['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $providers
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
});
/**
 * GET  ProvidersProviderIdGet
 * Summary: Get  particular provider details
 * Notes: GEt particular provider details.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/providers/{providerId}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        $provider = Models\Provider::Filter($queryParams)->find($request->getAttribute('providerId'));
        if (!empty($provider)) {
            $result['data'] = $provider->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Provider not found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Provider not found', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canViewProvider'));
/**
 * PUT ProvidersProviderIdPut
 * Summary: Update provider details
 * Notes: Update provider details.
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/providers/{providerId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $provider = Models\Provider::find($request->getAttribute('providerId'));
    if (!empty($provider)) {
        $validationErrorFields = $provider->validate($args);
        if (empty($validationErrorFields)) {
            $provider->fill($args);
            try {
                $provider->save();
                $result['data'] = $provider->toArray();
                return renderWithJson($result);
            } catch (Exception $e) {
                return renderWithJson($result, 'Provider could not be updated. Please, try again', $e->getMessage(), '', 1, 422);
            }
        } else {
            return renderWithJson($result, 'Provider could not be updated. Please, try again', '', $validationErrorFields, 1, 422);
        }
    } else {
        return renderWithJson($result, 'Provider not found', '', '', 1, 404);
    }
})->add(new ACL('canUpdateProvider'));
/**
 * GET RoleGet
 * Summary: Get roles lists
 * Notes: Get roles lists
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/roles', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $roles = Models\Role::Filter($queryParams)->paginate()->toArray();
        $data = $roles['data'];
        unset($roles['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $roles
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
});
/**
 * GET RolesIdGet
 * Summary: Get paticular email templates
 * Notes: Get paticular email templates
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/roles/{roleId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $role = Models\Role::Filter($queryParams)->find($request->getAttribute('roleId'));
        if (!empty($role)) {
            $result['data'] = $role->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Role not found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Role not found', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canViewRole'));
/**
 * GET PagesGet
 * Summary: Filter  pages
 * Notes: Filter pages.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/pages', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $pages = Models\Page::Filter($queryParams)->paginate()->toArray();
        $data = $pages['data'];
        unset($pages['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $pages
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
});
/**
 * POST pagePost
 * Summary: Create New page
 * Notes: Create page.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/pages', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    try {
        foreach ($args['pages'] as $page) {
            $page = new Models\Page($page);
            $page->slug = $args['slug'];
            
            $page->save();
        }
         $result = array(
            'status' => 'success',
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Page user could not be added. Please, try again.', $e->getMessage(), '', 1, 422);
    }
    
})->add(new ACL('canCreatePage'));
/**
 * GET PagePageIdGet.
 * Summary: Get page.
 * Notes: Get page.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/pages/{pageId}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        if (!empty($queryParams['type']) && $queryParams['type'] == 'slug') {
            $page = Models\Page::Filter($queryParams)->where('slug', $request->getAttribute('pageId'))->first();
        } else {
            $page = Models\Page::Filter($queryParams)->where('id', $request->getAttribute('pageId'))->first();
        }
        if (!empty($page)) {
            $result['data'] = $page->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Page not found.', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Page not found.', $e->getMessage(), '', 1, 422);
    }
});
/**
 * GET PagePageIdGet.
 * Summary: Get page.
 * Notes: Get page.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/pages/{pageId}/{pageSlug}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        if (empty($queryParams['lang_code'])) {
            $queryParams['lang_code'] = SITE_DAFAULT_LANGUAGE;
        }
        $page = Models\Page::Filter($queryParams)->where('slug', $request->getAttribute('pageSlug'))->first();
        if (!empty($page)) {
            $result['data'] = $page->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Page not found.', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Page not found.', $e->getMessage(), '', 1, 422);
    }
});
/**
 * PUT PagepageIdPut
 * Summary: Update page by admin
 * Notes: Update page by admin
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/pages/{pageId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $queryParams = $request->getQueryParams();
    $result = array();
    $page = Models\Page::Filter($queryParams)->find($request->getAttribute('pageId'));
    $validationErrorFields = $page->validate($args);
    if (empty($validationErrorFields)) {
        $oldPageTitle = $page->title;
        $page->fill($args);
        try {
            //$page->slug = getSlug($page->title);
            $page->save();
            $result['data'] = $page->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'Page could not be updated. Please, try again.', $e->getMessage(), '', 1, 422);
        }
    } else {
        return renderWithJson($result, 'Page could not be updated. Please, try again.', '', $validationErrorFields, 1, 422);
    }
})->add(new ACL('canUpdatePage'));
/**
 * DELETE PagepageIdDelete
 * Summary: DELETE page by admin
 * Notes: DELETE page by admin
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/pages/{pageId}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    $page = Models\Page::Filter($queryParams)->find($request->getAttribute('pageId'));
    try {
        if (!empty($page)) {
            $page->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Page Notfound', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Page could not be deleted. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canDeletePage'));
/**
 * GET SettingcategoriesGet
 * Summary: Filter  Setting categories
 * Notes: Filter Setting categories.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/setting_categories', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {        
        $settingCategories = Models\SettingCategory::Filter($queryParams)->paginate()->toArray();
        $data = $settingCategories['data'];        
        unset($settingCategories['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $settingCategories
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
});
/**
 * GET SettingcategoriesSettingCategoryIdGet
 * Summary: Get setting categories.
 * Notes: GEt setting categories.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/setting_categories/{settingCategoryId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $settingCategory = Models\SettingCategory::Filter($queryParams)->find($request->getAttribute('settingCategoryId'));
        if (!empty($settingCategory)) {
            $result['data'] = $settingCategory->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Setting Category not found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Setting Category not found', $e->getMessage(), '', 1, 422);
    }
});

$app->GET('/api/v1/settings/site_languages', function ($request, $response, $args)
{
    $result = array();
    $site_languages = explode(',', SITE_LANGUAGES);
    if(!empty($site_languages)){
        $result['site_languages'] = Models\Language::whereIn('iso2', $site_languages)->get();
    }else{
        $result['preferred_locale'] = Models\Language::where('iso2', SITE_DAFAULT_LANGUAGE)->first();
    }
    return renderWithJson($result);
});

/**
 * GET SettingGet .
 * Summary: Get settings.
 * Notes: GEt settings.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/settings', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $settings = Models\Setting::Filter($queryParams);
        if (!empty($queryParams['limit']) && $queryParams['limit'] == 'all') {
            $settings = $settings->get()->toArray();
        } else {
            $settings = $settings->paginate()->toArray();
        }
        $data = $settings['data'];
        unset($settings['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $settings
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
});
/**
 * GET settingssettingIdGet
 * Summary: GET particular Setting.
 * Notes: Get setting.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/settings/{settingId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $setting = Models\Setting::Filter($queryParams)->find($request->getAttribute('settingId'));
        if (!empty($setting)) {
            $result['data'] = $setting->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Setting not found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Setting not found', $e->getMessage(), '', 1, 422);
    }
});
/**
 * PUT SettingsSettingIdPut
 * Summary: Update setting by admin
 * Notes: Update setting by admin
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/settings/{settingId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $setting = Models\Setting::find($request->getAttribute('settingId'));
    try {
        if (!empty($setting)) {
            $setting->fill($args);
            if ($setting->name == 'ALLOWED_SERVICE_LOCATIONS') {
                $country_list = array();
                $city_list = array();
                $allowed_locations = array();
                if (!empty(!empty($args['allowed_countries']))) {
                    foreach ($args['allowed_countries'] as $key => $country) {
                        $country_list[$key]['id'] = $country['id'];
                        $country_list[$key]['name'] = $country['name'];
                        $country_list[$key]['iso2'] = '';
                        $country_details = Models\Country::select('iso2')->where('id', $country['id'])->first();
                        if (!empty($country_details)) {
                            $country_list[$key]['iso2'] = $country_details->iso2;
                        }
                    }
                    $allowed_locations['allowed_countries'] = $country_list;
                }
                if (!empty(!empty($args['allowed_cities']))) {
                    foreach ($args['allowed_cities'] as $key => $city) {
                        $city_list[$key]['id'] = $city['id'];
                        $city_list[$key]['name'] = $city['name'];
                    }
                    $allowed_locations['allowed_cities'] = $city_list;
                }
                $setting->value = json_encode($allowed_locations);
            }
            $setting->save();
            $result['data'] = $setting->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Setting not found.', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Setting could not be updated. Please, try again.', $e->getMessage(), '', 1, 422);
    }
});
/**
 * GET EmailTemplateGet
 * Summary: Get email templates lists
 * Notes: Get email templates lists
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/email_templates', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $enabled_plugins = getEnabledPlugin();
        $emailTemplates = Models\EmailTemplate::Filter($queryParams)->whereIn('plugin', $enabled_plugins)->orwhere('plugin', null)->paginate()->toArray();
        $data = $emailTemplates['data'];
        unset($emailTemplates['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $emailTemplates
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
});
/**
 * GET EmailTemplateemailTemplateIdGet
 * Summary: Get paticular email templates
 * Notes: Get paticular email templates
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/email_templates/{emailTemplateId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $emailTemplate = Models\EmailTemplate::Filter($queryParams)->find($request->getAttribute('emailTemplateId'));
        if (!empty($emailTemplate)) {
            $result['data'] = $emailTemplate->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Email Template not found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Email Template not found', $e->getMessage(), '', 1, 422);
    }
});
/**
 * PUT EmailTemplateemailTemplateIdPut
 * Summary: Put paticular email templates
 * Notes: Put paticular email templates
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/email_templates/{emailTemplateId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $emailTemplate = Models\EmailTemplate::find($request->getAttribute('emailTemplateId'));
    if (!empty($emailTemplate)) {
        $validationErrorFields = $emailTemplate->validate($args);
        if (empty($validationErrorFields)) {
            $emailTemplate->fill($args);
            try {
                $emailTemplate->save();
                $result['data'] = $emailTemplate->toArray();
                return renderWithJson($result);
            } catch (Exception $e) {
                return renderWithJson($result, 'Email template could not be updated. Please, try again.', $e->getMessage(), '', 1, 422);
            }
        } else {
            return renderWithJson($result, 'Email template could not be updated. Please, try again', '', $validationErrorFields, 1, 422);
        }
    } else {
        return renderWithJson($result, 'Email Template not found', '', '', 1, 404);
    }
});
$app->GET('/api/v1/cities', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $cities = Models\City::Filter($queryParams)->paginate()->toArray();
        $data = $cities['data'];
        unset($cities['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $cities
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
});
/**
 * POST citiesPost
 * Summary: create new city
 * Notes: create new city
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/cities', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $city = new Models\City($args);
    $validationErrorFields = $city->validate($args);
    if (empty($validationErrorFields)) {
        try {
            $city->save();
            $result['data'] = $city->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'City could not be added. Please, try again.', $e->getMessage(), '', 1, 422);
        }
    } else {
        return renderWithJson($result, 'city could not be added. Please, try again.', '', $validationErrorFields, 1, 422);
    }
})->add(new ACL('canCreateCity'));
/**
 * GET CitiesGet
 * Summary: Get  particular city
 * Notes: Get  particular city
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/cities/{cityId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $city = Models\City::Filter($queryParams)->find($request->getAttribute('cityId'));
        if (!empty($city)) {
            $result['data'] = $city->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'City not found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'City not found.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canViewCity'));
/**
 * PUT CitiesCityIdPut
 * Summary: Update city by admin
 * Notes: Update city by admin
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/cities/{cityId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $queryParams = $request->getQueryParams();
    $result = array();
    $city = Models\City::find($request->getAttribute('cityId'));
    if (!empty($city)) {
        $validationErrorFields = $city->validate($args);
        if (empty($validationErrorFields)) {
            $city->fill($args);
            // $city->slug = Inflector::slug(strtolower($city->name), '-');
            try {
                $city->save();
                $result['data'] = $city->toArray();
                return renderWithJson($result);
            } catch (Exception $e) {
                return renderWithJson($result, 'City could not be updated. Please, try again.', $e->getMessage(), '', 1, 422);
            }
        } else {
            return renderWithJson($result, 'City could not be updated. Please, try again.', '', $validationErrorFields, 1, 422);
        }
    } else {
        return renderWithJson($result, 'City not found', '', '', 1, 404);
    }
})->add(new ACL('canUpdateCity'));
/**
 * DELETE CitiesCityIdDelete
 * Summary: DELETE city by admin
 * Notes: DELETE city by admin
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/cities/{cityId}', function ($request, $response, $args) {
    $result = array();
    $city = Models\City::find($request->getAttribute('cityId'));
    try {
        if (!empty($city)) {
            $city->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'City not found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'City Could not be deleted. Please try again later.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canDeleteCity'));
/**
 * GET StatesGet
 * Summary: Filter  states
 * Notes: Filter states.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/states', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $states = Models\State::Filter($queryParams)->paginate()->toArray();
        $data = $states['data'];
        unset($states['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $states
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
});
/**
 * POST StatesPost
 * Summary: Create New states
 * Notes: Create states.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/states', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $state = new Models\State($args);
    $validationErrorFields = $state->validate($args);
    if (empty($validationErrorFields)) {
        try {
            $state->save();
            $result['data'] = $state->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'State could not be added. Please, try again', $e->getMessage(), '', 1, 422);
        }
    } else {
        return renderWithJson($result, 'State could not be added. Please, try again', '', $validationErrorFields, 1, 422);
    }
})->add(new ACL('canCreateState'));
/**
 * GET StatesstateIdGet
 * Summary: Get  particular state
 * Notes: Get  particular state
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/states/{stateId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $state = Models\State::Filter($queryParams)->find($request->getAttribute('stateId'));
        if (!empty($state)) {
            $result['data'] = $state->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'State not found.', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'State not found.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canViewState'));
/**
 * PUT StatesStateIdPut
 * Summary: Update states by admin
 * Notes: Update states.
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/states/{stateId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $state = Models\State::find($request->getAttribute('stateId'));
    if (!empty($state)) {
        $validationErrorFields = $state->validate($args);
        if (empty($validationErrorFields)) {
            $state->fill($args);
            try {
                $state->save();
                $result['data'] = $state->toArray();
                return renderWithJson($result);
            } catch (Exception $e) {
                return renderWithJson($result, 'State could not be updated. Please, try again.', $e->getMessage(), '', 1, 422);
            }
        } else {
            return renderWithJson($result, 'State could not be updated. Please, try again.', '', $validationErrorFields, 1, 422);
        }
    } else {
        return renderWithJson($result, 'State not found.', '', '', 1, 404);
    }
})->add(new ACL('canUpdateState'));
/**
 * DELETE StatesStateIdDelete
 * Summary: DELETE states by admin
 * Notes: DELETE states by admin
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/states/{stateId}', function ($request, $response, $args) {
    $result = array();
    $state = Models\State::find($request->getAttribute('stateId'));
    try {
        if (!empty($state)) {
            $state->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'State not found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'State could not be added. Please, try again', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canDeleteState'));
/**
 * GET countriesGet
 * Summary: Filter  countries
 * Notes: Filter countries.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/countries', function ($request, $response, $args) use ($app) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $countries = Models\Country::Filter($queryParams)->paginate()->toArray();
        $data = $countries['data'];
        unset($countries['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $countries
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
});
/**
 * POST countriesPost
 * Summary: Create New countries
 * Notes: Create countries.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/countries', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $country = new Models\Country($args);
    $validationErrorFields = $country->validate($args);
    if (empty($validationErrorFields)) {
        try {
            $country->save();
            $result['data'] = $country->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'Country could not be added. Please, try again.', $e->getMessage(), '', 1, 422);
        }
    } else {
        return renderWithJson($result, 'Country could not be added. Please, try again.', '', $validationErrorFields, 422);
    }
})->add(new ACL('canCreateCountry'));
/**
 * GET countriescountryIdGet
 * Summary: Get countries
 * Notes: Get countries.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/countries/{countryId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $country = Models\Country::Filter($queryParams)->find($request->getAttribute('countryId'));
        if (!empty($country)) {
            $result['data'] = $country->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Country not found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Country not found.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canViewCountry'));
/**
 * PUT countriesCountryIdPut
 * Summary: Update countries by admin
 * Notes: Update countries.
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/countries/{countryId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $country = Models\Country::find($request->getAttribute('countryId'));
    if (!empty($country)) {
        $validationErrorFields = $country->validate($args);
        if (empty($validationErrorFields)) {
            $country->fill($args);
            try {
                $country->save();
                $result = $country->toArray();
                return renderWithJson($result);
            } catch (Exception $e) {
                return renderWithJson($result, 'Country could not be updated. Please, try again.', $e->getMessage(), '', 1, 422);
            }
        } else {
            return renderWithJson($result, 'Country could not be updated. Please, try again.', $validationErrorFields, 1, 422);
        }
    } else {
        return renderWithJson($result, 'Country not found', '', '', 1, 404);
    }
})->add(new ACL('canUpdateCountry'));
/**
 * DELETE countrycountryIdDelete
 * Summary: DELETE country by admin
 * Notes: DELETE country.
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/countries/{countryId}', function ($request, $response, $args) {
    $result = array();
    $country = Models\Country::find($request->getAttribute('countryId'));
    try {
        if (!empty($country)) {
            $country->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Country not found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Country could not be deleted. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canDeleteCountry'));
/**
 * POST AttachmentPost
 * Summary: Add attachment
 * Notes: Add attachment.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/attachments', function ($request, $response, $args) {
    $args = $request->getQueryParams();
    $response = array();
    try {
        $file = $request->getUploadedFiles();
        if (isset($file['file'])) {
            $response = uploadFile($file);
            if (!empty($response['error']['code'])) {
                return renderWithJson($response, $response['error']['message'], '', '', 1, 422);
            }
            return renderWithJson($response);
        } else {
            return renderWithJson($response, 'Attachment could not be added.', '', '', 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($response, 'Attachment could not be added. Please, try again.', $e->getMessage(), '', 1, 422);
    }
});
/**
 * DELETE attachmentsAttachmentIdDelete
 * Summary: Delete attachment
 * Notes: Deletes a single attachment based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/attachments/{attachmentId}', function($request, $response, $args) {
    global $authUser, $capsule;      
	$result = array();
    $is_delete = false;    
	$attachment = Models\Attachment::with('foreign')->find($request->getAttribute('attachmentId'));
	try {
		if (!empty($attachment)) {
            if($attachment['class'] == 'ListingPhoto' ) {
                if ($authUser['id'] == $attachment['foreign']['attachments']['user_id'] || $authUser['role_id'] == \Constants\ConstUserTypes::Admin) {
                    $is_delete = true;     
                }       
            } 
            if($is_delete == true)  {
                $attachment = Models\Attachment::with('foreign')->find($request->getAttribute('attachmentId'));
                $attachment->delete();
                $result = array(
                    'status' => 'success',
                );
                return renderWithJson($result);
            } else {
                return renderWithJson($result, 'Attachment could not be deleted. Please, try again.', '', 1, 401);
            }			
		} else {
			return renderWithJson($result, 'No record found', '', 1);
		}
	} catch(Exception $e) {
		return renderWithJson($result, 'Attachment could not be deleted. Please, try again.', '', 1);
	}
})->add(new ACL('canDeleteAttachment'));
$app->GET('/api/v1/admin-config', function ($request, $response, $args) {
    $plugins = getEnabledPlugin();
    $corePlugins = array(
        'Message/Message',
        'Category/Category'
    );
    $plugins = array_merge($plugins, $corePlugins);
    $compiledMenus = $compiledTables = $mainJson = '';
    $file = __DIR__ . '/../public/admin-config.php';
    $list_mode = true;
    $create_mode = true;
    $edit_mode = true;
    $delete_mode = true;
    $show_mode = true;
    $resultSet = array();
    if (file_exists($file)) {
        require_once $file;
        if (!empty($menus)) {
            $resultSet['menus'] = $menus;
        }
        if (isset($dashboard)) {
            if (!empty($resultSet['dashboard'])) {
                $resultSet['dashboard'] = array_merge($resultSet['dashboard'], $dashboard);
            } else {
                $resultSet['dashboard'] = $dashboard;
            }
        }
        if (!empty($tables)) {
            $resultSet['tables'] = $tables;
            $tableName = current(array_keys($resultSet['tables']));
            if ($list_mode === false) {
                unset($resultSet['tables'][$tableName]['listview']);
            } else {
                if ($create_mode === false) {
                    unset($resultSet['tables'][$tableName]['listview']['actions'][2]);
                }
                if ($edit_mode === false) {
                    unset($resultSet['tables'][$tableName]['listview']['listActions'][0]);
                }
                if ($show_mode === false) {
                    unset($resultSet['tables'][$tableName]['listview']['actions'][1]);
                }
                if ($delete_mode === false) {
                    unset($resultSet['tables'][$tableName]['listview']['actions'][2]);
                }
            }
            if ($create_mode === false) {
                unset($resultSet['tables'][$tableName]['creationview']);
            }
            if ($edit_mode === false) {
                unset($resultSet['tables'][$tableName]['editionview']);
            }
            if ($delete_mode === false) {
                unset($resultSet['tables'][$tableName]['showview']);
            }
        }
    }
    if (!empty($plugins)) {
        foreach ($plugins as $plugin) {
            $file = __DIR__ . '/../plugins/' . $plugin . '/admin-config.php';
            if (file_exists($file)) {
                require_once $file;
                if (!empty($resultSet['menus'])) {
                    foreach ($menus as $key => $menu) {
                        if (isset($resultSet['menus'][$key])) {
                            $resultSet['menus'][$key]['child_sub_menu'] = array_merge($resultSet['menus'][$key]['child_sub_menu'], $menu['child_sub_menu']);
                        } else {
                            $resultSet['menus'][$key] = $menu;
                        }
                    }
                } elseif (!empty($menus)) {
                    $resultSet['menus'] = $menus;
                }
                if (!empty($dashboard)) {
                    if (!empty($resultSet['dashboard'])) {
                        $resultSet['dashboard'] = array_merge($resultSet['dashboard'], $dashboard);
                    } else {
                        $resultSet['dashboard'] = $dashboard;
                    }
                }
                if (!empty($tables)) {
                    $tableName = current(array_keys($tables));
                    if ($list_mode === false) {
                        unset($tables[$tableName]['listview']);
                    } else {
                        if ($create_mode === false) {
                            unset($tables[$tableName]['listview']['actions'][2]);
                        }
                        if ($edit_mode === false) {
                            unset($tables[$tableName]['listview']['listActions'][0]);
                        }
                        if ($show_mode === false) {
                            unset($tables[$tableName]['listview']['actions'][1]);
                        }
                        if ($delete_mode === false) {
                            unset($tables[$tableName]['listview']['actions'][2]);
                        }
                    }
                    if ($create_mode === false) {
                        unset($tables['tables'][$tableName]['creationview']);
                    }
                    if ($edit_mode === false) {
                        unset($tables[$tableName]['editionview']);
                    }
                    if ($delete_mode === false) {
                        unset($tables[$tableName]['showview']);
                    }
                    if (!empty($resultSet['tables'])) {
                        $resultSet['tables'] = array_merge($resultSet['tables'], $tables);
                    } else {
                        $resultSet['tables'] = $tables;
                    }
                }
            }
        }
        usort($resultSet['menus'], function ($a, $b) {
            return $a['order'] - $b['order'];
        });
        foreach ($resultSet['menus'] as $key => $value) {
            $resultSet['menus'][$key]['child_sub_menu'] = menu_sub_array_sorting($resultSet['menus'][$key]['child_sub_menu'], 'suborder', SORT_ASC);
        }
        foreach ($resultSet['tables'] as $key => $table) {
            if ($key == 'user_cash_withdrawals') {
                foreach ($table as $view_key => $view) {
                    $fields = menu_sub_array_sorting($resultSet['tables'][$key][$view_key]['fields'], 'suborder', SORT_ASC);
                    if (count($fields) > 0) {
                        foreach ($fields as $field) {
                            $field_list[] = $field;
                        }
                        $resultSet['tables'][$key][$view_key]['fields'] = $field_list;
                        $field_list = array();
                    }
                }
            }
        }
    }
    echo json_encode($resultSet);
    exit;
});
$app->GET('/api/v1/appointment_statuses', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        $appointment_statuses = Models\AppointmentStatus::Filter($queryParams)->paginate()->toArray();
        $data = $appointment_statuses['data'];
        unset($appointment_statuses['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $appointment_statuses
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
});
/**
 * GET appointmentStatusesAppointmentStatusIdGet
 * Summary: Fetch appointment status
 * Notes: Returns a appointment status based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/appointment_statuses/{appointmentStatusId}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        $appointmentStatus = Models\AppointmentStatus::Filter($queryParams)->find($request->getAttribute('appointmentStatusId'));
        if (!empty($appointmentStatus)) {
            $result['data'] = $appointmentStatus;
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
});
//Appointment Modification
//post
$app->POST('/api/v1/appointment_modifications', function ($request, $response, $args) {
    global $authUser;
    $args = $request->getParsedBody();
    $appointment_modifications = new Models\AppointmentModification($args);
    $result = array();
    try {
        $appointment_modification_error = true;
        if ($args['type'] == \Constants\ConstAppointmentModificationType::UnavailableParticularDateAndTime && (empty($args['unavailable_from_time']) || empty($args['unavailable_from_time']))) {
            $appointment_modification_error = false;
            $validationError[] = array(
                'Unavailable from time and Unavailable to time is required '
            );
        } elseif ($args['type'] == \Constants\ConstAppointmentModificationType::MakeADayFullyOff && empty($args['unavailable_date'])) {
            $appointment_modification_error = false;
            $validationError[] = array(
                'Unavailable date is required '
            );
        }
        $validationErrorFields = $appointment_modifications->validate($args);
        if (empty($validationErrorFields) && $appointment_modification_error == true) {
            if ($appointment_modifications->save()) {
                $result['data'] = $appointment_modifications->toArray();
                return renderWithJson($result);
            } else {
                return renderWithJson($result, 'AppointmentModifications could not be added. Please, try again.', '', '', 1, 422);
            }
        } else {
            if (!empty($validationErrorFields)) {
                $validationError[] = $validationErrorFields->toArray();
            }
            return renderWithJson($result, 'AppointmentModifications could not be added. Please, try again.', '', $validationError, 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'AppointmentModifications could not be added. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canCreateAppointmentModifications'));
$app->POST('/api/v1/appointment_modifications/multiple', function ($request, $response, $args) {
    global $authUser;
    $args = $request->getParsedBody();
    $result = array();
    if ($authUser->role_id == \Constants\ConstUserTypes::Admin) {
        $user_id = $args['user_id'];
    } else {
        $user_id = $authUser->id;
    }    
    $appointmentModifications = array();
    try {
        $appointment_modification_error = true;
        if ($args['appointment_modifications']) {
            foreach ($args['appointment_modifications'] as $appointment_modification) {               
                $appointment_modification['user_id'] = $user_id;
                $appointmentModifications[] =  $appointment_modification;
                $appointmentModification  = new Models\AppointmentModification;
                $appointmentModification_validation_error = $appointmentModification->validate($appointment_modification);
                if(!empty($appointmentModification_validation_error)){
                    $appointment_modification_error = false;
                }                
                if ($appointment_modification['type'] == \Constants\ConstAppointmentModificationType::UnavailableParticularDateAndTime && (empty($appointment_modification['unavailable_from_time']) || empty($appointment_modification['unavailable_from_time']))) {
                    $appointment_modification_error = false;
                    $validationError[] = array(
                        'Unavailable from time and Unavailable to time is required '
                    );
                } elseif ($appointment_modification['type'] == \Constants\ConstAppointmentModificationType::MakeADayFullyOff && empty($appointment_modification['unavailable_date'])) {
                    $appointment_modification_error = false;
                    $validationError[] = array(
                        'Unavailable date is required '
                    );
                }
            }
        }
        if ($appointment_modification_error == true) {
            if (!empty($appointmentModifications)) {
                foreach($appointmentModifications as $appointmentModification){
                    if (isset($appointmentModification['id'])) {
                        $appointmentModification_obj  = Models\AppointmentModification::find($appointmentModification['id']);
                    } else {
                        $appointmentModification_obj  = new Models\AppointmentModification;
                    }                                                 
                    $appointmentModification_obj->fill($appointmentModification);
                    if ($appointmentModification_obj->save()) {
                        $ids[] = $appointmentModification_obj->id;
                    }                           
                }
                $appointment_modifications  = Models\AppointmentModification::whereIN('id', $ids)->get(); 
                $result['data'] = $appointment_modifications->toArray();
                return renderWithJson($result);                                                   
            } else {
                return renderWithJson($result, 'AppointmentModifications could not be added. Please, try again.', '', '', 1, 422);
            }
        } else {
            return renderWithJson($result, 'AppointmentModifications could not be added. Please, try again.', '', $validationError, 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'AppointmentModifications could not be added. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canCreateAppointmentModificationsMultiple'));
//get all
$app->GET('/api/v1/appointment_modifications', function ($request, $response, $args) {
    global $authUser;
    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        $appointment_modifications = Models\AppointmentModification::Filter($queryParams)->paginate()->toArray();
        $data = $appointment_modifications['data'];
        unset($appointment_modifications['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $appointment_modifications
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canListAppointmentModifications'));
//get me appointment modifications
$app->GET('/api/v1/me/appointment_modifications', function ($request, $response, $args) {
    global $authUser;
    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        $appointment_modifications = Models\AppointmentModification::Filter($queryParams)->where('user_id', $authUser->id)->paginate()->toArray();
        $data = $appointment_modifications['data'];
        unset($appointment_modifications['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $appointment_modifications
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canGetMeAppointmentModifications'));
//put
$app->PUT('/api/v1/appointment_modifications/{appointmentModificationId}', function ($request, $response, $args) {
    global $authUser;
    $args = $request->getParsedBody();
    $result = array();
    $appointment_modifications = Models\AppointmentModification::find($request->getAttribute('appointmentModificationId'));
    if ($authUser->role_id != \Constants\ConstUserTypes::Admin && $appointment_modifications->user_id != $authUser->id) {
        return renderWithJson($result, 'Authentication failed! Please try again!', '', '', 1, 401);
    }
    if (!empty($appointment_modifications)) {
        try {
            $validationErrorFields = $appointment_modifications->validate($args);
            if (empty($validationErrorFields)) {
                $appointment_modifications->fill($args);
                if ($appointment_modifications->save()) {
                    $result['data'] = $appointment_modifications->toArray();
                    return renderWithJson($result);
                } else {
                    return renderWithJson($result, 'AppointmentModifications could not be updated. Please, try again.', '', '', 1, 422);
                }
            } else {
                return renderWithJson($result, 'AppointmentModifications could not be updated. Please, try again.', '', $validationErrorFields, 1, 422);
            }
        } catch (Exception $e) {
            return renderWithJson($result, 'AppointmentModifications could not be updated. Please, try again.', $e->getMessage(), '', 1, 422);
        }
    } else {
        return renderWithJson($result, 'AppointmentModifications could not found.', '', '', 1, 404);
    }
})->add(new ACL('canUpdateAppointmentModifications'));
//get single
$app->GET('/api/v1/appointment_modifications/{appointmentModificationId}', function ($request, $response, $args) {
    global $authUser;
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $appointment_modifications = Models\AppointmentModification::Filter($queryParams)->find($request->getAttribute('appointmentModificationId'));
        if (!empty($appointment_modifications)) {
            $result['data'] = $appointment_modifications->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'AppointmentModifications could not found.', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'User device not found. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canViewAppointmentModifications'));
//delete
$app->DELETE('/api/v1/appointment_modifications/multiple', function ($request, $response, $args) {
    global $authUser;
    $args = $request->getParsedBody();
    $result = array();
    try {
        if (!empty($args['ids'])) {
            foreach ($args['ids'] as $id) {
                $appointment_modifications = Models\AppointmentModification::find($id['id']);
                if (!$appointment_modifications->delete()) {
                    return renderWithJson($result, 'AppointmentModifications could not be deleted. Please, try again.', '', '', 1, 422);
                }            
            }
            $result = array(
                'status' => 'success',
            );            
            return renderWithJson($result);  
        } else {
            return renderWithJson($result, 'Sorry! No record found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'AppointmentModifications could not be deleted. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canDeleteAppointmentModificationsMultiple'));
$app->DELETE('/api/v1/appointment_modifications/{appointmentModificationId}', function ($request, $response, $args) {
    global $authUser;
    $appointment_modifications = Models\AppointmentModification::find($request->getAttribute('appointmentModificationId'));
    $result = array();
    try {
        if (!empty($appointment_modifications)) {
            if ($appointment_modifications->delete()) {
                $result = array(
                    'status' => 'success',
                );
                return renderWithJson($result);
            } else {
                return renderWithJson($result, 'AppointmentModifications could not be deleted. Please, try again.', '', '', 1, 422);
            }
        } else {
            return renderWithJson($result, 'Sorry! No record found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'AppointmentModifications could not be deleted. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canDeleteAppointmentModifications'));
$app->GET('/api/v1/languages', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        $languages = Models\Language::Filter($queryParams)->paginate()->toArray();
        $data = $languages['data'];
        unset($languages['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $languages
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
});
/**
 * DELETE languagesLanguageIdDelete
 * Summary: Delete language
 * Notes: Deletes a single language based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/languages/{languageId}', function ($request, $response, $args) {
    $language = Models\Language::find($request->getAttribute('languageId'));
    $result = array();
    try {
        if (!empty($language)) {
            $language->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Sorry! No record found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Language could not be deleted. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canDeleteLanguage'));
/**
 * GET languagesLanguageIdGet
 * Summary: Fetch language
 * Notes: Returns a language based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/languages/{languageId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $language = Models\Language::Filter($queryParams)->find($request->getAttribute('languageId'));
        if (!empty($language)) {
            $result['data'] = $language;
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Sorry! No record found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canViewLanguage'));
/**
 * PUT languagesLanguageIdPut
 * Summary: Update language by its id
 * Notes: Update language by its id
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/languages/{languageId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $language = Models\Language::find($request->getAttribute('languageId'));
    $result = array();
    if (!empty($language)) {
        $language->fill($args);
        try {
            $validationErrorFields = $language->validate($args);
            if (empty($validationErrorFields)) {
                $language->save();
                $result['data'] = $language->toArray();
                return renderWithJson($result);
            } else {
                return renderWithJson($result, 'Language could not be updated. Please, try again.', '', $validationErrorFields, 1, 422);
            }
        } catch (Exception $e) {
            return renderWithJson($result, 'Language could not be updated. Please, try again.', $e->getMessage(), '', 1, 422);
        }
    } else {
        return renderWithJson($result, 'Sorry! No record found', '', '', 1, 404);
    }
})->add(new ACL('canUpdateLanguage'));
/**
 * POST languagesPost
 * Summary: Creates a new language
 * Notes: Creates a new language
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/languages', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $language = new Models\Language($args);
    $result = array();
    try {
        $validationErrorFields = $language->validate($args);
        if (empty($validationErrorFields)) {
            $language->save();
            $result['data'] = $language->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Language could not be added. Please, try again.', '', $validationErrorFields, 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Language could not be added. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canCreateLanguage'));
/**
 * GET userLoginsGet
 * Summary: Fetch all user logins
 * Notes: Returns all user logins from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/user_logins', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        $userLogins = Models\UserLogin::Filter($queryParams)->paginate()->toArray();
        $data = $userLogins['data'];
        unset($userLogins['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $userLogins
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canListUserLogin'));
/**
 * DELETE userLoginIdDelete
 * Summary: Delete user_logins
 * Notes: Deletes a single  user_logins based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/user_logins/{userLoginId}', function ($request, $response, $args) {
    $result = array();
    $UserLogin = Models\UserLogin::find($request->getAttribute('userLoginId'));
    if (empty($UserLogin)) {
        return renderWithJson($result, 'No record found.', '', 1);
    }
    try {
        $UserLogin->delete();
        $result = array(
            'status' => 'success',
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'UserLogin could not be deleted. Please, try again.', '', 1);
    }
})->add(new ACL('canDeleteUserLogin'));
/**
 * GET CitieuserLoginIdsGet
 * Summary: Get  particular userLogin
 * Notes: Get  particular userLogin
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/user_logins/{userLoginId}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    $UserLogin = Models\UserLogin::Filter($queryParams)->find($request->getAttribute('userLoginId'));
    if (!empty($UserLogin)) {
        $result['data'] = $UserLogin->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'Sorry! No record found', '', '', 1, 422);
    }
})->add(new ACL('canviewUserLogin'));
/**
 * GET userViewsGet
 * Summary: Fetch all user views
 * Notes: Returns all user views from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/user_views', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        $userViews = Models\UserView::Filter($queryParams)->paginate()->toArray();
        $data = $userViews['data'];
        unset($userViews['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $userViews
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canListUserView'));
$app->GET('/api/v1/user_views/{userViewId}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    $userViews = Models\UserView::Filter($queryParams)->find($request->getAttribute('userViewId'));
    if (!empty($userViews)) {
        $result['data'] = $userViews->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'Sorry! No record found', '', '', 1, 404);
    }
})->add(new ACL('canViewUserView'));
$app->DELETE('/api/v1/user_views/{userViewId}', function ($request, $response, $args) {
    global $authUser;
    $userViews = Models\UserView::find($request->getAttribute('userViewId'));
    $result = array();
    try {
        if (!empty($userViews)) {
                $userViews->delete();
                $result = array(
                    'status' => 'success',
                );
                return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Sorry! No record found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canDeleteUserView'));
/**
 * GET contactsContactIdGet
 * Summary: Fetch contact
 * Notes: Returns a contact based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/contacts/{contactId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $contact = Models\Contact::Filter($queryParams)->find($request->getAttribute('contactId'));
        if (!empty($contact)) {
            $result['data'] = $contact;
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Sorry! No record found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canViewContact'));
/**
 * DELETE contactsContactIdDelete
 * Summary: Delete contact
 * Notes: Deletes a single contact based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/contacts/{contactId}', function ($request, $response, $args) {
    global $authUser;
    $contact = Models\Contact::find($request->getAttribute('contactId'));
    $result = array();
    try {
        if (!empty($contact)) {
            if ($authUser->role_id == \Constants\ConstUserTypes::Admin || $authUser->id == $contact->user_id) {
                $contact->delete();
                $result = array(
                    'status' => 'success',
                );
                return renderWithJson($result);
            } else {
                return renderWithJson($result, 'Authentication Failed', '', '', 1, 401);
            }
        } else {
            return renderWithJson($result, 'Sorry! No record found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canDeleteContact'));
/**
 * PUT contactsContactIdPut
 * Summary: Update contact by its id
 * Notes: Update contact by its id
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/contacts/{contactId}', function ($request, $response, $args) {
    global $authUser;
    $result = array();
    $args = $request->getParsedBody();
    $contact = Models\Contact::find($request->getAttribute('contactId'));
    try {
        if ($authUser->role_id == \Constants\ConstUserTypes::Admin || $authUser->id == $contact->user_id) {
            if (!empty($contact)) {
                $contact->fill($args);
                $validationErrorFields = $contact->validate($args);
                if (empty($validationErrorFields)) {
                    $contact->save();
                    $result['data'] = $contact->toArray();
                    return renderWithJson($result);
                } else {
                    return renderWithJson($result, 'Contact could not be updated. Please, try again.', '', $validationErrorFields, 1, 422);
                }
            } else {
                return renderWithJson($result, 'Sorry! No record found', '', '', 1, 404);
            }
        } else {
            return renderWithJson($result, 'Authentication Failed', '', '', 1, 401);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Contact could not be updated. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canUpdateContact'));
/**
 * GET contactsGet
 * Summary: Fetch all contacts
 * Notes: Returns all contacts from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/contacts', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        $contacts = Models\Contact::Filter($queryParams)->paginate()->toArray();
        $data = $contacts['data'];
        unset($contacts['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $contacts
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canListContact'));
/**
 * POST contactsPost
 * Summary: Creates a new contact
 * Notes: Creates a new contact
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/contacts', function ($request, $response, $args) {
    global $authUser;
    $args = $request->getParsedBody();
    $contact = new Models\Contact($args);
    if (!empty($authUser['id'])) {
        $contact->user_id = $authUser->id;
    }
    $result = array();
    try {
        $validationErrorFields = $contact->validate($args);
        if (empty($validationErrorFields)) {
            $contact->save();
            if (!empty($args['image'])) {
                foreach ($args['image'] as $image) {
                    saveImage('Contact', $image['attachment'], $contact->id, 1);
                }
            }            
            if (!empty($args['image_data'])) {
                foreach ($args['image_data'] as $image_data) {
                    saveImageData('Contact', $image_data['attachment'], $contact->id, 1);
                }
            }
            $result['data'] = $contact->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Contact could not be added. Please, try again.', '', $validationErrorFields, 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Contact could not be added. Please, try again.', $e->getMessage(), '', 1, 422);
    }
});
$app->GET('/api/v1/appointments', function ($request, $response, $args) {
    global $authUser;
    $queryParams = $request->getQueryParams();
    $appointments = Models\Appointment::Filter($queryParams)->paginate()->toArray();
    $data = $appointments['data'];
    unset($appointments['data']);
    $results = array(
        'data' => $data,
        '_metadata' => $appointments
    );
    return renderWithJson($results);
})->add(new ACL('canGetAppointments'));
$app->GET('/api/v1/appointments/{appointmentId}', function ($request, $response, $args) {
    global $authUser;
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $appointments = Models\Appointment::Filter($queryParams)->find($request->getAttribute('appointmentId'));
        if ($authUser->role_id != \Constants\ConstUserTypes::Admin && $appointments->user_id != $authUser->id && $appointments->provider_user_id != $authUser->id) {
            return renderWithJson($result, 'Authentication failed! Please try again!', '', '', 1, 401);
        }
        if (!empty($appointments)) {
            $result['data'] = $appointments;
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Sorry! No record found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canViewAppointments'));
$app->POST('/api/v1/appointments', function ($request, $response, $args) {
    global $authUser, $_server_domain_url;
    $result = array();
    $args = $request->getParsedBody();
    $appointment = new Models\Appointment;
    try {
        $args['user_id'] = $authUser->id;
        if (isset($args['appointment_status_id']) && isset($args['customer_note'])) {
            if ($args['appointment_status_id'] != \Constants\ConstAppointmentStatus::Enquiry) {
                unset($args['appointment_status_id']);
                unset($args['customer_note']);
            }
        }
        if ((isPluginEnabled('PaymentBooking/PaymentBooking')) && !isset($args['appointment_status_id'])) {
            $args['appointment_status_id'] = \Constants\ConstAppointmentStatus::PaymentPending;
        } elseif (!isset($args['appointment_status_id'])) {
            $args['appointment_status_id'] = \Constants\ConstAppointmentStatus::PendingApproval;
        }  
        $validationErrorFields = $appointment->validate($args);
        if (is_object($validationErrorFields)) {
            $validationErrorFields = $validationErrorFields->toArray();
        }
        $validationErrorFields = empty($validationErrorFields) ? [] : $validationErrorFields;
        $appointment_validation = Models\Appointment::appointmentValidation($args);
        if (isset($appointment_validation['error'])) {
            $validationErrorFields = array_merge($validationErrorFields, $appointment_validation['error']);
        }
        $serviceDetails = Models\ServiceUser::with('service')->find($args['services_user_id']);
        if (!empty($serviceDetails)) {
            if (empty($args['work_location_address']) && !empty($serviceDetails->service->is_need_user_location)) {
                 $validation['error']['work_location_address'] = ['Work location address required'];
                 $validationErrorFields = array_merge($validationErrorFields, $validation);
            } 
            if (empty($args['work_location_postal_code']) && !empty($serviceDetails->service->is_need_user_location)) {
                 $validation['error']['work_location_postal_code'] = ['Work location pincode required'];
                 $validationErrorFields = array_merge($validationErrorFields, $validation);
            } 
            if (!empty($args['work_location_country']['iso2'])) {
                $work_location_country_id = findCountryIdFromIso2($args['work_location_country']['iso2']);
                $args['work_location_country_id'] = $work_location_country_id;
            } elseif (empty($args['work_location_country']['iso2']) && !empty($serviceDetails->service->is_need_user_location)) {
                 $validation['error']['work_location_country_id'] = ['Work location country required'];
                 $validationErrorFields = array_merge($validationErrorFields, $validation);
            } else {
                $args['work_location_country_id'] = null;
            }
            if (!empty($args['work_location_state']['name']) && !empty($args['work_location_country_id'])) {
                $work_location_state_id = findOrSaveAndGetStateId($args['work_location_state']['name'], $args['work_location_country_id']);
                $args['work_location_state_id'] = $work_location_state_id;
            } elseif (isset($args['work_location_state']['name'])) {
                $args['work_location_state_id'] = null;
            }
            if (!empty($args['work_location_city']['name']) && !empty($args['work_location_country_id']) && !empty($args['work_location_state_id'])) {
                $work_location_city_id = findOrSaveAndGetCityId($args['work_location_city']['name'], $args['work_location_country_id'], $args['work_location_state_id']);
                $args['work_location_city_id'] = $work_location_city_id;
            } elseif (empty($args['work_location_city']['name']) && !empty($serviceDetails->service->is_need_user_location)) {
                 $validation['error']['work_location_city_id'] = ['Work location city required'];
                 $validationErrorFields = array_merge($validationErrorFields, $validation);
            } else {
                $args['work_location_city_id'] = null;
            }   
        }
        if (empty($validationErrorFields)) {
            //TODO
            $appointment->fill($args);
            $appointment->appointment_from_date = $appointment_validation['data']['appointment_from_date'];
            $appointment->appointment_to_date = !empty($appointment_validation['data']['appointment_to_date']) ? $appointment_validation['data']['appointment_to_date'] : null;
            $appointment->appointment_from_time = !empty($appointment_validation['data']['appointment_from_time']) ? $appointment_validation['data']['appointment_from_time'] : null;
            $appointment->appointment_to_time = !empty($appointment_validation['data']['appointment_to_time']) ? $appointment_validation['data']['appointment_to_time'] : null;
            $appointment->no_of_days = $appointment_validation['data']['no_of_days'];
            $appointment->booked_minutes = !empty($appointment_validation['data']['booked_minutes']) ? $appointment_validation['data']['booked_minutes'] : 0;
            $appointment->total_booking_amount = $appointment_validation['data']['total_booking_amount'];
             if (isPluginEnabled('Interview/Interview') && !empty($appointment->is_appointment_for_interview) && !empty(SITE_COMMISSION_FOR_INTERVIEW_FROM_SERVICE_PROVIDER)) {
                 $appointment->site_commission_from_freelancer = ((SITE_COMMISSION_FOR_INTERVIEW_FROM_SERVICE_PROVIDER / 100) * $appointment->total_booking_amount);
             } else {
                 $appointment->site_commission_from_freelancer = !empty(SITE_COMMISSION_FOR_BOOKING_FROM_SERVICE_PROVIDER) ? ((SITE_COMMISSION_FOR_BOOKING_FROM_SERVICE_PROVIDER / 100) * $appointment->total_booking_amount) : 0;
             }
            $appointment->provider_user_id = $appointment_validation['data']['provider_user_id'];
            $appointment->service_id = $appointment_validation['data']['service_id'];
            $appointment->cancellation_policy_id = $appointment_validation['data']['cancellation_policy_id'];
            if (isPluginEnabled('Referral/Referral')) {
                $total_booking_amount = $appointment->total_booking_amount - $authUser->affiliate_pending_amount;
                if ($total_booking_amount < 0) {
                    $affiliate_pending_amount = abs($total_booking_amount);
                    $affiliate_paid_amount = $authUser->affiliate_paid_amount + $appointment->total_booking_amount;
                    $used_affiliate_amount = $appointment->total_booking_amount;
                    $total_booking_amount = 0;
                } else {
                    $affiliate_pending_amount = 0;
                    $affiliate_paid_amount = $authUser->affiliate_paid_amount + $authUser->affiliate_pending_amount;
                    $used_affiliate_amount = $authUser->affiliate_pending_amount;
                }
                $appointment->used_affiliate_amount = !empty($used_affiliate_amount) ? $used_affiliate_amount : 0.00;
                $appointment->total_booking_amount = $total_booking_amount;
            }
            if ($appointment->save()) {
                $appointment->id = $appointment->id;
                $provider_user = Models\User::find($appointment->provider_user_id);
                if ($appointment->appointment_status_id == \Constants\ConstAppointmentStatus::Enquiry) {
                    Models\Message::saveEnquiry($authUser->id, $appointment);
                    $appointment->payment_type = 'sale';
                    $appointment->paid_escrow_amount_at = date('Y-m-d H:i:s');
                    if (IS_SITE_ENABLED_PUSH_NOTIFICATION) {
                        $followMessage = array(
                            'message_type' => 'PUSH_NOTIFICATION_FOR_NEW_ENQUIRY_TO_SERVICE_PROVIDER',
                            'appointment_id' => $appointment->id
                        );
                        addPushNotification($appointment->provider_user_id, $followMessage);
                    }
                    if (isPluginEnabled('SMS/SMS')) {
                        $message = array(
                            'appointment_id' => $appointment->id,
                            'message_type' => 'SMS_FOR_NEW_ENQUIRY_TO_SERVICE_PROVIDER'
                        );
                        Models\Sms::sendSMS($message, $appointment->provider_user_id);
                    }
                } else {
                    if (isPluginEnabled('PaymentBooking/PaymentBooking')) {
                        $appointment->appointment_status_id = \Constants\ConstAppointmentStatus::PaymentPending;
                    } else {
                        $appointment->appointment_status_id = \Constants\ConstAppointmentStatus::PendingApproval;
                        if (IS_SITE_ENABLED_PUSH_NOTIFICATION) {
                            $followMessage = array(
                                'message_type' => 'PUSH_NOTIFICATION_FOR_NEW_BOOKING',
                                'appointment_id' => $appointment->id
                            );
                            addPushNotification($appointment->provider_user_id, $followMessage);
                        }
                        if (isPluginEnabled('SMS/SMS')) {
                            $message = array(
                                'appointment_id' => $appointment->id,
                                'message_type' => 'SMS_FOR_NEW_BOOKING'
                            );
                            Models\Sms::sendSMS($message, $appointment->provider_user_id);
                        }
                    }
                    
                }

                $total_booking_amount = $appointment->total_booking_amount;
                $appointment->site_commission_from_customer = 0;
                if (isPluginEnabled('Interview/Interview') && !empty($appointment->is_appointment_for_interview) && !empty(SITE_COMMISSION_FOR_INTERVIEW_FROM_CUSTOMER)) {
                    $site_commission_from_customer = (SITE_COMMISSION_FOR_INTERVIEW_FROM_CUSTOMER / 100) * $appointment->total_booking_amount;
                    $appointment->site_commission_from_customer = $site_commission_from_customer;
                    $total_booking_amount = $appointment->total_booking_amount + $site_commission_from_customer;
                } elseif (empty($appointment->is_appointment_for_interview) && !empty(SITE_COMMISSION_FOR_BOOKING_FROM_CUSTOMER)) {
                    $site_commission_from_customer = (SITE_COMMISSION_FOR_BOOKING_FROM_CUSTOMER / 100) * $appointment->total_booking_amount;
                    $appointment->site_commission_from_customer = $site_commission_from_customer;
                    $total_booking_amount = $appointment->total_booking_amount + $site_commission_from_customer;
                }
                $appointment->update();                
                $result = $appointment->toArray();                
                if (!empty($args['payment_gateway_id']) && isPluginEnabled('PaymentBooking/PaymentBooking')) {
                    if ($total_booking_amount > 0) {
                        $args['name'] = $args['description'] = "Booking amount";
                        $args['amount'] = $total_booking_amount;
                        $args['payment_type'] = $appointment->payment_type;
                        $args['appointment_status_id'] = $appointment->appointment_status_id;
                        $args['id'] = $appointment->id;
                        $args['success_url'] = $_server_domain_url . '/appointments/success/' . $appointment->id . '?error_code=0';
                        $args['cancel_url'] = $_server_domain_url . '/appointments/cancelled/' . $appointment->id . '/tests?error_code=512';                        
                        $result = Models\Payment::processPayment($appointment->id, $args, 'Appointment');
                    } else {
                        if($appointment->appointment_status_id == \Constants\ConstAppointmentStatus::PaymentPending){
                            $appointment->appointment_status_id = \Constants\ConstAppointmentStatus::PendingApproval;
                        }
                        $appointment->update();
                        if (isPluginEnabled('Referral/Referral')) {
                            Models\User::where('id', $authUser->id)->update(array(
                                'affiliate_paid_amount' => $affiliate_paid_amount,
                                'affiliate_pending_amount' => $affiliate_pending_amount
                            ));
                        }
                        $result = $appointment->toArray();
                    }
                }
                if (!empty($args['form_field_submissions']) && $appointment['id']) {
                    foreach ($args['form_field_submissions'] as $formFieldSubmissions) {
                        foreach ($formFieldSubmissions as $form_field_id => $value) {
                            $formField = Models\FormField::where('id', $form_field_id)->select('id', 'name')->first();
                            if (!empty($formField)) {
                                $formFieldSubmission = new Models\FormFieldSubmission;
                                $formFieldSubmission->response = $value;
                                $formFieldSubmission->form_field_id = $formField->id;
                                $formFieldSubmission->foreign_id = $appointment['id'];
                                $formFieldSubmission->class = 'Appointment';
                                $formFieldSubmission->save();
                            }
                        }
                    }
                }
                return renderWithJson($result);
            }
        } else {
            return renderWithJson($result, 'Appointment Could not be added', '', $validationErrorFields, 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Appointment Could not be added', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canBookAppointments'));
$app->GET('/api/v1/me/appointments', function ($request, $response, $args) {
    global $authUser;
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $appointments = Models\Appointment::Filter($queryParams);
        $appointments = $appointments->where(function($q) use($authUser){
            $q->where('user_id', $authUser->id);
            $q->orWhere('provider_user_id', $authUser->id);
        });
        if (!empty($appointments)) {
            $appointments = $appointments->paginate()->toArray();
            $data = $appointments['data'];
            unset($appointments['data']);
            $results = array(
                'data' => $data,
                '_metadata' => $appointments
            );
            return renderWithJson($results);
        } else {
            return renderWithJson($result, 'Sorry! No record found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canGetOwnAppointments'));
$app->GET('/api/v1/service_provider/{serviceProviderId}/appointments/calendar', function ($request, $response, $args) {
    global $authUser;
    $queryParams = $request->getQueryParams();
    $result = array();
    $appointment_setting = Models\AppointmentSetting::where('user_id',$request->getAttribute('serviceProviderId'))->first();
    $start_date = !empty($queryParams['month']) && !empty($queryParams['year']) ? date('Y-m-d', mktime(0, 0, 0, $queryParams['month'], 1, $queryParams['year'])) : date('Y-m-d', strtotime('first day of this month', strtotime(date('Y-m-d'))));
    $end_date = !empty($queryParams['month']) && !empty($queryParams['year']) ?  date("Y-m-t", strtotime($start_date)) : date('Y-m-d', strtotime('last day of this month', strtotime(date('Y-m-d'))));
    $timeslot = \Constants\ConstBookingOption::TimeSlot;
    $multihours =  \Constants\ConstBookingOption::MultiHours;
    $service_user = Models\ServiceUser::where('user_id',$request->getAttribute('serviceProviderId'))->whereHas('service', function ($q) use ($timeslot, $multihours)  {
            $q->whereIn('booking_option_id', [$timeslot, $multihours]);
    })->count();
    if(empty($queryParams['type']))
    {
        if($service_user > 0)
        {
            $queryParams['type'] = 'timewise';
        }
        else
        {
            $queryParams['type'] = 'daywise';
        }
    }
    if(!empty($queryParams) && $queryParams['type'] == 'timewise')
    {
        $calendar_slot_id = $appointment_setting->calendar_slot_id;
        $slots = array();
        $appointment_slots = array();
        for($date = strtotime($start_date); $date <= strtotime($end_date); $date = strtotime('+1 day', $date)){
            $send_array = array();
            $practice_open = "";
            $practice_close = "";
            if($appointment_setting->type == \Constants\ConstAppointmentSettingType::IndividualDays)
            {
                $is_dayname = 'is_'.strtolower(date('l', $date)).'_open';
                if(empty($appointment_setting->$is_dayname)){
                    $appoinment_modification = Models\AppointmentModification::where('user_id',$request->getAttribute('serviceProviderId'))->where('unavailable_date', date('Y-m-d', $date))->where('type', \Constants\ConstAppointmentModificationType::MakeADayFullyOn)->first();
                    if(!empty($appoinment_modification)) {
                        $send_array['status'] = 'available';
                    } else {
                        $send_array['status'] = 'week-off';
                    }                    
                }
                $day_practice_open = strtolower(date('l', $date)).'_practice_open';
                $day_practice_close = strtolower(date('l', $date)).'_practice_close';
                $practice_open = $appointment_setting->$day_practice_open;
                $practice_close = $appointment_setting->$day_practice_close;
            }else{
                $practice_open = $appointment_setting->practice_open;
                $practice_close = $appointment_setting->practice_close;
            }
            $appointment_slots[date('Y-m-d',$date)] = generateSlot($date, $practice_open, $practice_close, $calendar_slot_id, $send_array);
            $appointment_modification = Models\AppointmentModification::where('user_id',$request->getAttribute('serviceProviderId'))->where(function($q) use($date){
                $q->where('unavailable_date', date('Y-m-d',$date));
                $q->orWhere('day', ucfirst(date('l', $date)));
                $q->orWhere('day', 'AllDay');
            })->get();
            if(!empty($appointment_modification))
            {
                foreach($appointment_modification as $appoint_mod)
                {
                    $send_array['status'] = 'unavailable';
                    if($appoint_mod->type == \Constants\ConstAppointmentModificationType::MakeADayFullyOn){
                        $send_array['status'] = 'available';
                    }
                    if($appoint_mod->type == \Constants\ConstAppointmentModificationType::MakeADayFullyOff){
	
                        $send_array['status'] = 'full-day-off';
                    } 
                    if($appoint_mod->unavailable_from_time == null){
                        $appoint_mod->unavailable_from_time = '00:00:00';
                    }
                    if($appoint_mod->unavailable_to_time == null){
                        $appoint_mod->unavailable_to_time = '23:59:59';
                    }   
                    $send_array['appointment_modification'] = $appoint_mod->toArray();                                     
                    $slots = generateSlot($date, $appoint_mod->unavailable_from_time, $appoint_mod->unavailable_to_time, $calendar_slot_id, $send_array);
                    $appointment_slots[date('Y-m-d',$date)] = array_merge($appointment_slots[date('Y-m-d',$date)], $slots);
                }
            }
            $appointments = Models\Appointment::with('user')->where('provider_user_id',$request->getAttribute('serviceProviderId'))->where('appointment_from_date','<=', date('Y-m-d',$date))->where('appointment_to_date','>=',date('Y-m-d',$date))->whereIn('appointment_status_id',[\Constants\ConstAppointmentStatus::Approved, \Constants\ConstAppointmentStatus::PendingApproval])->get();
            if(!empty($appointments))
            {
                foreach($appointments as $appointment)
                {
                    $send_array['status'] = 'booked';
                    $send_array['appointment'] = $appointment->toArray();
                    if($appointment['appointment_status_id'] == \Constants\ConstAppointmentStatus::PendingApproval) {
                        $send_array['color'] = '#FFA500';
                    }
                    $slots = generateSlot($date, $appointment->appointment_from_time, $appointment->appointment_to_time, $calendar_slot_id, $send_array);
                    $appointment_slots[date('Y-m-d',$date)] = array_merge($appointment_slots[date('Y-m-d',$date)], $slots);
                    
                }
            }
        }
        $new_array=array();
        if(!empty($appointment_slots))
        {
            foreach($appointment_slots as $key => $return_array){ 
                $return_array = array_values($return_array);
                for($i=0; $i < count($return_array); $i++){
                    $temp = array();
                    $temp['title'] = $return_array[$i]['title']; 
                    $temp['start'] = $return_array[$i]['start']; 
                    $temp['end'] = $return_array[$i]['end']; 
                    $temp['rendering'] = $return_array[$i]['rendering']; 
                    $temp['color'] = $return_array[$i]['color']; 
                    if(!empty($return_array[$i]['appointment_modification'])){
                        $temp['appointment_modification_id'] = $return_array[$i]['appointment_modification']['id']; 
                    }                    
                    for($j=$i; $j< count($return_array); $j++){
                        if($return_array[$i]['status'] != $return_array[$j]['status']){
                            $temp['end'] = !empty($return_array[$j]['start']) ? $return_array[$j]['start'] : $return_array[$j-1]['end'];
                            $new_array[] = $temp;
                            $i=$j-1;
                            break;
                        }else{
                            $temp['end'] = $return_array[$j]['end'];
                            if ($j == (count($return_array)-1)) {
                                $new_array[] = $temp;
                                $i=$j;
                            }                            
                        }
                    }
                }
            }
        }
        if(!empty($queryParams['available']))
        {
            foreach($new_array as $key => $value)
            {
                if($value['title'] != 'Available'){
                    unset($new_array[$key]);
                }
            }
        }
        return renderWithJson($new_array);
    }elseif(!empty($queryParams) && $queryParams['type'] == 'daywise'){
        $new_array = array(); 
        for($date = strtotime($start_date); $date <= strtotime($end_date); $date = strtotime('+1 day', $date))
        {
            $temp_array = array(
                'title' => 'Available',
                'rendering' => 'background', 
                'color'=> '#59d771',
                'start' => date('Y-m-d', $date),
                'end' => date('Y-m-d', $date)
            );
            $appointment_modification = Models\AppointmentModification::where('user_id',$request->getAttribute('serviceProviderId'))->where('unavailable_date', date('Y-m-d', $date))->where('type', '!=', \Constants\ConstAppointmentModificationType::UnavailableInEveryParticularDayAndTime
            )->first();
            if(!empty($appointment_modification))
            {
                $temp_array['title'] = 'full-day-off';
                if($appointment_modification->type == \Constants\ConstAppointmentModificationType::MakeADayFullyOn){
                    $temp_array['title'] = 'Available';
                }
                $temp_array['appointment_modification_id'] = $appointment_modification['id'];
            }
            $appointments = Models\Appointment::with('user')->where('provider_user_id',$request->getAttribute('serviceProviderId'))->where('appointment_from_date','<=', date('Y-m-d', $date))->where('appointment_to_date','>=', date('Y-m-d', $date))->whereIn('appointment_status_id',[\Constants\ConstAppointmentStatus::Approved, \Constants\ConstAppointmentStatus::PendingApproval])->first();
            if(!empty($appointments))
            {
                $temp_array['title'] = 'Booked by '.$appointments['user']['username'];
                $temp_array['appointment_id'] = $appointments['id'];
                $temp_array['appointment_status_id'] = $appointments['appointment_status_id'];
                if($appointments['appointment_status_id'] == \Constants\ConstAppointmentStatus::PendingApproval) {
                    $temp_array['color'] = '#FFA500';
                }
            }
            $is_dayname = 'is_'.strtolower(date('l', $date)).'_open';
            if(empty($appointment_setting->$is_dayname)){
                $appoinment_modification = Models\AppointmentModification::where('user_id',$request->getAttribute('serviceProviderId'))->where('unavailable_date', date('Y-m-d', $date))->where('type', \Constants\ConstAppointmentModificationType::MakeADayFullyOn)->first();
                if(!empty($appoinment_modification)) {
                    $temp_array['title'] = 'Available';
                } else {
                    $temp_array['title'] = 'week-off';
                }
            }
            $new_array[] = $temp_array;
        }
        if(!empty($queryParams['available']))
        {
            foreach($new_array as $key => $value)
            {
                if($value['title'] != 'Available') {
                    unset($new_array[$key]);
                }
            }
        }
        return renderWithJson($new_array);
    }
});
$app->GET('/api/v1/me/contacts', function ($request, $response, $args) {
    global $authUser;
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $user_appointments = Models\Appointment::whereNotIn('appointment_status_id', [\Constants\ConstAppointmentStatus::PaymentPending, \Constants\ConstAppointmentStatus::PreApproved, \Constants\ConstAppointmentStatus::Expired])->where('user_id', $authUser->id)->get()->toArray();
        $provider_user_appointments = Models\Appointment::whereNotIn('appointment_status_id', [\Constants\ConstAppointmentStatus::PaymentPending, \Constants\ConstAppointmentStatus::PreApproved, \Constants\ConstAppointmentStatus::Expired])->where('provider_user_id', $authUser->id)->get()->toArray();
        $user_ids = array();
        if (!empty($user_appointments)) {
            foreach ($user_appointments as $appointment) {
                $user_ids[] = $appointment['provider_user_id'];
            }
        }
        if (!empty($provider_user_appointments)) {
            foreach ($provider_user_appointments as $appointment) {
                $user_ids[] = $appointment['user_id'];
            }
        }
        $get_user = Models\User::Filter($queryParams)->whereIn('id', $user_ids)->paginate()->toArray();
        $data = $get_user['data'];
        unset($get_user['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $get_user
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($result, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canGetOtherUsers'));
$app->PUT('/api/v1/appointments/{appointmentId}', function ($request, $response, $args) {
    global $authUser;
    $result = array();
    $args = $request->getParsedBody();
    $appointment = Models\Appointment::find($request->getAttribute('appointmentId'));
    if ($authUser->role_id != \Constants\ConstUserTypes::Admin && $appointment->user_id != $authUser->id && $appointment->provider_user_id != $authUser->id) {
        return renderWithJson($result, 'Authentication failed! Please try again!', '', '', 1, 401);
    }
    if (!empty($args['work_location_country']['iso2'])) {
        $work_location_country_id = findCountryIdFromIso2($args['work_location_country']['iso2']);
        $args['work_location_country_id'] = $work_location_country_id;
    } elseif (isset($args['work_location_country']['iso2'])) {
        $args['work_location_country_id'] = '';
    }
    if (!empty($args['work_location_state']['name']) && !empty($args['work_location_country_id'])) {
        $work_location_state_id = findOrSaveAndGetStateId($args['work_location_state']['name'], $args['work_location_country_id']);
        $args['work_location_state_id'] = $work_location_state_id;
    } elseif (isset($args['work_location_state']['name'])) {
        $args['work_location_state_id'] = '';
    }
    if (!empty($args['work_location_city']['name']) && !empty($args['work_location_country_id']) && !empty($args['work_location_state_id'])) {
        $work_location_city_id = findOrSaveAndGetCityId($args['work_location_city']['name'], $args['work_location_country_id'], $args['work_location_state_id']);
        $args['work_location_city_id'] = $work_location_city_id;
    } elseif (isset($args['work_location_city']['name'])) {
        $args['work_location_city_id'] = '';
    }     
    if (!empty($appointment)) {
        $appointment->fill($args);
        $validationErrorFields = $appointment->validate($args);
        if (empty($validationErrorFields)) {
            $appointment->save();
            $result['data'] = $appointment->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Appointment could not be updated. Please, try again.', '', $validationErrorFields, 1, 422);
        }
    } else {
        return renderWithJson($result, 'Sorry! No record found', '', '', 1, 422);
    }
})->add(new ACL('canUpdateAppointments'));
$app->DELETE('/api/v1/appointments/{appointmentId}', function ($request, $response, $args) {
    $result = array();
    $appointment = Models\Appointment::find($request->getAttribute('appointmentId'));
    if (!empty($appointment)) {
        if ($appointment->delete()) {
            return renderWithJson($result, 'Appointment deleted successfully');
        } else {
            return renderWithJson($result, 'Unable to delete.', '', '', 1, 422);
        }
    } else {
        return renderWithJson($result, 'No record found.', '', '', 1, 404);
    }
})->add(new ACL('canDeleteAppointments'));
$app->PUT('/api/v1/appointments/{appointmentId}/change_status', function ($request, $response, $args) {
    global $authUser, $_server_domain_url;
    $args = $request->getParsedBody();
    $appointment = Models\Appointment::find($request->getAttribute('appointmentId'));
    $admin = Models\User::where('role_id', \Constants\ConstUserTypes::Admin)->first();
    $oldstatus = $appointment->appointment_status_id;
    $result = array();
    if (!empty($appointment) && !empty($args['appointment_status_id'])) {
        $service_provider = Models\User::with('user_profile')->where('id', $appointment->provider_user_id)->first();
        $request_user = Models\User::with('user_profile')->where('id', $appointment->user_id)->first();
        if (!empty($service_provider->user_profile->first_name) || !empty($service_provider->user_profile->last_name)) {
            $service_username = $service_provider->user_profile->first_name .' '.$service_provider->user_profile->last_name;
        } else {
            $service_username = $service_provider->email; 
        }  
        if (!empty($request_user->user_profile->first_name) || !empty($request_user->user_profile->last_name)) {
            $request_username = $request_user->user_profile->first_name .' '.$request_user->user_profile->last_name;
        } else {
            $request_username = $request_user->email; 
        }         
        $oldAppointmentStatus = $appointment->appointment_status_id;
        $newAppointmentStatus = $args['appointment_status_id'];
        if ($oldAppointmentStatus != $newAppointmentStatus) {
            if (($authUser->role_id == \Constants\ConstUserTypes::Admin) || $authUser->id == $appointment->user_id || $authUser->id == $appointment->provider_user_id) {
                $msg = '';
                switch ($newAppointmentStatus) {
                    case \Constants\ConstAppointmentStatus::Approved:
                        if (($authUser->role_id == \Constants\ConstUserTypes::Admin || $authUser->id == $appointment->provider_user_id) && $oldAppointmentStatus == \Constants\ConstAppointmentStatus::PendingApproval) {
                            if ((isPluginEnabled('PayPal/PayPal')) && (isPluginEnabled('PaymentBooking/PaymentBooking')) && $appointment->payment_type == 'authorize' && $appointment->total_booking_amount > 0) {
                                $authorization = authorizePayment($appointment->authorization_id);
                                if (is_object($authorization)){
                                    if ($authorization->getState() == 'authorized') {
                                        $payment = capturePayment($authorization, $appointment->total_booking_amount);
                                        
                                        if (!empty($payment) && is_object($payment)) {
                                            $appointment->paypal_status = $payment->getState();
                                            $appointment->capture_id = $payment->getId();
                                        }
                                    }
                                }else{
                                    return renderWithJson($result, $authorization['error_message'], '', 1, 422);
                                }
                            }
                            if (!empty($appointment->request_id)) {
                                $appointment->appointment_status_id = \Constants\ConstAppointmentStatus::Closed;
                            } else {
                                $appointment->appointment_status_id = \Constants\ConstAppointmentStatus::Approved;
                            }
                            if ($appointment->update()) {
                                if (($appointment->appointment_status_id == \Constants\ConstAppointmentStatus::Approved || $appointment->appointment_status_id == \Constants\ConstAppointmentStatus::Closed) && !empty($appointment->capture_id)) {
                                    $transaction_type = \Constants\TransactionType::BookingAcceptedAndAmountMovedToEscrow;
                                    insertTransaction($appointment->user_id, $appointment->provider_user_id, $appointment->id, 'Appointment', $transaction_type, $appointment->payment_gateway_id, $appointment->total_booking_amount, 0, 0, null, $appointment->site_commission_from_customer);                      
                                    $total_spent_amount_as_customer = Models\Appointment::updateAmountInCustomerProfile($appointment->user_id);                                 
                                }
                            }
                            $msg = "Booking Approved Successfully";
                            $emailFindReplace = array(
                            '##SERVICE_PROVIDER##' => $service_username,
                            '##REQUESTOR_NAME##' => $request_username,
                            '##LINK##' => $_server_domain_url . '/#/appointments',
                            '##DATE##' => $appointment->appointment_from_date,
                            );
                            sendMail('Service Request Accept', $emailFindReplace, $request_user->email);
                            if (!empty($request_user->referred_by_user_id) && isPluginEnabled('Referral/Referral')) {
                                    $exist_appoinment_count = Models\Appointment::where('user_id', $request_user->referred_by_user_id)->whereIn('appointment_status_id', array(
                                        \Constants\ConstAppointmentStatus::Approved,
                                        \Constants\ConstAppointmentStatus::Closed,
                                        \Constants\ConstAppointmentStatus::Present,
                                        \Constants\ConstAppointmentStatus::Completed
                                    ))->count();
                                    if ($exist_appoinment_count == 1) {
                                        $referred_by_user = Models\User::find($request_user->referred_by_user_id);
                                        $referred_by_user->affiliate_pending_amount = $referred_by_user->affiliate_pending_amount + AFFILIATE_REFERRAL_AMOUNT_FOR_AFFILIATE;
                                        $referred_by_user->update();
                                    }
                            }
                            if (IS_SITE_ENABLED_PUSH_NOTIFICATION) {
                                    $followMessage = array(
                                        'message_type' => 'PUSH_NOTIFICATION_FOR_BOOKING_ACCEPTED_BY_SERVICE_PROVIDER',
                                        'appointment_id' => $appointment->id
                                    );
                                    addPushNotification($appointment->user_id, $followMessage);
                            }
                            if (isPluginEnabled('SMS/SMS')) {
                                    $message = array(
                                        'appointment_id' => $appointment->id,
                                        'message_type' => 'SMS_FOR_BOOKING_ACCEPTED_BY_SERVICE_PROVIDER'
                                    );
                                    Models\Sms::sendSMS($message, $appointment->user_id);
                            }
                        } else {
                            return renderWithJson($result, "Requestor could not be updated in the status. Please, try again", '', 1);
                        }
                        break;
                    case \Constants\ConstAppointmentStatus::PreApproved:
                        if (($authUser->role_id == \Constants\ConstUserTypes::Admin || $authUser->id == $appointment->provider_user_id) && $oldAppointmentStatus == \Constants\ConstAppointmentStatus::Enquiry) {
                            if (empty($args['total_booking_amount'])) {
                                return renderWithJson($result, "Total booking amount required", '', 1);
                            }
                            $appointment->appointment_status_id = \Constants\ConstAppointmentStatus::PreApproved;
                            $appointment->total_booking_amount = $args['total_booking_amount'];
                            $appointment->site_commission_from_freelancer = !empty(SITE_COMMISSION_FOR_BOOKING_FROM_SERVICE_PROVIDER) ? ((SITE_COMMISSION_FOR_BOOKING_FROM_SERVICE_PROVIDER / 100) * $appointment->total_booking_amount) : 0;
                            $appointment->update();
                            $msg = "Booking PreApproved Successfully";
                            $emailFindReplace = array(
                            '##SERVICE_PROVIDER##' => $service_username,
                            '##REQUESTOR_NAME##' => $request_username,
                            '##LINK##' => $_server_domain_url . '/#/appointments',
                            '##DATE##' => $appointment->appointment_from_date
                            );
                            sendMail('Service Request Accept', $emailFindReplace, $request_user->email);
                            if (IS_SITE_ENABLED_PUSH_NOTIFICATION) {
                                    $followMessage = array(
                                        'message_type' => 'PUSH_NOTIFICATION_FOR_PAID_AND_BOOKING_CONFIRMED_BY_REQUESTOR',
                                        'appointment_id' => $appointment->id
                                    );
                                    addPushNotification($appointment->user_id, $followMessage);
                            }
                            if (isPluginEnabled('SMS/SMS')) {
                                    $message = array(
                                        'appointment_id' => $appointment->id,
                                        'message_type' => 'SMS_FOR_PAID_AND_BOOKING_CONFIRMED_BY_REQUESTOR'
                                    );
                                    Models\Sms::sendSMS($message, $appointment->user_id);
                            }
                        } else {
                            return renderWithJson($result, "Requestor could not be updated in the status. Please, try again", '', 1);
                        }
                        break;
                    case \Constants\ConstAppointmentStatus::Rejected:
                        if (($authUser->role_id == \Constants\ConstUserTypes::Admin || $authUser->id == $appointment->provider_user_id) && ($oldAppointmentStatus == \Constants\ConstAppointmentStatus::PendingApproval || $oldAppointmentStatus == \Constants\ConstAppointmentStatus::Enquiry)) {
                            if ((isPluginEnabled('PayPal/PayPal')) && $appointment->payment_type == 'authorize') {
                                if ($appointment->paypal_status == 'authorized') {
                                    $payment = voidPayment($appointment->authorization_id);
                                    if (!empty($payment)) {
                                        $appointment->paypal_status = $payment->getState();
                                    }
                                }
                            }
                            $appointment->appointment_status_id = \Constants\ConstAppointmentStatus::Rejected;
                            if ($appointment->update()) {
                                if ($appointment->appointment_status_id == \Constants\ConstAppointmentStatus::Rejected && !empty($appointment->authorization_id)) {
                                    $transaction_type = \Constants\TransactionType::BookingDeclinedAndVoided;
                                    insertTransaction($appointment->provider_user_id, $appointment->user_id, $appointment->id, 'Appointment', $transaction_type, $appointment->payment_gateway_id, $appointment->total_booking_amount, $appointment->site_commission_from_freelancer);
                                }
                            }
                            $msg = "Booking Rejected Successfully";
                            $emailFindReplace = array(
                            '##SERVICE_PROVIDER##' => $service_username,
                            '##REQUESTOR_NAME##' => $request_username,
                            '##LINK##' => $_server_domain_url . '/#/users'
                            );
                            sendMail('Service Request Reject', $emailFindReplace, $request_user->email);
                        } else {
                            return renderWithJson($result, "Requestor could not be updated in the status. Please, try again", '', 1);
                        }
                        break;
                    case \Constants\ConstAppointmentStatus::Closed:
                        $appointment_allowed_status = array (
                            \Constants\ConstAppointmentStatus::Completed
                        );
                        $interview_allowed_status = array (
                            \Constants\ConstAppointmentStatus::Approved,
                            \Constants\ConstAppointmentStatus::Completed,
                            \Constants\ConstAppointmentStatus::Present
                        );

                        if ($authUser->role_id == \Constants\ConstUserTypes::Admin || ($authUser->id == $appointment->user_id && ((empty($appointment->is_appointment_for_interview) && in_array($oldAppointmentStatus, $appointment_allowed_status)) || (!empty($appointment->is_appointment_for_interview) && in_array($oldAppointmentStatus, $interview_allowed_status))))) {
                            Models\Appointment::closeAppointment($appointment->id);
                            $msg = "Booking Closed Successfully";
                        } else {
                            return renderWithJson($result, "Service Provider could not be updated in the status. Please, try again", '', 1);
                        }
                        break;
                    case \Constants\ConstAppointmentStatus::Cancelled:
                        $allowed_status = array(
                        \Constants\ConstAppointmentStatus::Enquiry,
                        \Constants\ConstAppointmentStatus::PreApproved,
                        \Constants\ConstAppointmentStatus::Approved,
                        \Constants\ConstAppointmentStatus::PendingApproval
                            );
                        if (($authUser->role_id == \Constants\ConstUserTypes::Admin || $authUser->id == $appointment->user_id) && in_array($oldAppointmentStatus, $allowed_status)) {
                            if ($oldAppointmentStatus == \Constants\ConstAppointmentStatus::PendingApproval) {
                                if ((isPluginEnabled('PayPal/PayPal')) && $appointment->payment_type == 'authorize' && $appointment->total_booking_amount > 0) {
                                    if ($appointment->paypal_status == 'authorized') {
                                        $payment = voidPayment($appointment->authorization_id);
                                        if (!empty($payment)) {
                                            $appointment->paypal_status = $payment->getState();
                                        }
                                    }
                                }
                            }
                            if ($oldAppointmentStatus == \Constants\ConstAppointmentStatus::Approved) {
                                $appointment_from_date = ($appointment->appointment_from_date .' '. $appointment->appointment_from_time);
                                $now = date('Y-m-d H:i:s');
                                $date1=date_create($now);
                                $date2=date_create($appointment_from_date);
                                $diff=date_diff($date1,$date2);
                                $daydiff =  $diff->format("%a");
                                //$daydiff = ($appointment_from_date - time()) / (24 * 60 * 60);die;
                                $cancellation_policy = Models\CancellationPolicy::find($appointment->cancellation_policy_id);
                                if (!empty($cancellation_policy) && isPluginEnabled('CancellationPolicies/CancellationPolicies') && $appointment->total_booking_amount > 0) {
                                    if ($cancellation_policy->days_before <= $daydiff) {
                                        $appointment->refunded_amount = ($cancellation_policy->days_before_refund_percentage / 100) * $appointment->total_booking_amount;
                                    } elseif ($cancellation_policy->days_after > $daydiff && $daydiff >= 0 ) {
                                        $appointment->refunded_amount = ($cancellation_policy->days_after_refund_percentage / 100) * $appointment->total_booking_amount;
                                    }
                                } else {
                                    $appointment->refunded_amount = $appointment->total_booking_amount;
                                }                                
                                if ((isPluginEnabled('PayPal/PayPal')) && ($appointment->payment_type == 'authorize' || $appointment->payment_type == 'sale') && $appointment->refunded_amount > 0) {
                                    if ($appointment->paypal_status == 'completed' || $appointment->paypal_status == 'approved') {
                                        $payment = refundPayment($appointment);
                                        if (!empty($payment)) {
                                            $appointment->paypal_status = $payment->getState();
                                        }
                                    }
                                }
                            }
                            $appointment->appointment_status_id = \Constants\ConstAppointmentStatus::Cancelled;
                            if ($appointment->update()) {
                                $transaction_type = \Constants\TransactionType::BookingCanceledAndVoided;
                                if ($oldAppointmentStatus == \Constants\ConstAppointmentStatus::PendingApproval && $appointment->appointment_status_id == \Constants\ConstAppointmentStatus::Cancelled && !empty($appointment->authorization_id)) {
                                    insertTransaction($appointment->provider_user_id, $appointment->user_id, $appointment->id, 'Appointment', $transaction_type, $appointment->payment_gateway_id, $appointment->total_booking_amount, $appointment->site_commission_from_freelancer);
                                } elseif ($oldAppointmentStatus == \Constants\ConstAppointmentStatus::Approved && $appointment->appointment_status_id == \Constants\ConstAppointmentStatus::Cancelled && $appointment->paypal_status == 'completed') {
                                    insertTransaction($appointment->provider_user_id, $appointment->user_id, $appointment->id, 'Appointment', $transaction_type, $appointment->payment_gateway_id, $appointment->refunded_amount, $appointment->site_commission_from_freelancer);
                                }
                            }
                            $msg = "Booking Cancelled Successfully";
                            $emailFindReplace = array(
                                '##SERVICE_PROVIDER##' => $service_username,
                                '##REQUESTOR_NAME##' => $request_username,
                                '##DATE##' => $appointment->appointment_from_date
                                );
                                sendMail('Service Request Cancelled', $emailFindReplace, $service_provider->email);
                                if (!empty($appointment->capture_id)) {
                                    $user = Models\User::where('id', $appointment->provider_user_id)->first();
                                    $user->available_wallet_amount = $user->available_wallet_amount + ($appointment->total_booking_amount - $appointment->refunded_amount);
                                    $user->update();
                                }                                    
                                if ($appointment->used_affiliate_amount > 0) {
                                    $customerUser = Models\User::where('id', $appointment->user_id)->first();
                                    $customerUser->affiliate_pending_amount = $customerUser->affiliate_pending_amount + $appointment->used_affiliate_amount;
                                    $customerUser->affiliate_paid_amount = $customerUser->affiliate_paid_amount - $appointment->used_affiliate_amount;
                                    $customerUser->update();
                                }
                                if ($appointment->refunded_amount > 0) {
                                    insertTransaction($appointment->provider_user_id, $appointment->user_id, $appointment->id, 'Appointment', \Constants\TransactionType::BookingCanceledAndRefunded, 1, $appointment->refunded_amount, 0, 0, 0, 0, 0, 1);
                                }
                                if (($appointment->total_booking_amount - $appointment->refunded_amount) > 0) {
                                    insertTransaction($appointment->user_id, $appointment->provider_user_id, $appointment->id, 'Appointment', \Constants\TransactionType::BookingCanceledAndCreditedCancellationAmount, 1, ($appointment->total_booking_amount - $appointment->refunded_amount), 0, 0, 0, 0, 0, 1);
                                }
                        } else {
                            return renderWithJson($result, "Service Provider could not be updated in the status. Please, try again", '', 1);
                        }
                        break;
                    case \Constants\ConstAppointmentStatus::CanceledByAdmin:
                        if ($authUser->role_id != \Constants\ConstUserTypes::Admin) {
                            return renderWithJson($result, "Admin only access this status", '', 1);
                        }
                        $appointment->appointment_status_id = \Constants\ConstAppointmentStatus::CanceledByAdmin;
                        $appointment->update();
                        $msg = "Booking CanceledByAdmin Successfully";
                        break;
                    case \Constants\ConstAppointmentStatus::ReassignedServiceProvider:
                        if ($authUser->role_id != \Constants\ConstUserTypes::Admin) {
                            return renderWithJson($result, "Admin only access this status", '', 1);
                        }
                        $appointment->appointment_status_id = \Constants\ConstAppointmentStatus::ReassignedServiceProvider;
                        $appointment->update();
                        $msg = "Booking ReassignedServiceProvider Successfully";
                        break;
                    case \Constants\ConstAppointmentStatus::Completed:
                        if (($authUser->role_id == \Constants\ConstUserTypes::Admin || $authUser->id == $appointment->provider_user_id) && (( empty($appointment->is_appointment_for_interview) && ($oldAppointmentStatus == \Constants\ConstAppointmentStatus::Approved || $oldAppointmentStatus == \Constants\ConstAppointmentStatus::Present)) || (!empty($appointment->is_appointment_for_interview)) && $oldAppointmentStatus == \Constants\ConstAppointmentStatus::Approved)) {
                            $appointment->appointment_status_id = \Constants\ConstAppointmentStatus::Completed;
                            $appointment->update();
                            $msg = "Booking Completed Successfully";
                            $emailFindReplace = array(
                            '##SERVICE_PROVIDER##' => $service_username,
                            '##REQUESTOR_NAME##' => $request_username,
                            '##LINK##' => $_server_domain_url . '/#/appointments'
                            );
                            sendMail('Service Request Complete', $emailFindReplace, $request_user->email);
                            if (IS_SITE_ENABLED_PUSH_NOTIFICATION) {
                                    $followMessage = array(
                                        'message_type' => 'PUSH_NOTIFICATION_FOR_TASK_MARKED_AS_COMPLETED_BY_SERVICE_PROVIDER',
                                        'appointment_id' => $appointment->id
                                    );
                                    addPushNotification($appointment->user_id, $followMessage);
                            }
                            if (isPluginEnabled('SMS/SMS')) {
                                    $message = array(
                                        'appointment_id' => $appointment->id,
                                        'message_type' => 'SMS_FOR_TASK_MARKED_AS_COMPLETED_BY_SERVICE_PROVIDER'
                                    );
                                    Models\Sms::sendSMS($message, $appointment->user_id);
                            }
                        } else {
                            return renderWithJson($result, "Requestor could not be updated in the status. Please, try again", '', 1);
                        }
                        break;
                    case \Constants\ConstAppointmentStatus::Expired:
                        if ($authUser->role_id != \Constants\ConstUserTypes::Admin) {
                            return renderWithJson($result, "Admin only access this status", '', 1);
                        }
                        $appointment->appointment_status_id = \Constants\ConstAppointmentStatus::Expired;
                        $appointment->update();
                        $msg = "Booking Completed Successfully";
                        break;
                    case \Constants\ConstAppointmentStatus::Present:
                        if (($authUser->role_id == \Constants\ConstUserTypes::Admin || $authUser->id == $appointment->provider_user_id) && $oldAppointmentStatus == \Constants\ConstAppointmentStatus::Approved) {
                            $appointment->appointment_status_id = \Constants\ConstAppointmentStatus::Present;
                            $appointment->update();
                            $msg = "Booking Present Successfully";
                            if (IS_SITE_ENABLED_PUSH_NOTIFICATION) {
                                $followMessage = array(
                                'message_type' => 'PUSH_NOTIFICATION_FOR_TASK_MARKED_AS_INPROGRESS_BY_SERVICE_PROVIDER',
                                'appointment_id' => $appointment->id
                                );
                                addPushNotification($appointment->user_id, $followMessage);
                            }
                            if (isPluginEnabled('SMS/SMS')) {
                                $message = array(
                                'appointment_id' => $appointment->id,
                                'message_type' => 'SMS_FOR_TASK_MARKED_AS_INPROGRESS_BY_SERVICE_PROVIDER'
                                );
                                Models\Sms::sendSMS($message, $appointment->user_id);
                            }
                        } else {
                            return renderWithJson($result, "Requestor could not be updated in the status. Please, try again", '', 1);
                        }
                        break;
                    case \Constants\ConstAppointmentStatus::PaymentPending:
                        if ($authUser->role_id != \Constants\ConstUserTypes::Admin) {
                            return renderWithJson($result, "Admin only access this status", '', 1);
                        }
                        $appointment->appointment_status_id = \Constants\ConstAppointmentStatus::PaymentPending;
                        $appointment->update();
                        $msg = "Booking PaymentPending Successfully";
                        break;
                        
                    default:
                        break;
                }
                $now = strtotime(date('Y-m-d H:i:s'));
                $created = strtotime($appointment->created_at);
                $interval = abs($now - $created);
                $minutes = round($interval / 60);
                if ($authUser->id == $appointment->provider_user_id && $appointment->first_response_time == null && in_array($newAppointmentStatus, [\Constants\ConstAppointmentStatus::Enquiry, \Constants\ConstAppointmentStatus::PreApproved])) {
                    $appointment->first_response_time = $minutes;
                } elseif (!empty(isPluginEnabled('PaymentBooking/PaymentBooking')) && $authUser->id == $appointment->provider_user_id && $appointment->first_response_time == null && $newAppointmentStatus == \Constants\ConstAppointmentStatus::Approved) {
                    $now = strtotime(date('Y-m-d H:i:s'));
                    $created = strtotime($appointment->paid_escrow_amount_at);
                    $interval = abs($now - $created);
                    $escrow_minutes = round($interval / 60);
                    $appointment->first_response_time = $escrow_minutes;
                } elseif ($authUser->id == $appointment->provider_user_id && $appointment->first_response_time == null) {
                    $appointment->first_response_time = $minutes;
                }
                $appointment->save();
                $result['Success'] = $msg;
                return renderWithJson($result);
            } else {
                return renderWithJson($result, 'Booking could not be updated. Please, try again', '', '', 1, 422);
            }
        } else {
            return renderWithJson($result, 'Booking could not be updated. Please, try again', '', '', 2, 422);
        }
    } else {
        return renderWithJson($result, 'Sorry! No record found', '', '', 1, 404);
    }
})->add(new ACL('canUpdateAppointmentStatus'));
$app->PUT('/api/v1/appointments/{appointmentId}/change_service_provider', function ($request, $response, $args) {
    global $authUser;
    $args = $request->getParsedBody();
    $appointment = Models\Appointment::find($request->getAttribute('appointmentId'));
    $result = array();
    if (!empty($appointment) && !empty($args['new_service_provider'])) {
        $newAppointment = $appointment->replicate();
        $newAppointment->provider_user_id = $args['new_service_provider'];
        $newAppointment->save();
        $appointment->appointment_status_id = \Constants\ConstAppointmentStatus::ReassignedServiceProvider;
        $appointment->save();
        $result['data'] = $newAppointment->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'Sorry! No record found', '', '', 1, 404);
    }
})->add(new ACL('canUpdateAppointmentStatus'));
//Services
$app->GET('/api/v1/services', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        $services = Models\Service::Filter($queryParams)->where('services.is_hidden_record', 1)->paginate()->toArray();        
        $data = $services['data'];
        unset($services['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $services
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
});
$app->GET('/api/v1/services/{servicesId}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        $services = Models\Service::Filter($queryParams)->find($request->getAttribute('servicesId'));
        if (!empty($services)) {
            $result['data'] = $services;
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
});
$app->POST('/api/v1/services', function ($request, $response, $args) {
    global $authUser;
    $result = array();
    $args = $request->getParsedBody();
    try {
        $services = new Models\Service($args);
        $validationErrorFields = $services->validate($args);
        if (empty($validationErrorFields)) {
            $services->slug = Inflector::slug(strtolower($args['name']), '-');
            if ($services->save()) {
                if (!empty($args['form_field_groups'])) {
                    foreach ($args['form_field_groups'] as $formFieldGroups) {
                        $formFieldGroup = new Models\FormFieldGroup;
                        $formFieldGroup->name = $formFieldGroups['name'];
                        $formFieldGroup->slug = Inflector::slug(strtolower($formFieldGroups['name']), '-');
                        $formFieldGroup->class = 'Service';
                        $formFieldGroup->foreign_id = $services->id;
                        $formFieldGroup->save();
                        if (!empty($formFieldGroups['form_fields'])) {
                            foreach ($formFieldGroups['form_fields'] as $formFields) {
                                $formField = new Models\FormField($formFields);
                                $formField->class = 'Service';
                                $formField->form_field_group_id = $formFieldGroup->id;
                                $formField->foreign_id = $services->id;
                                $formField->save();
                            }
                        }
                    }
                }
                if (!empty($args['image'])) {
                    saveImage('Service', $args['image'], $services->id);
                }
                $result['data'] = $services->toArray();
            }
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Service could not be added. Please, try again.', '', $validationErrorFields, 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Service could not be added. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canPostService'));
$app->PUT('/api/v1/services/{servicesId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $services = Models\Service::find($request->getAttribute('servicesId'));
    $services->fill($args);
    $result = array();
    try {
        $validationErrorFields = $services->validate($args);
        if (empty($validationErrorFields)) {
            if (!empty($args['name'])) {
                $services->slug = Inflector::slug(strtolower($args['name']), '-');
            }
            if ($services->save()) {
                if (!empty($args['form_field_groups'])) {
                    foreach ($args['form_field_groups'] as $formFieldGroups) {
                        $formFieldGroup = new Models\FormFieldGroup;
                        if (!empty($formFieldGroups['id'])) {
                            $formFieldGroup = Models\FormFieldGroup::where('id', $formFieldGroups['id'])->first();
                        }
                        $formFieldGroup->name = $formFieldGroups['name'];
                        $formFieldGroup->slug = Inflector::slug(strtolower($formFieldGroups['name']), '-');
                        $formFieldGroup->class = 'Service';
                        $formFieldGroup->foreign_id = $services->id;
                        $formFieldGroup->save();
                        if (!empty($formFieldGroups['form_fields'])) {
                            foreach ($formFieldGroups['form_fields'] as $formFields) {
                                $formField = new Models\FormField($formFields);
                                if (!empty($formFields['id'])) {
                                    $formField = Models\FormField::where('id', $formFields['id'])->first();
                                    $formField->fill($formFields);
                                }
                                $formField->class = 'Service';
                                $formField->form_field_group_id = $formFieldGroup->id;
                                $formField->foreign_id = $services->id;
                                $formField->save();
                            }
                        }
                    }
                }
                if (!empty($args['image'])) {
                    saveImage('Service', $args['image'], $services->id);
                }
                $result['data'] = $services->toArray();
            }
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Service could not be updated. Please, try again.', '', $validationErrorFields, 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Service could not be updated. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canUpdateService'));
$app->DELETE('/api/v1/services/{servicesId}', function ($request, $response, $args) {
    $services = Models\Service::find($request->getAttribute('servicesId'));
    $result = array();
    try {
        if (!empty($services)) {
            $services->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Service could not be deleted. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canDeleteService'));
//TODO
/**
 * GET getSearch
 * Summary: For search the doctor&#39;s
 * Notes:
 * Output-Formats: [application/json]
 */
/*$app->GET('/api/v1/search', function ($request, $response, $args) {
    global $authUser;
    $queryParams = $request->getQueryParams();
    if (!empty($queryParams['date'])) {
        $start_ts = strtotime(date('Y-m-d'));
        $end_ts = strtotime($queryParams['date']);
        $diff = $end_ts - $start_ts;
        $tatal_days = round($diff / 86400);
        if ($tatal_days >= 7) {
            $viewSlot = ceil($tatal_days / 7);
        } else {
            $viewSlot = 1;
        }
    } else {
        $viewSlot = 1;
    }
    unset($queryParams['date']);
    $conditions = '';
    $wherRawCondition = '';
    $isWeb = !empty($queryParams['is_web']) ? true : null;
    if (!empty($queryParams['review']) && ($queryParams['review'] == true)) {
        $reivew = true;
    } else {
        $reivew = null;
    }
    foreach ($queryParams as $fields => $value) {
        if ($fields != 'page') {
            if (!empty($value)) {
                if ($fields == 'doctor') {
                    $conditions = [['display_name', 'LIKE', '%' . $value . '%']];
                } elseif ($fields == 'service_id') {
                    $wherRawCondition[] = "FIND_IN_SET($value,$fields)";
                } elseif ($fields == 'review' || $fields == 'latitude' || $fields = 'longitude' || $fields = 'display_type' || $fields = 'distance') {
                } else {
                    $conditions[$fields] = $value;
                }
            }
        }
    }
    $query = new Models\UserProfile();
    if (!empty($queryParams['display_type']) && $queryParams['display_type'] == 'review') {
        $docotorsListPaginate = $query->select(['user_profiles.user_id', 'users.overall_avg_rating'])->join('users', function ($join) {
            $join->on('users.id', '=', 'user_profiles.user_id');
        })->where('users.overall_avg_rating', $queryParams['review'])->where('listing_status_id', \Constants\ConstListingStatus::Approved)->orderBy('users.overall_avg_rating', 'desc')->paginate()->toArray();
    } elseif (!empty($queryParams['display_type']) && $queryParams['display_type'] == 'distance' && !empty($queryParams['latitude']) && !empty($queryParams['longitude'])) {
        $distance = 'ROUND(( 6371 * acos( cos( radians(' . $queryParams['latitude'] . ') ) * cos( radians( user_profiles.latitude ) ) * cos( radians( user_profiles.longitude ) - radians(' . $queryParams['longitude'] . ')) + sin( radians(' . $queryParams['latitude'] . ') ) * sin( radians( user_profiles.latitude ) ) )))';
        $radius = 5;
        $docotorsListPaginate = $query->select(['user_profiles.user_id', 'users.overall_avg_rating'])->join('users', function ($join) {
            $join->on('users.id', '=', 'user_profiles.user_id');
        })->selectRaw($distance . ' AS distance')->whereRaw('(' . $distance . ')<=' . $radius)->where('listing_status_id', \Constants\ConstListingStatus::Approved)->orderBy('users.overall_avg_rating', 'desc')->paginate()->toArray();
    } else {
        $docotorsListPaginate = $query->select(['user_profiles.user_id'])->join('users', function ($join) {
            $join->on('users.id', '=', 'user_profiles.user_id');
        })->where('listing_status_id', \Constants\ConstListingStatus::Approved)->paginate()->toArray();
    }
    $userIds = array_column($docotorsListPaginate['data'], 'user_id');
    if (!empty($queryParams['category_id'])) {
        $category_id = $queryParams['category_id'];
    } else {
        $category_id = null;
    }
    unset($docotorsListPaginate['data']);
    $appointmentDetails = Models\AppointmentSetting::get_doctors_appointment_details($userIds, $viewSlot, 1, $isWeb, $reivew, $category_id);
    if (!empty($appointmentDetails)) {
        $jsonUserIds = base64_encode(implode('-', $userIds));
        if ($authUser && $authUser['role_id']) {
            foreach ($appointmentDetails as $key => $appointmentDetail) {
                $user_id = $appointmentDetail['data']['user_id'];
                $favoriteData = Models\UserFavorite::where('user_id', $user_id)->orWhere('provider_user_id', $user_id)->first();
                $appointmentDetails[$key]['data']['User']['data']['UserFavorite']['data'] = $favoriteData;
            }
        }
        $results['paginate_values'] = $docotorsListPaginate;
        $results['user_profiles'] = $appointmentDetails;
        $results['weekids'] = $jsonUserIds;
        $results['userLoadMore'] = 5;
        $results['viewslot'] = $viewSlot;
        return renderWithJson($results);
    } else {
        $results['paginate_values'] = [];
        $results['user_profiles'] = [];
        $results['weekids'] = [];
        $results['userLoadMore'] = 5;
        $results['viewslot'] = $viewSlot;
        return renderWithJson($results);
    }
});*/
/**
 * GET getSearchGetdoctorweeklistDoctoridViewslot
 * Summary: For Get Doctor Appointment Slot
 * Notes:
 * Output-Formats: [application/json]
 */
/*$app->GET('/api/v1/search/getdoctorweeklist/{doctorid}/{viewslot}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    $isWeb = !empty($queryParams['is_web']) ? true : null;
    if (!empty($queryParams['category_id'])) {
        $category_id = $queryParams['category_id'];
    } else {
        $category_id = null;
    }
    $reivew = true;
    $appointmentDetails = Models\AppointmentSetting::get_doctors_appointment_details([$request->getAttribute('doctorid') ], $request->getAttribute('viewslotid'), 0, $isWeb, $reivew, $category_id);
    $result['user_profiles'] = $appointmentDetails;
    $result['viewslot'] = $request->getAttribute('viewslotid');
    $result['userLoadMore'] = 5;
    return renderWithJson($result);
});*/
/**
 * GET getSearchTimeslot
 * Summary: For search the doctor&#39;s
 * Notes:
 * Output-Formats: [application/json]
 */
/*$app->GET('/api/v1/search/timeslot', function ($request, $response, $args) {
    global $authUser;
    $queryParams = $request->getQueryParams();
    $results = array();
    if (!empty($authUser->id)) {
        $appointmentSettings = Models\AppointmentSetting::where('user_id', '=', $authUser->id)->first();
        $splitedTime = Models\AppointmentSetting::getTimeSlot('00:00', '23:59', $appointmentSettings['calendar_slot_id']);
        array_pop($splitedTime);
        $results['data'] = $splitedTime;
    }
    return renderWithJson($results);
});*/
/**
 * GET getSearchWeeklistUsersidViewslotid
 * Summary: For get the week list details
 * Notes:
 * Output-Formats: [application/json]
 */
/*$app->GET('/api/v1/search/weeklist/{usersid}/{viewslotid}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    $isWeb = !empty($queryParams['is_web']) ? true : null;
    if (!empty($queryParams['category_id'])) {
        $category_id = $queryParams['category_id'];
    } else {
        $category_id = null;
    }
    $reivew = true;
    $userIDS = explode('-', base64_decode($request->getAttribute('usersid')));
    $appointmentDetails = Models\AppointmentSetting::get_doctors_appointment_details($userIDS, $request->getAttribute('viewslotid'), 1, $isWeb, $reivew, $category_id);
    $result['user_profiles'] = $appointmentDetails;
    $result['viewslot'] = $request->getAttribute('viewslotid');
    $result['userLoadMore'] = 5;
    return renderWithJson($result);
});*/
/**
 * GET FormFieldGet
 * Summary: all FormField lists
 * Notes: all FormField lists
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/form_fields', function ($request, $response, $args) {
    global $authUser;
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $FormField = Models\FormField::Filter($queryParams)->paginate()->toArray();
        if (!empty($FormField)) {
            $data = $FormField['data'];
            unset($FormField['data']);
            $result = array(
                'data' => $data,
                '_metadata' => $FormField
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'No record found', $e->getMessage(), '', 1, 422);
    }
});
/**
 * POST QuoteFormField POST
 * Summary:Post QuoteFormField
 * Notes:  Post QuoteFormField
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/form_fields', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $queryParams = $request->getQueryParams();
    $FormFields = new Models\FormField($args);
    $FormFields->class = $queryParams['class'];
    $result = array();
    try {
        $validationErrorFields = $FormFields->validate($args);
        if (empty($validationErrorFields)) {
            $FormFields->save();
            $result['data'] = $FormFields->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Form Field could not be added. Please, try again.', '', $validationErrorFields, 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, ' Form Field could not be added. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canCreateFormField'));
/**
 * DELETE QuoteFormField QuoteFormFieldIdDelete
 * Summary: Delete QuoteFormFielde
 * Notes: Deletes a single  QuoteFormField based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/form_fields/{FormFieldId}', function ($request, $response, $args) {
    $FormFields = Models\FormField::find($request->getAttribute('FormFieldId'));
    $result = array();
    try {
        if (!empty($FormFields)) {
            $FormFields->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Form Field could not be deleted. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canDeleteFormField'));
/**
 * GET QuoteFormField QuoteFormFieldId get
 * Summary: Fetch a QuoteFormField based on QuoteFormField Id
 * Notes: Returns a QuoteFormField from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/form_fields/{FormFieldId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $FormFields = Models\FormField::Filter($queryParams)->find($request->getAttribute('FormFieldId'));
        if (!empty($FormFields)) {
            $result['data'] = $FormFields->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'No record found.', $e->getMessage(), '', 1, 422);
    }
});
/**
 * PUT QuoteFormField QuoteFormFieldIdPut
 * Summary: Update QuoteFormField details
 * Notes: Update QuoteFormField details.
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/form_fields/{FormFieldId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $FormFields = Models\FormField::find($request->getAttribute('FormFieldId'));
    $oldForeignId = $FormFields->foreign_id;
    if (!empty($FormFields)) {
        $validationErrorFields = $FormFields->validate($args);
        if (empty($validationErrorFields)) {
            $FormFields->fill($args);
            try {
                $FormFields->save();
                $result['data'] = $FormFields->toArray();
                return renderWithJson($result);
            } catch (Exception $e) {
                return renderWithJson($result, 'Form Field could not be updated. Please, try again', $e->getMessage(), '', 1, 422);
            }
        } else {
            return renderWithJson($result, 'Form Field could not be updated. Please, try again', '', $validationErrorFields, 1, 422);
        }
    } else {
        return renderWithJson($result, 'No record found', '', '', 1, 404);
    }
})->add(new ACL('canUpdateFormField'));
/**
 * GET formFieldGroupsGet
 * Summary: Fetch all  form fields
 * Notes: Returns all  form fields from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/form_field_groups', function($request, $response, $args) {
	$queryParams = $request->getQueryParams();
	$results = array();
	try {
		$formFieldGroups = Models\FormFieldGroup::Filter($queryParams)->paginate()->toArray();
		$data = $formFieldGroups['data'];
		unset($formFieldGroups['data']);
		$results = array(
			'data' => $data,
			'_metadata' => $formFieldGroups
		);
		return renderWithJson($results);
	} catch(Exception $e) {
		return renderWithJson($results, 'No record found', $e->getMessage(), '', 1, 422);
	}
});
/**
 * POST QuoteFormFieldGroup POST
 * Summary:Post QuoteFormFieldGroup
 * Notes:  Post QuoteFormFieldGroup
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/form_field_groups', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $FormFieldGroups = new Models\FormFieldGroup($args);
    $result = array();
    try {
        $validationErrorFields = $FormFieldGroups->validate($args);
        if (empty($validationErrorFields)) {
            $FormFieldGroups->save();
            $result['data'] = $FormFieldGroups->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Form Field Group could not be added. Please, try again.', '', $validationErrorFields, 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, ' Form Field Group could not be added. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canCreateFormFieldGroup'));
/**
 * GET formFieldGroupsFormFieldGroupIdGet
 * Summary: Fetch  form field
 * Notes: Returns a  form field based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/form_field_groups/{formFieldGroupId}', function($request, $response, $args) {
	$result = array();
    $queryParams = $request->getQueryParams();
    try {
        $formFieldGroup = Models\FormFieldGroup::Filter($queryParams)->find($request->getAttribute('formFieldGroupId'));
        if (!empty($formFieldGroup)) {
            $result['data'] = $formFieldGroup;
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', '', 1, 404);
        }
    } catch(Exception $e) {
		return renderWithJson($result, 'No record found', $e->getMessage(), '', 1, 422);
	}
});
/**
 * PUT FormFieldGroupId FormFieldGroupIdPut
 * Summary: Update FormFieldGroup details
 * Notes: Update FormFieldGroup details.
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/form_field_groups/{FormFieldGroupId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $FormFieldGroups = Models\FormFieldGroup::find($request->getAttribute('FormFieldGroupId'));
    if (!empty($FormFieldGroups)) {
        $validationErrorFields = $FormFieldGroups->validate($args);
        if (empty($validationErrorFields)) {
            $FormFieldGroups->fill($args);
            try {
                $FormFieldGroups->save();
                $result['data'] = $FormFieldGroups->toArray();
                return renderWithJson($result);
            } catch (Exception $e) {
                return renderWithJson($result, 'Form Field Group could not be updated. Please, try again', $e->getMessage(), '', 1, 422);
            }
        } else {
            return renderWithJson($result, 'Form Field Group could not be updated. Please, try again', '', $validationErrorFields, 1, 422);
        }
    } else {
        return renderWithJson($result, 'No record found', '', '', 1, 404);
    }
})->add(new ACL('canUpdateFormFieldGroup'));
/**
 * GET inputTypesGet
 * Summary: Fetch all input types
 * Notes: Returns all input types from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/input_types', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        $inputTypes = Models\InputType::Filter($queryParams)->paginate()->toArray();
        $data = $inputTypes['data'];
        unset($inputTypes['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $inputTypes
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, 'No record found.', $e->getMessage(), '', 1, 422);
    }
});
/**
 * GET gendersGenderIdGet
 * Summary: Fetch gender
 * Notes: Returns a gender based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/genders/{genderId}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        $gender = Models\Gender::Filter($queryParams)->find($request->getAttribute('genderId'));
        if (!empty($gender)) {
            $result['data'] = $gender;
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'No record found.', $e->getMessage(), '', 1, 422);
    }
});
/**
 * GET gendersGet
 * Summary: Fetch all genders
 * Notes: Returns all genders from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/genders', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        $genders = Models\Gender::Filter($queryParams)->paginate()->toArray();
        $data = $genders['data'];
        unset($genders['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $genders
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, 'No record found.', $e->getMessage(), '', 1, 422);
    }
});
/**
 * GET appointmentSettingsAppointmentSettingIdGet
 * Summary: Fetch appointment setting
 * Notes: Returns a appointment setting based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/appointment_settings/{appointmentSettingId}', function ($request, $response, $args) {
    $result = array();
    global $authUser;
    $queryParams = $request->getQueryParams();
    try {
        $appointmentSetting = Models\AppointmentSetting::Filter($queryParams)->find($request->getAttribute('appointmentSettingId'));
        if ($authUser->role_id != \Constants\ConstUserTypes::Admin && $appointmentSetting->user_id != $authUser->id) {
            return renderWithJson($result, 'Authentication failed! Please try again!', '', '', 1, 401);
        }
        if (!empty($appointmentSetting)) {
            $result['data'] = $appointmentSetting;
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'No record found.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canViewAppointmentSetting'));
/**
 * GET appointmentSettingsGet
 * Summary: Fetch all appointment settings
 * Notes: Returns all appointment settings from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/appointment_settings', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        $appointmentSettings = Models\AppointmentSetting::Filter($queryParams)->paginate()->toArray();
        $data = $appointmentSettings['data'];
        unset($appointmentSettings['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $appointmentSettings
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, 'No record found.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canListAppointmentSetting'));
/* PUT appointmentSettingsAppointmentSettingIdGet
 * Summary: Update appointment setting
 * Notes: Returns a appointment setting based on a single ID
 * Output-Formats: [application/json]
*/
$app->PUT('/api/v1/appointment_settings/{appointmentSettingId}', function ($request, $response, $args) {
    global $authUser;
    $args = $request->getParsedBody();
    $appointmentSetting = Models\AppointmentSetting::find($request->getAttribute('appointmentSettingId'));
    $result = array();
    $validationStatus = true;
    try {
        if ($authUser->role_id != \Constants\ConstUserTypes::Admin && $appointmentSetting->user_id != $authUser->id) {
            return renderWithJson($result, 'Authentication failed! Please try again!', '', '', 1, 401);
        }
        $appointmentSetting->fill($args);
        if (!isset($args['type']) || $args['type'] > \Constants\ConstAppointmentSettingType::IndividualDays) {
            $validationStatus = false;
            $type_validation_error['required'] = array(
                'Invalid Type'
            );
            $validationError[] = $type_validation_error;
        } elseif (empty($args['type']) && (empty($args['practice_open']) || empty($args['practice_close']))) {
            $validationStatus = false;
            $type_validation_error['required'] = array(
                'Practice open and practice close is required'
            );
            $validationError[] = $type_validation_error;
        } elseif (!empty($args['type']) && $args['type'] == \Constants\ConstAppointmentSettingType::IndividualDays) {
            $appointmentSettingsValidation = Models\AppointmentSetting::validateAppointmentSettings($args);
            $validationStatus = $appointmentSettingsValidation['validationStatus'];
            $validationError[] = $appointmentSettingsValidation['required'];
        }
        if (!empty($args['appointment_modifications'])) {
            foreach ($args['appointment_modifications'] as $key => $appointment_modification) {
                if (empty($appointment_modification['type']) || (!empty($appointment_modification['type']) && $appointment_modification['type'] != \Constants\ConstAppointmentModificationType::UnavailableInEveryParticularDayAndTime)) {
                    $validationStatus = false;
                    $type_validation_error['required'] = array(
                        'Invalid Appointment modification type'
                    );
                    $validationError[] = $type_validation_error;
                } elseif (empty($appointment_modification['unavailable_from_time']) || empty($appointment_modification['unavailable_to_time'])) {
                    $validationStatus = false;
                    $type_validation_error['required'] = array(
                        'Unavailable from time and unavailable to time is required'
                    );
                    $validationError[] = $type_validation_error;
                }
            }
        }
        $validationErrorFields = $appointmentSetting->validate($args);
        if (empty($validationErrorFields) && $validationStatus == true) {
            $appointmentSetting->save();
            if (isset($args['appointment_modifications'])) {
                Models\AppointmentModification::where('user_id', $appointmentSetting->user_id)->where('type', \Constants\ConstAppointmentModificationType::UnavailableInEveryParticularDayAndTime)->delete();
                foreach ($args['appointment_modifications'] as $appointment_modification) {
                    $appointmentModification = new Models\AppointmentModification;
                    $appointmentModification->type = \Constants\ConstAppointmentModificationType::UnavailableInEveryParticularDayAndTime;
                    $appointmentModification->day = $appointment_modification['day'];
                    $appointmentModification->unavailable_from_time = $appointment_modification['unavailable_from_time'];
                    $appointmentModification->unavailable_to_time = $appointment_modification['unavailable_to_time'];
                    $appointmentModification->user_id = $appointmentSetting->user_id;
                    $appointmentModification->save();
                }
            }
            $appointmentSetting = Models\AppointmentSetting::find($request->getAttribute('appointmentSettingId'));
            $result['data'] = $appointmentSetting->toArray();
            return renderWithJson($result);
        } else {
            if (!empty($validationErrorFields)) {
                $validationError[] = $validationErrorFields->toArray();
            }
            return renderWithJson($result, 'Appointment Setting could not be updated. Please, try again.', $validationError, 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Appointment Setting could not be updated. Please, try again.', $e->getMessage(), 1, 422);
    }
})->add(new ACL('canUpdateAppointmentSetting'));
/**
 * GET withdrawalStatusesGet
 * Summary: Fetch all withdrawal statuses
 * Notes: Returns all withdrawal statuses from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/withdrawal_statuses', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        $withdrawalStatuses = Models\WithdrawalStatus::Filter($queryParams)->paginate()->toArray();
        $data = $withdrawalStatuses['data'];
        unset($withdrawalStatuses['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $withdrawalStatuses
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, 'No record found.', $e->getMessage(), '', 1, 422);
    }
});
/**
 * GET withdrawalStatusesWithdrawalStatusIdGet
 * Summary: Fetch withdrawal status
 * Notes: Returns a withdrawal status based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/withdrawal_statuses/{withdrawalStatusId}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        $withdrawalStatus = Models\WithdrawalStatus::Filter($queryParams)->find($request->getAttribute('withdrawalStatusId'));
        if (!empty($withdrawalStatus)) {
            $result['data'] = $withdrawalStatus;
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'No record found.', $e->getMessage(), '', 1, 422);
    }
});
/**
 * POST orderPost
 * Summary: Creates a new page
 * Notes: Creates a new page
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/order', function ($request, $response, $args) {
    global $authUser, $_server_domain_url;
    $args = $request->getParsedBody();
    $result = array();
    if (!empty($args['class']) && !empty($args['foreign_id'])) {
        $args['user_id'] = isset($args['user_id']) ? $args['user_id'] : $authUser->id;
        switch ($args['class']) {
            case 'Appointment':
                if ((isPluginEnabled('PayPal/PayPal'))) {
                    $result = Models\Appointment::processOrder($args);
                }
                break;
            case 'ProUser':
                if ((isPluginEnabled('ProUser/ProUser'))) {
                    $result = Models\User::processOrder($args);
                }
                break;
            case 'TopUser':
                if ((isPluginEnabled('TopUser/TopUser'))) { 
                    $result = Models\User::processOrder($args);
                }
                break;
        }
        if (!empty($result)) {
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Order could not added. No record found', '', '', 1, 404);
        }
    } else {
        $validationErrorFields['class'] = 'class required';
        $validationErrorFields['foreign_id'] = 'foreign_id required';
        return renderWithJson($result, 'Order could not added', '', $validationErrorFields, 1, 422);
    }
})->add(new ACL('canCreateOrder'));
/**
 * GET meServicesUsersGet
 * Summary: Fetch  users
 * Notes: Returns users from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/me/services_users', function ($request, $response, $args) {
    global $authUser;
    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        
        $servicesUsers = Models\Service::with(array(
            'service_user' => function ($q) use ($authUser) {
                $q->where('user_id', $authUser->id);
            }
            ,'sub_service.service_user' => function ($q) use ($authUser) {
                $q->where('user_id', $authUser->id);
            }, 'category'))->whereHas('category', function ($q) use ($authUser) {
                $q->where('is_active', 1);
            })->where('is_active', 1)->where('category_id', $authUser->category_id)->whereNull('parent_id')->Filter($queryParams)->paginate();
        if (!empty($servicesUsers)) {
            $servicesUsers = $servicesUsers->toArray();
            $data = $servicesUsers['data'];
            unset($servicesUsers['data']);
            $results = array(
                'data' => $data,
                '_metadata' => $servicesUsers
            );
        }
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, 'Service Users Not Found', $e->getMessage(), 1, 422);
    }
})->add(new ACL('canListMeServicesUser'));

/**
 * DELETE faqsFaqIdDelete
 * Summary: Delete faq
 * Notes: Deletes a single faq based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/faqs/{faqId}', function ($request, $response, $args) {
    $faq = Models\Faq::find($request->getAttribute('faqId'));
    $result = array();
    try {
        if (!empty($faq)) {
            $faq->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Faq could not be deleted. Please, try again.', '', 1, 422);
    }
})->add(new ACL('canDeleteFaq'));
/**
 * GET faqsFaqIdGet
 * Summary: Fetch faq
 * Notes: Returns a faq based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/faqs/{faqId}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    $faq = Models\Faq::Filter($queryParams)->find($request->getAttribute('faqId'));
    if (!empty($faq)) {
        $result['data'] = $faq;
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1, 404);
    }
});
/**
 * PUT faqsFaqIdPut
 * Summary: Update faq by its id
 * Notes: Update faq by its id
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/faqs/{faqId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $faq = Models\Faq::find($request->getAttribute('faqId'));
    $faq->fill($args);
    $result = array();
    try {
        $validationErrorFields = $faq->validate($args);
        if (empty($validationErrorFields)) {
            $faq->save();
            $result['data'] = $faq->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Faq could not be updated. Please, try again.', $validationErrorFields, 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Faq could not be updated. Please, try again.', '', 1, 422);
    }
})->add(new ACL('canUpdateFaq'));
/**
 * GET faqsGet
 * Summary: Fetch all faqs
 * Notes: Returns all faqs from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/faqs', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        $faqs = Models\Faq::Filter($queryParams)->paginate()->toArray();
        $data = $faqs['data'];
        unset($faqs['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $faqs
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, $message = $e->getMessage(), $fields = '', $isError = 1, 422);
    }
});
/**
 * POST faqsPost
 * Summary: Creates a new faq
 * Notes: Creates a new faq
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/faqs', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $faq = new Models\Faq($args);
    $result = array();
    try {
        $validationErrorFields = $faq->validate($args);
        if (empty($validationErrorFields)) {
            $faq->save();
            $result['data'] = $faq->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Faq could not be added. Please, try again.', $validationErrorFields, 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Faq could not be added. Please, try again.', '', 1, 422);
    }
})->add(new ACL('canCreateFaq'));
/**
 * DELETE accountCloseReasonsAccountCloseReasonIdDelete
 * Summary: Delete account close reason
 * Notes: Deletes a single account close reason based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/account_close_reasons/{accountCloseReasonId}', function ($request, $response, $args) {
    $accountCloseReason = Models\AccountCloseReason::find($request->getAttribute('accountCloseReasonId'));
    $result = array();
    try {
        if (!empty($accountCloseReason)) {
            $accountCloseReason->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Account close reason not found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Account close reason could not be deleted. Please, try again.', '', '', 1, 422);
    }
})->add(new ACL('canDeleteAccountCloseReason'));
/**
 * GET accountCloseReasonsAccountCloseReasonIdGet
 * Summary: Fetch account close reason
 * Notes: Returns a account close reason based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/account_close_reasons/{accountCloseReasonId}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        $accountCloseReason = Models\AccountCloseReason::Filter($queryParams)->find($request->getAttribute('accountCloseReasonId'))->first();
        if (!empty($accountCloseReason)) {
            $result['data'] = $accountCloseReason;
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Account close reason not found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Account close reason not found. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canViewAccountCloseReason'));
/**
 * PUT accountCloseReasonsAccountCloseReasonIdPut
 * Summary: Update account close reason by its id
 * Notes: Update account close reason by its id
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/account_close_reasons/{accountCloseReasonId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $accountCloseReason = Models\AccountCloseReason::find($request->getAttribute('accountCloseReasonId'));
    try {
        if (!empty($accountCloseReason)) {
            $accountCloseReason->fill($args);
            $validationErrorFields = $accountCloseReason->validate($args);
            if (empty($validationErrorFields)) {
                $accountCloseReason->save();
                $result = $accountCloseReason->toArray();
                return renderWithJson($result);
            } else {
                return renderWithJson($result, 'Account close reason could not be updated. Please, try again.', '', $validationErrorFields, 1, 422);
            }
        } else {
            return renderWithJson($result, 'Account close reason not found. Please, try again.', '', '', 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Account close reason could not be updated. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canUpdateAccountCloseReason'));
/**
 * GET accountCloseReasonsGet
 * Summary: Fetch all account close reasons
 * Notes: Returns all account close reasons from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/account_close_reasons', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        $accountCloseReasons = Models\AccountCloseReason::Filter($queryParams)->paginate()->toArray();
        $data = $accountCloseReasons['data'];
        unset($accountCloseReasons['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $accountCloseReasons
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, 'No record found', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canListAccountCloseReason'));
/**
 * POST accountCloseReasonsPost
 * Summary: Creates a new account close reason
 * Notes: Creates a new account close reason
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/account_close_reasons', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $accountCloseReason = new Models\AccountCloseReason($args);
    $result = array();
    try {
        $validationErrorFields = $accountCloseReason->validate($args);
        if (empty($validationErrorFields)) {
            $accountCloseReason->save();
            $result = $accountCloseReason->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Account close reason could not be added. Please, try again.', '', $validationErrorFields, 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Account close reason could not be added. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canCreateAccountCloseReason'));
/**
 * DELETE apnsDevicesApnsDeviceIdDelete
 * Summary: Delete apns device
 * Notes: Deletes a single apns device based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/apns_devices/{apnsDeviceId}', function ($request, $response, $args) {
    $result = array();
    $apnsDevice = Models\ApnsDevice::find($request->getAttribute('apnsDeviceId'));
    if (!empty($apnsDevice)) {
        try {
            $apnsDevice->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'Apns device could not be deleted. Please, try again.', '', 1);
        }
    } else {
        return renderWithJson($result, 'Apns device could not be deleted. Please, try again.', '', 1);
    }
})->add(new ACL('canDeleteApnsDevice'));
/**
 * GET apnsDevicesApnsDeviceIdGet
 * Summary: Fetch apns device
 * Notes: Returns a apns device based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/apns_devices/{apnsDeviceId}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        $apnsDevice = Models\ApnsDevice::Filter($queryParams)->find($request->getAttribute('apnsDeviceId'));
        if (!empty($apnsDevice)) {
            $result['data'] = $apnsDevice->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Apns device not found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($results, 'Apns device not found', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canViewApnsDevice'));
/**
 * GET apnsDevicesGet
 * Summary: Fetch all apns devices
 * Notes: Returns all apns devices from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/apns_devices', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        $apnsDevices = Models\ApnsDevice::Filter($queryParams)->paginate()->toArray();
        $data = $apnsDevices['data'];
        unset($apnsDevices['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $apnsDevices
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canListApnsDevice'));
/**
 * POST apnsDevicesPost
 * Summary: Creates a new apns device
 * Notes: Creates a new apns device
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/apns_devices', function ($request, $response, $args) {
    global $authUser;
    $result = array();
    $args = $request->getParsedBody();
    if (!empty($args['devicetoken'])) {
        Models\ApnsDevice::where('devicetoken', $args['devicetoken'])->where('user_id', '!=', $authUser->id)->delete();
    }
    $apns_device = Models\ApnsDevice::where('user_id', $authUser->id)->first();
    $apnsDevice = new Models\ApnsDevice($args);
    if (!empty($apns_device)) {
        $apnsDevice = Models\ApnsDevice::find($apns_device['id']);
        $apnsDevice->fill($args);
    }
    $apnsDevice->user_id = $authUser->id;
    try {
        $validationErrorFields = $apnsDevice->validate($args);
        if (empty($validationErrorFields)) {
            $apnsDevice->save();
            $user = Models\User::find($authUser->id);
            $user->longitude = $apnsDevice->longitude;
            $user->latitude = $apnsDevice->latitude;
            $user->apns_device_last_update_time = date("Y-m-d h:i:s");
            $user->is_app_device_available = 1;
            $user->save();
            $apnsDevice = Models\ApnsDevice::with('user')->where('id', $apnsDevice->id)->first();
            $result['data'] = $apnsDevice->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Apns device could not be added. Please, try again.', $validationErrorFields, 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Apns device could not be added. Please, try again.', $e->getMessage(), 1);
    }
})->add(new ACL('canCreateApnsDevice'));
/**
 * GET StatsGet
 * Summary: Get site stats lists
 * Notes: Get site stats lists
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/stats', function ($request, $response, $args) {
    global $authUser;
    $result = array();
    $result['total_service_provider_user_count'] = Models\User::where('is_active', 1)->where('is_deleted', 0)->whereIn('role_id', [\Constants\ConstUserTypes::ServiceProvider, \Constants\ConstUserTypes::User])->count();
    $result['total_customer_user_count'] = Models\User::where('is_active', 1)->where('is_deleted', 0)->where('role_id', \Constants\ConstUserTypes::Customer)->count();
    $result['total_appointment_count'] = Models\Appointment::where('appointment_status_id', '!=', \Constants\ConstAppointmentStatus::PaymentPending)->count();
    if ((isPluginEnabled('PaymentBooking/PaymentBooking'))){
        $transaction_type = array (
            \Constants\TransactionType::BookingAcceptedAndAmountMovedToEscrow,
            \Constants\TransactionType::PROPayment,
            \Constants\TransactionType::TopListed,
            \Constants\TransactionType::BonusAmount
        );        
        $result['total_transaction_amount'] = Models\Transaction::whereIn('transaction_type', $transaction_type)->sum('amount');
        $result['site_revenue_from_freelancer'] = Models\UserProfile::sum('site_revenue_as_service_provider');
        $result['site_revenue_from_employer'] = Models\UserProfile::sum('site_revenue_as_customer');
        $result['total_site_revenue'] = $result['site_revenue_from_freelancer'] + $result['site_revenue_from_employer'];
    }
    return renderWithJson($result);
})->add(new ACL('canViewStats'));
/**
 * GET formFieldSubmissionsGet
 * Summary: Fetch all  form field submission
 * Notes: Returns all  form field submission from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/form_field_submissions', function($request, $response, $args) {
	$queryParams = $request->getQueryParams();
	$results = array();
	try {
		$formFieldSubmissions = Models\FormFieldSubmission::Filter($queryParams)->paginate()->toArray();
		$data = $formFieldSubmissions['data'];
		unset($formFieldSubmissions['data']);
		$results = array(
			'data' => $data,
			'_metadata' => $formFieldSubmissions
		);
		return renderWithJson($results);
	} catch(Exception $e) {
		return renderWithJson($results, 'No record found', $e->getMessage(), '', 1, 422);
	}
});

$app->GET('/api/v1/plugins', function ($request, $response, $args) {
    global $authUser;
    $result = array();
    $path = APP_PATH . DIRECTORY_SEPARATOR . 'client' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'plugins';
    if (!is_dir($path)) {
        $path = APP_PATH . DIRECTORY_SEPARATOR . 'client' . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'plugins';
    }
    $directories = array();
    $directories = glob($path . '/*', GLOB_ONLYDIR);
    $pluginArray = array();
    $plugin_name = array();
    $otherlugins = array();
    $hide_plugins = array('Message');
    foreach ($directories as $key => $val) {
        $name = explode('/', $val);
        $sub_directories = glob($val . '/*', GLOB_ONLYDIR);
        if (!empty($sub_directories)) {
            foreach ($sub_directories as $sub_directory) {
                $json = file_get_contents($sub_directory . DIRECTORY_SEPARATOR . 'plugin.json');
                $data = json_decode($json, true);
                if (!in_array($data['name'], $hide_plugins)) {
                    if (!empty($data['dependencies'])) {
                        $pluginArray[$data['dependencies']][$data['name']] = $data;
                    } elseif (!in_array($data['name'], $pluginArray)) {
                        if (empty($pluginArray[$data['name']])) {
                            $pluginArray[] = $data;
                        }
                    }
                }
            }
        }
    }
    foreach ($pluginArray as $plugin) {
        $otherlugins[] = $plugin;
    }
    $enabled_plugins = explode(',', SITE_ENABLED_PLUGINS);
    foreach ($enabled_plugins as $key => $enabled_plugin) {
        $name = explode('/', $enabled_plugin);
        $plugin_name[] = end($name);
    }
    $enabled_plugin = array_map('trim', $plugin_name);
    $result['data']['other_plugin'] = $otherlugins;
    $result['data']['enabled_plugin'] = $enabled_plugin;
    return renderWithJson($result);
})->add(new ACL('canListPlugin'));;
/**
 * PUT pluginPut
 * Summary: Update plugins ny plugin name
 * Notes: Update plugins ny plugin name
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/plugins', function ($request, $response, $args) {
    global $authUser;
    $args = $request->getParsedBody();
    $result = array();
    $enabled_plugins = explode(',', trim(SITE_ENABLED_PLUGINS,','));
    if ($args['is_enabled'] === 1) {
        if (!in_array($args['plugin'], $enabled_plugins)) {
            $enabled_plugins[] = $args['plugin'];
        }
        $pluginStr = trim(implode(',', $enabled_plugins),',');
        Models\Setting::where('name', 'SITE_ENABLED_PLUGINS')->update(array(
            'value' => $pluginStr
        ));
        return renderWithJson($result, 'Plugin enabled', '', 0);
    } elseif ($args['is_enabled'] === 0) {
        $key = array_search($args['plugin'], $enabled_plugins);
        if ($key !== false) {
            unset($enabled_plugins[$key]);
        }
        $pluginStr = trim(implode(',', $enabled_plugins),',');
        Models\Setting::where('name', 'SITE_ENABLED_PLUGINS')->update(array(
            'value' => $pluginStr
        ));
        $scripts_path = APP_PATH . DIRECTORY_SEPARATOR . 'client' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'scripts';
        if (!is_dir($scripts_path)) {
            $scripts_path = APP_PATH . DIRECTORY_SEPARATOR . 'client' . DIRECTORY_SEPARATOR . 'scripts';
        }
        $list = glob($scripts_path . '/plugins*.js');
        if ($list) {
            unlink($list[0]);
        }
        return renderWithJson($result, 'Plugin disabled', '', 0);
    } else {
        return renderWithJson($result, 'Invalid request.', '', 1);
    }
})->add(new ACL('canUpdatePlugin'));
/**
 * GET topListingPaymentLogsGet
 * Summary: Fetch all top listing payment logs
 * Notes: Returns all top listing payment logs from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/top_listing_payment_logs', function($request, $response, $args) {
	$queryParams = $request->getQueryParams();
	$results = array();
	try {
		$count = PAGE_LIMIT;
        if (!empty($queryParams['limit'])) {
            $count = $queryParams['limit'];
        }
		$topListingPaymentLogs = Models\TopListingPaymentLog::Filter($queryParams)->paginate($count)->toArray();
		$data = $topListingPaymentLogs['data'];
		unset($topListingPaymentLogs['data']);
		$results = array(
			'data' => $data,
			'_metadata' => $topListingPaymentLogs
		);
		return renderWithJson($results);
	} catch(Exception $e) {
		return renderWithJson($results, $message = 'No record found', $fields = '', $isError = 1);
	}
})->add(new ACL('canListTopListingPaymentLog'));


/**
 * DELETE topListingPaymentLogsTopListingPaymentLogIdDelete
 * Summary: Delete top listing payment log
 * Notes: Deletes a single top listing payment log based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/top_listing_payment_logs/{topListingPaymentLogId}', function($request, $response, $args) {
	$topListingPaymentLog = Models\TopListingPaymentLog::find($request->getAttribute('topListingPaymentLogId'));
	$result = array();
	try {
		if (!empty($topListingPaymentLog)) {
			$topListingPaymentLog->delete();
			$result = array(
				'status' => 'success',
			);
			return renderWithJson($result);
		} else {
			return renderWithJson($result, 'No record found', '', 1);
		}
	} catch(Exception $e) {
		return renderWithJson($result, 'Top listing payment log could not be deleted. Please, try again.', '', 1);
	}
})->add(new ACL('canDeleteTopListingPaymentLog'));


/**
 * GET topListingPaymentLogsTopListingPaymentLogIdGet
 * Summary: Fetch top listing payment log
 * Notes: Returns a top listing payment log based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/top_listing_payment_logs/{topListingPaymentLogId}', function($request, $response, $args) {
	$result = array();
    $topListingPaymentLog = Models\TopListingPaymentLog::find($request->getAttribute('topListingPaymentLogId'));
    if (!empty($topListingPaymentLog)) {
        $result['data'] = $topListingPaymentLog;
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new ACL('canViewTopListingPaymentLog'));

$app->run();
