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
require_once __DIR__.'/../../../../lib/bootstrap.php';
global $_server_domain_url;
$user_profiles = Models\UserProfile::with('user.service_users.service')->where('is_sent_search_notification', 0)->where('listing_status_id', \Constants\ConstListingStatus::Approved)->get()->toArray();
if(!empty($user_profiles)){    
    foreach($user_profiles as $user_profile){
        if(!empty($user_profile['user']['service_users'])){
            $service_id = array();
            foreach($user_profile['user']['service_users'] as $service_user){
                $service_id[] = $service_user['service_id'];
            }
            $radius = 50;
            $distance = 'ROUND(( 6371 * acos( cos( radians(' . $user_profile['listing_latitude'] . ') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(' . $user_profile['listing_longitude'] . ')) + sin( radians(' . $user_profile['listing_latitude'] . ') ) * sin( radians( latitude ) ) )))';
            $user_searches = Models\UserSearch::with('service.service_user')->select('*')->selectRaw($distance . ' AS distance')->whereRaw('(' . $distance . ')<=' . $radius)->whereIn('service_id', $service_id);
            if (isPluginEnabled('BlockedUser/BlockedUser')) {
                $user_searches = $user_searches->doesntHave('blocker', 'and', function($q) use ($user_profile){
                            $q->where('blocked_user_id', $user_profile['user_id']);
                        })->doesntHave('blocking', 'and', function($q) use ($user_profile){
                            $q->where('user_id', $user_profile['user_id']);
                        });                
            }
            $user_searches = $user_searches->get()->toArray();
            if(!empty($user_searches)){ 
               
                foreach($user_searches as $user_search){
                    //@todo // $user_search['service_user'] has many values
                    $user_search_notification_log = Models\UserSearchNotificationLog::where('user_id', $user_search['user_id'])->where('service_provider_id', $user_profile['user_id'])->where('user_search_id', $user_search['id'])->first();
                    if(empty($user_search_notification_log)){
                        $save_user_search_notification_log = new Models\UserSearchNotificationLog;
                        $save_user_search_notification_log->user_id = $user_search['user_id'];
                        $save_user_search_notification_log->service_provider_id = $user_profile['user_id'];
                        $save_user_search_notification_log->user_search_id = $user_search['id'];
                        $save_user_search_notification_log->save();
                        $user = Models\User::find($user_search['user_id']);
                        $emailFindReplace = array(
                            '##USERNAME##' => $user['username'],
                            '##SERVICE_NAME##' => $user_search['service']['name'],
                            '##CALENDAR_URL##' => $_server_domain_url.'/users/'.$user_profile['user_id'].'/'.$user_profile['first_name'].'+'.$user_profile['last_name']
                        );
                        sendMail('New listing published', $emailFindReplace, $user['email']);

                        if (IS_SITE_ENABLED_PUSH_NOTIFICATION) {
                                $followMessage = array(
                                    'message_type' => 'PUSH_NOTIFICATION_FOR_NEW_INTEREST_RECEIVED',
                                    'user_search_id' => $user_search['id'],
                                    'service_provider_id' => $user_profile['user_id']
                                );
                                addPushNotification($user->id, $followMessage);
                        }
                        if (isPluginEnabled('SMS/SMS')) {
                            $message = array(
                                'user_search_id' => $user_search['id'],
                                'message_type' => 'SMS_FOR_NEW_INTEREST_RECEIVED',
                                'service_provider_id' => $user_profile['user_id']
                            );
                            Models\Sms::sendSMS($message, $user->id);
                        }
                    }
                }
                Models\UserProfile::where('user_id', $user_profile['user_id'])->update(['is_sent_search_notification' => 1]);
            }
        }
    }
}

