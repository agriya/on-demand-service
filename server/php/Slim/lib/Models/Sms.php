<?php
/**
 * Skill
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    Hirecoworker
 * @subpackage Model
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Models;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;
use Exception;
/*
 * Skill
*/
class Sms extends AppModel
{
    protected $table = false;
    public function sendSMS($message = array(), $to_user_id, $is_otp = 0)
    {
        global  $_server_domain_url;
        $to_user = User::find($to_user_id);
        $notificationMessages = "";
        if(!empty($to_user) && (!empty($to_user->is_mobile_number_verified) || !empty($is_otp)))
        {
            $mobile = $to_user->mobile_code . $to_user->phone_number;
            $notificationMessage = Setting::where('name', $message['message_type'])->first();
            $default_content = array(
                '##SITE_NAME##' => SITE_NAME
            );
            if (isset($message['appointment_id'])) {
                $appointment = Appointment::with('user.user_profile', 'provider_user', 'service')->find($message['appointment_id']);
                if (!empty($appointment)) {
                    if (!empty($appointment['user'])) {
                        if (!empty($appointment['user']['user_profile']['first_name']) || !empty($appointment['user']['user_profile']['last_name'])) {
                            $username = $appointment['user']['user_profile']['first_name'].' '.$appointment['user']['user_profile']['last_name'];
                        } else {
                            $username = $appointment['user']['email']; 
                        }                        
                        $default_content['##REQUESTOR_NAME##'] = $username;
                    }
                    if (!empty($appointment['user']['user_profile'])) {
                        $default_content['##REQUESTOR_FIRST_NAME##'] = $appointment['user']['user_profile']['first_name'];
                        $default_content['##REQUESTOR_LAST_NAME##'] = $appointment['user']['user_profile']['last_name'];
                    }
                    if (!empty($appointment['provider_user']['user_profile'])) {
                        $default_content['##SERVICE_PROVIDER_FIRST_NAME##'] = $appointment['provider_user']['user_profile']['first_name'];
                        $default_content['##SERVICE_PROVIDER_LAST_NAME##'] = $appointment['provider_user']['user_profile']['last_name'];
                    }
                    if (!empty($appointment['service'])) {
                        $default_content['##SERVICE_NAME##'] = $appointment['service']['name'];
                    }
                    $default_content['##APPOINTMENT_DATE##'] = $appointment->appointment_from_date;
                }
            }
            if(isset($message['request_user_id'])){
                $request_user = RequestsUser::find($message['request_user_id']);
                if(!empty($request_user)){
                    $service_provider = User::with('user_profile')->find($request_user['user_id']);
                    $request = Request::with('service','user')->find($request_user['request_id']);
                    $default_content['##USERNAME##'] = $request['user']['username'];
                    $default_content['##SERVICE_PROVIDER_FIRSTNAME##'] = $service_provider['user_profile']['first_name'];
                    $default_content['##SERVICE_PROVIDER_LASTNAME##'] = $service_provider['user_profile']['last_name'];
                    $default_content['##SERVICENAME##'] = $request['service']['name'];
                    $default_content['##CALENDAR_URL##'] = $_server_domain_url.'/users/'.$service_provider['user_id'].'/'.$service_provider['user_profile']['first_name'].'+'.$service_provider['user_profile']['last_name'];
                }
            } 
            if(isset($message['user_search_id'])){
                $user_searches = UserSearch::find($message['user_search_id']);
                if(!empty($user_searches)){
                    $service_provider = User::with('user_profile')->find($message['service_provider_id']);
                    $service = Service::find($user_searches['service_id']);
                    $user = User::with('user_profile')->find($user_searches['user_id']);
                    $default_content['##SERVICE_PROVIDER_FIRSTNAME##'] = $service_provider['user_profile']['first_name'];
                    $default_content['##SERVICE_PROVIDER_LASTNAME##'] = $service_provider['user_profile']['last_name'];
                    $default_content['##SERVICENAME##'] = $service['name'];
                    $default_content['##CALENDAR_URL##'] = $_server_domain_url.'/users/'.$message['service_provider_id'].'/'.$service_provider['user_profile']['first_name'].'+'.$service_provider['user_profile']['last_name'];
                }
            }                      
            if (isset($message['user_id'])) {
                $user = User::find($message['user_id']);
                if (!empty($user)) {
                    $default_content['##OTP##'] = $user->mobile_number_verification_otp;
                }
            }
            if (!empty($notificationMessage)) {
                $notificationMessages = strtr($notificationMessage->value, $default_content);
            }
            if (!empty($mobile) && !empty($notificationMessages)) {
                //  send SMS
                //  @TODO
                try {
                    $Twilio = new Client(SMS_ACCOUNT_SID, SMS_GATEWAY_TOKEN);       
                    $message = $Twilio->account->messages->create($mobile, array (
                            'from' => SMS_FROM_NUMBER,
                            'body' => $notificationMessages
                        ));
                    // Log::info($message);
                } catch (TwilioException $e) {
                    return false;
                    // Log::info($exp->getMessage());
                } catch (Exception $e) {
                    return false;
                    // Log::info($exp->getMessage());
                }
            }
        }
        return true;
    }
}
