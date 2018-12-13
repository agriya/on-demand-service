<?php
/**
 * Sample cron file
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
 
require_once  __DIR__ . '/../../../../lib/bootstrap.php'; 
global $_server_domain_url;
if (!empty(isPluginEnabled('Review/Review'))) {
    $appointments = Models\Appointment::with('provider_user.user_profile', 'service')->whereIn('appointment_status_id', [\Constants\ConstAppointmentStatus::Closed, \Constants\ConstAppointmentStatus::Completed])->where('is_review_posted', 0)->where('review_reminder_notification_sent_count', '<', 2)->get();
    if (!empty($appointments)) {        
        foreach ($appointments as $appointment) {
            Models\Appointment::where('id', $appointment->id)->update(array('review_reminder_notification_sent_count' => $appointment->review_reminder_notification_sent_count + 1));        
            $emailFindReplace = array(
            '##FIRSTNAME##' => $appointment->provider_user->user_profile->first_name,
            '##LASTNAME##' => $appointment->provider_user->user_profile->last_name,        
            '##SERVICENAME##' => $appointment->service->name,
            '##SERVICEURL##' => $_server_domain_url . '/booking/'.$appointment->id
            );
            sendMail('Review - Remainder Notification', $emailFindReplace, $appointment->provider_user->email);
            if (IS_SITE_ENABLED_PUSH_NOTIFICATION) {
                    $followMessage = array(
                        'message_type' => 'PUSH_NOTIFICATION_FOR_REMAINDER_NOTIFICATION_TO_PROVIDER',
                        'appointment_id' => $appointment->id
                    );
                    addPushNotification($appointment->provider_user_id, $followMessage);                    
            }
            if (isPluginEnabled('SMS/SMS')) {
                $message = array(
                    'appointment_id' => $appointment->id,
                    'message_type' => 'SMS_FOR_FOR_REMAINDER_NOTIFICATION_TO_PROVIDER'
                );
                Models\Sms::sendSMS($message, $appointment->provider_user_id);                
            }
        }
    }
}

