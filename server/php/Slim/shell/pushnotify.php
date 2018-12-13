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
require_once __DIR__ . '/../lib/bootstrap.php';

sendPushNotification();
function sendPushNotification(){
     $push_notifications = Models\PushNotification::with('apns_device')->get();
     if(!empty($push_notifications)){
        $push_notifications =  $push_notifications->toArray();
        foreach($push_notifications as $push_notification){
            $device_type = $push_notification['apns_device']['devicetype'];
            $device_token = $push_notification['apns_device']['devicetoken'];
            $message = $push_notification['message'];
            try{
                Models\PushNotification::sendPushMessage($device_type,$device_token,$message);
                Models\PushNotification::where('id',$push_notification['id'])->delete();
            } catch (Exception $e) {
                
            }
        }
     }
}
