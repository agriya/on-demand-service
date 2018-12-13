<?php
/**
 * PushNotification
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    Agriya Base Core Package
 * @subpackage Model
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Models;

/*
 * PushNotification
*/
class PushNotification extends AppModel
{
    protected $table = 'push_notifications';
    public $rules = array(
        'message_type' => 'sometimes|required',
        'message' => 'sometimes|required'
    );
    public function apns_device()
    {
        return $this->belongsTo('Models\ApnsDevice', 'user_device_id', 'id');
    }
    public function sendSinglePushNotification($id)
    {
        $push_notification = PushNotification::with('apns_device')->where('id', $id)->first();
        if (!empty($push_notification)) {
            $device_type = $push_notification['apns_device']['devicetype'];
            $device_token = $push_notification['apns_device']['devicetoken'];
            $message = $push_notification['message'];
            try {
                PushNotification::sendPushMessage($device_type, $device_token, $message);
                PushNotification::where('id', $push_notification['id'])->delete();
            } catch (Exception $e) {
            }
        }
    }
    public function sendPushMessage($device_type, $device_token, $message) {
        if (!empty($device_token)) {
            if($device_type == Constants\ConstDeviceType::Android) {
                $site_name = SITE_NAME;
                $fields = '{
                    "to" : "'.$device_token.'",
                    "from":"",
                    "priority" : "normal",
                    "notification" : {
                    "body" : "'.$message.'",
                    "title" : "'.$site_name .'",
                    "icon" : "",
                    },
                }';
                $pushApiKey = ANDROID_API_ACCESS_KEY;              
                $headers = [
                    'Authorization: key=' . $pushApiKey,
                    'Content-Type: application/json'
                ];
                $ch = curl_init();
                curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                curl_setopt( $ch,CURLOPT_POST, true );
                curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                curl_setopt( $ch,CURLOPT_POSTFIELDS, $fields );
                $result = curl_exec($ch );
                curl_close( $ch );
            } else {
                $path = APP_PATH . '/server/php/Slim/lib/'.IPHONE_PEM_FILE_NAME;
                $pass = IPHONE_PEM_PASSWORD;                
                $iphone_mode = IPHONE_IS_LIVE;
                if (!empty($iphone_mode)) {
                    $ssl_url = 'ssl://gateway.push.apple.com:2195';
                } else {
                    $ssl_url = 'ssl://gateway.sandbox.push.apple.com:2195';
                }
                $ctx = stream_context_create();
                stream_context_set_option($ctx, 'ssl', 'local_cert', $path);
                stream_context_set_option($ctx, 'ssl', 'passphrase', $pass);
                $fp = stream_socket_client($ssl_url, $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
                $body['aps'] = array(
                    'alert' => $message,
                    'sound' => 'default'
                );
                $payload = json_encode($body);
                $msg = chr(0) . pack('n', 32) . pack('H*', $device_token) . pack('n', strlen($payload)) . $payload;
                $result = fwrite($fp, $msg, strlen($msg));            
                fclose($fp);
            }
        }
    }    
}
