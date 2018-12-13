<?php
/**
 * Mailchimp configurations
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    On demand service marketplace/
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
 function getMailChimpUrl($endpoint) {
    $api_key = MAILCHIMP_API_KEY;
    $url = 'https://' . substr($api_key,strpos($api_key,'-')+1) . '.api.mailchimp.com/3.0/'. $endpoint .'/';
    return $url;
 }
 function mailchimpCurlConnect( $url, $request_type, $data = array() ) {
    $api_key = MAILCHIMP_API_KEY;
    if( $request_type == 'GET' )
        $url .= '?' . http_build_query($data);

    $mch = curl_init();
    $headers = array(
        'Content-Type: application/json',
        'Authorization: Basic '.base64_encode( 'user:'. $api_key )
    );
    curl_setopt($mch, CURLOPT_URL, $url );
    curl_setopt($mch, CURLOPT_HTTPHEADER, $headers);
    //curl_setopt($mch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
    curl_setopt($mch, CURLOPT_RETURNTRANSFER, true); // do not echo the result, write it into variable
    curl_setopt($mch, CURLOPT_CUSTOMREQUEST, $request_type); // according to MailChimp API: POST/GET/PATCH/PUT/DELETE
    curl_setopt($mch, CURLOPT_TIMEOUT, 10);
    curl_setopt($mch, CURLOPT_SSL_VERIFYPEER, false); // certificate verification for TLS/SSL connection

    if( $request_type != 'GET' ) {
        curl_setopt($mch, CURLOPT_POST, true);
        curl_setopt($mch, CURLOPT_POSTFIELDS, json_encode($data) ); // send data in json
    }    
    return curl_exec($mch);
 } 
?>
