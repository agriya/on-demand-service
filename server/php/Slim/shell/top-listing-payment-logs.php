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
require_once '../lib/bootstrap.php';
use Carbon\Carbon;
$datetime = Carbon::parse(Carbon::now())->format('Y-m-d H:i:s');
$user_profiles = Models\UserProfile::where('top_user_expiry', '<', $datetime)->where('is_top_listed', 1)->get();
if(!empty($user_profiles)){
    foreach($user_profiles as $user_profile){
        Models\TopListingPaymentLog::where('user_id', $user_profile->user_id)->where('is_active', 1)->update(['is_active' => 0]);
        Models\User::updateDisplayOrder($user_profile->user_id);
        Models\UserProfile::where('user_id', $user_profile->user_id)->update(['is_top_listed' => 0]);
    }
}

