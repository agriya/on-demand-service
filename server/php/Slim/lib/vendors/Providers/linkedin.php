<?php
include_once (dirname(dirname(dirname(dirname(dirname(__FILE__))))) . DIRECTORY_SEPARATOR . 'config.inc.php');
class Providers_linkedin
{
    function getAccessToken($pass_value)
    {
        $params = array(
            'grant_type'=>'authorization_code',
            'code' => $pass_value['code'],
            'redirect_uri' => $pass_value['redirectUri'],
            'client_id' => $pass_value['clientId'],
            'client_secret' => $pass_value['secret_key']
        );
        $accessTokenUrl = 'https://www.linkedin.com/oauth/v2/accessToken';
        $_response = _doPost($accessTokenUrl, $params);
        $access_token = $_response['access_token'];
        return $access_token;
    }
    function getUserProfile($access_token, $provider_details = '')
    {
        // request user profile from fb api
        try {
            $url = 'https://api.linkedin.com/v1/people/~:(id,maiden-name,first-name,last-name,num-connections,picture-url,email-address)?format=json';
            $data = _doGet($url,array('auth'=>$access_token));
        }
        catch(FacebookApiException $e) {
            throw new Exception("User profile request failed! Linkedin returned an error: $e", 6);
        }
        // if the provider identifier is not recived, we assume the auth has failed
        if (!isset($data["id"])) {
            throw new Exception("User profile request failed! Linkedin api returned an invalid response.", 6);
        }
        // store the user profile.
        $user = (object)array();
        $user->access_token = $user->access_token_secret = '';
        $user->access_token = $access_token;
        $user->email = (array_key_exists('emailAddress', $data)) ? $data['emailAddress'] : "";
        $username=explode('@',$data['emailAddress']);
        $user->identifier = (array_key_exists('id', $data)) ? $data['id'] : "";
        $user->displayName = $username[0];
        $user->firstName = (array_key_exists('firstName', $data)) ? $data['firstName'] : "";
        $user->lastName = (array_key_exists('last_name', $data)) ? $data['last_name'] : "";
        $user->profileURL = (array_key_exists('pictureUrl', $data)) ? $data['pictureUrl'] : "";
        $user->webSiteURL = (array_key_exists('website', $data)) ? $data['website'] : "";
        $user->gender = (array_key_exists('gender', $data)) ? $data['gender'] : "M";
        $user->description = (array_key_exists('bio', $data)) ? $data['bio'] : "";
        $user->emailVerified = (array_key_exists('emailAddress', $data)) ? $data['emailAddress'] : "";
        $user->region = (array_key_exists("hometown", $data) && array_key_exists("name", $data['hometown'])) ? $data['hometown']["name"] : "";
        if (array_key_exists('birthday', $data)) {
            list($birthday_month, $birthday_day, $birthday_year) = explode("/", $data['birthday']);
            $user->birthDay = (int)$birthday_day;
            $user->birthMonth = (int)$birthday_month;
            $user->birthYear = (int)$birthday_year;
        }
        return $user;
    }
    function getUserContacts($access_token, $provider_details = '')
    {
        try {
            $graphApiUrl = 'http://api.linkedin.com/v1/people/~:(id,maiden-name,first-name,last-name,num-connections,picture-url,email-address)/connections?modified=new';
            $_response = _doGet($graphApiUrlurl,array('auth'=>$access_token));
        }
        catch(FacebookApiException $e) {
            throw new Exception("User contacts request failed! {$this->providerId} returned an error: $e");
        }
        if (!$_response || !count($_response["data"])) {
            return array();
        }
        $contacts = array();
        foreach ($_response["data"] as $item) {
            $uc = (object)array();
            $uc->identifier = (array_key_exists("id", $item)) ? $item["id"] : "";
            $uc->displayName = (array_key_exists("name", $item)) ? $item["name"] : "";
            $uc->profileURL = "https://www.linkedin.com/profile.php?id=" . $uc->identifier;
            $uc->photoURL = "https://graph.linkedin.com/" . $uc->identifier . "/picture?type=normal";
            $contacts[] = $uc;
        }
        return $contacts;
    }
}   