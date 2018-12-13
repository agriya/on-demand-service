<?php
require 'instagram/Instagram.php';
use MetzWeb\Instagram\Instagram as Instagram;
/**
 * To fetch access token, user Google profile details, fetch user's contacts and user's interests
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    ace
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2014 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
/**
 * Google Provider API Class
 *
 * @category   PHP
 * @package    ace
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2014 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
class Providers_instagram
{
    /**
     * To get access token from Google
     *
     * @param $pass_value
     * @return string
     */
    function getAccessToken($pass_value)
    {
            global $_server_domain_url;
            $instagram = new Instagram(array(
                'apiKey'      => $pass_value['api_key'],
                'apiSecret'   => $pass_value['secret_key'],
                'apiCallback' => $_server_domain_url.'/api/v1/users/social_login?type=instagram'
            ));
            if(isset($pass_value['code'])){
                $pass_value['access_token'] = $instagram->getOAuthToken($pass_value['code']);
            }
            $instagram->setAccessToken($pass_value['access_token']);
            return $instagram;
    }
    function getUserProfile(Instagram $instagram, $provider_details = ''){
        global $authUser;
        try{
            if(Models\ProviderUser::where('user_id','=',$authUser['id'])->where('provider_id','=',$provider_details['id'])->update(['is_connected' => true])){
                $response['error']['code'] = 0;
                $response['message'] = 'Portfolio could be update with instagram media sucessfully.';
                return $response;
            }
            $provider_users_data = new Models\ProviderUser;
            $user_detail=$instagram->getUser();
            $provider_users_data->user_id =$authUser['id'];
            $provider_users_data->provider_id = $provider_details['id'];
            $provider_users_data->foreign_id = $user_detail->data->id;
            $provider_users_data->access_token = $instagram->getAccessToken();
            $provider_users_data->access_token_secret = '';
            $provider_users_data->is_connected = true;
            $provider_users_data->profile_picture_url = $user_detail->data->profile_picture;
            $provider_users_data->save();
            $response['error']['code'] = 0;
            $response['data'] = $provider_users_data;
            $response['message'] = 'Portfolio could be connect with instagram media sucessfully.';
            return $response;
        }catch(Exception $e){
            $response['error']['code'] = 1;
            $response['message'] = 'Portfolio could not be connect with instagram. Please, try again.';
            return $response;
        }
        
    }
}