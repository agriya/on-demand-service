<?php
/**
 * Core configurations
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
/**
 * sendmail
 *
 * @param string $template    template name
 * @param array  $replace_content   replace content
 * @param string  $to  to email address
 * @param string  $reply_to_mail  reply email address
 *
 * @return true or false
 */
use \Firebase\JWT\JWT;

function getToken($user_id = 0)
{
    $key = SITE_NAME;
    $subject = $user_id;
    $issuedAt = time() - 1000;
    $notBefore = $issuedAt + 1000; //Adding 1000 seconds
    $expire = $notBefore + \Constants\JWT::JWTTOKENEXPTIME; // Adding 6000 seconds
    $token = array(
        "sub" => $subject,
        "iat" => $issuedAt,
        "exp" => $expire,
        "nbf" => $notBefore
    );
    $jwt = JWT::encode($token, $key);
    return $jwt;
}
function sendMail($template, $replace_content, $to, $reply_to_mail = '')
{
    global $_server_domain_url;
    $transport = Swift_MailTransport::newInstance();
    $mailer = Swift_Mailer::newInstance($transport);
    $default_content = array(
        '##SITE_NAME##' => SITE_NAME,
        '##SITE_URL##' => $_server_domain_url,
        '##FROM_EMAIL##' => SITE_FROM_EMAIL,
        '##CONTACT_EMAIL##' => SITE_CONTACT_EMAIL
    );
    $emailFindReplace = array_merge($default_content, $replace_content);
    $email_templates = Models\EmailTemplate::where('name', $template)->first();
    if (count($email_templates) > 0) {
        $content = $email_templates['body_content'];
        $content_type = 'text/html';
        $message = strtr($content, $emailFindReplace);
        $subject = strtr($email_templates['subject'], $emailFindReplace);
        $from_email = strtr($email_templates['from_name'], $emailFindReplace);
        $message = Swift_Message::newInstance($subject)->setFrom(array(
            $from_email => SITE_NAME
        ))->setTo(array(
            $to => 'You'
        ))->setBody($message)->setContentType($content_type);
        // Send the message
        //1 - Sent, 0 - Failure
        return $mailer->send($message);
    }
    return false;
}

/**
 * Checking already username is exists in users table
 *
 * @return true or false
 */
function checkAlreadyUsernameExists($username)
{
    $user = Models\User::where('username', $username)->first();
    if (!empty($user)) {
        return true;
    }
    return false;
}
/**
 * Checking already email is exists in users table
 *
 * @return true or false
 */
function checkAlreadyEmailExists($email, $id = 0)
{
    $user = Models\User::where('email', $email);
    if (!empty($id)) {
        $user->where('id', '!=', $id);
    }
    $user = $user->first();
    if (!empty($user)) {
        return true;
    }
    return false;
}
/**
 * Checking already mobile is exists in users profile table
 *
 * @return true or false
 */
function checkAlreadyMobileExists($mobile_num, $id = 0)
{
    $UserProfile = Models\UserProfile::where('phone', $mobile_num);
    if (!empty($id)) {
        $UserProfile->where('id', '!=', $id);
    }
    $UserProfile = $UserProfile->first();
    if (!empty($UserProfile)) {
        return true;
    }
    return false;
}
/**
 * To generate random string
 *
 * @param array  $arr_characters Random string options
 * @param string $length         Length of the random string
 *
 * @return string
 */
function getRandomStr($arr_characters, $length)
{
    $rand_str = '';
    $characters_length = count($arr_characters);
    for ($i = 0; $i < $length; ++$i) {
        $rand_str.= $arr_characters[rand(0, $characters_length - 1) ];
    }
    return $rand_str;
}
/**
 * To generate the encrypted password
 *
 * @param string $str String to be encrypted
 *
 * @return string
 */
function getCryptHash($str)
{
    $salt = '';
    if (CRYPT_BLOWFISH) {
        if (version_compare(PHP_VERSION, '5.3.7') >= 0) { // http://www.php.net/security/crypt_blowfish.php
            $algo_selector = '$2y$';
        } else {
            $algo_selector = '$2a$';
        }
        $workload_factor = '12$'; // (around 300ms on Core i7 machine)
        $val_arr = array(
            '.',
            '/'
        );
        $range1 = range('0', '9');
        $range2 = range('a', 'z');
        $range3 = range('A', 'Z');
        $res_arr = array_merge($val_arr, $range1, $range2, $range3);
        $salt = $algo_selector . $workload_factor . getRandomStr($res_arr, 22); // './0-9A-Za-z'
    } elseif (CRYPT_MD5) {
        $algo_selector = '$1$';
        $char1 = chr(33);
        $char2 = chr(127);
        $range = range($char1, $char2);
        $salt = $algo_selector . getRandomStr($range, 12); // actually chr(0) - chr(255), but used ASCII only
    } elseif (CRYPT_SHA512) {
        $algo_selector = '$6$';
        $workload_factor = 'rounds=5000$';
        $char1 = chr(33);
        $char2 = chr(127);
        $range = range($char1, $char2);
        $salt = $algo_selector . $workload_factor . getRandomStr($range, 16); // actually chr(0) - chr(255)
    } elseif (CRYPT_SHA256) {
        $algo_selector = '$5$';
        $workload_factor = 'rounds=5000$';
        $char1 = chr(33);
        $char2 = chr(127);
        $range = range($char1, $char2);
        $salt = $algo_selector . $workload_factor . getRandomStr($range, 16); // actually chr(0) - chr(255)
    } elseif (CRYPT_EXT_DES) {
        $algo_selector = '_';
        $val_arr = array(
            '.',
            '/'
        );
        $range1 = range('0', '9');
        $range2 = range('a', 'z');
        $range3 = range('A', 'Z');
        $res_arr = array_merge($val_arr, $range1, $range2, $range3);
        $salt = $algo_selector . getRandomStr($res_arr, 8); // './0-9A-Za-z'.
    } elseif (CRYPT_STD_DES) {
        $algo_selector = '';
        $val_arr = array(
            '.',
            '/'
        );
        $range1 = range('0', '9');
        $range2 = range('a', 'z');
        $range3 = range('A', 'Z');
        $res_arr = array_merge($val_arr, $range1, $range2, $range3);
        $salt = $algo_selector . getRandomStr($res_arr, 2); // './0-9A-Za-z'
    }
    return crypt($str, $salt);
}
/**
 * To login using social networking site accounts
 *
 * @params $profile
 * @params $provider_id
 * @params $provider
 * @params $adapter
 * @return array
 */
function social_login($adapter, $provider, $profile)
{
    global $authUser;
    if ($provider['id'] == \Constants\SocialLogins::Twitter) {
        $access_token = $profile->access_token;
        $access_token_secret = $profile->access_token_secret;
    } else {
        $access_token = $profile->access_token;
    }
    $providerUser = Models\ProviderUser::where('provider_id', $provider['id'])->where('foreign_id', $profile->identifier)->where('is_connected', true)->first();
    if (!empty($providerUser)) {
        $providerUser->access_token = $access_token;
        $providerUser->update();
        if (empty($authUser)) {
            $loggedin_user_id = $providerUser['user_id'];
        } else if (!empty($authUser) && $authUser['id'] != $providerUser['user_id']) {
            $response = array(
                'error' => array(
                    'code' => 1,
                    'message' => 'Some other user connected'
                )
            );
        }
    } else {
        if (!empty($authUser)) {
            $providerUser = new Models\ProviderUser;
            $providerUser->user_id = $authUser['id'];
            $providerUser->provider_id = $provider['id'];
            $providerUser->foreign_id = $profile->identifier;
            $providerUser->access_token = $access_token;
            $providerUser->access_token_secret = !empty($access_token_secret) ? $access_token_secret : '';
            $providerUser->is_connected = true;
            $providerUser->profile_picture_url = !empty($profile->photoURL) ? $profile->photoURL : '';
            $providerUser->save();
            $response = array(
                'error' => array(
                    'code' => 0,
                    'message' => 'Connected succesfully'
                )
            );
        } else {
            $isEmailExist = Models\User::where('email', $profile->email)->first();
            if (empty($isEmailExist) && isset($profile->role_id)) {
                $user = new Models\User;
                $username = strtolower(str_replace(' ', '', $profile->displayName));
                $username = $user->checkUserName($username);
                $user->username = Inflector::slug($username, '-');
                $user->email = $profile->email;
                $user->password = getCryptHash('default'); // dummy password
                $user->is_email_confirmed = true;
                $user->is_active = true;
                $user->role_id = $profile->role_id;
                $user->save();
                $providerUser = new Models\ProviderUser;
                $providerUser->user_id = $user->id;
                $providerUser->provider_id = $provider['id'];
                $providerUser->foreign_id = $profile->identifier;
                $providerUser->access_token = $access_token;
                $providerUser->access_token_secret = !empty($access_token_secret) ? $access_token_secret : '';
                $providerUser->is_connected = true;
                $providerUser->profile_picture_url = !empty($profile->photoURL) ? $profile->photoURL : '';
                $providerUser->save();
                $loggedin_user_id = $user->id;
            } else {
                if (!empty($isEmailExist)) {
                    $response = array(
                        'error' => array(
                            'code' => 1,
                            'message' => 'Email already exist'
                        )
                    );
                } else {
                    $response = array(
                        'error' => array(
                            'code' => 1,
                            'message' => 'Role Missing',
                            'access_token' => $profile->access_token,
                            'access_token_secret' => isset($profile->access_token_secret) ? $profile->access_token_secret : null
                        )
                    );
                }
                    
            }
        }
    }
    if (!empty($loggedin_user_id)) {
        $condition = array(
            'id' => $loggedin_user_id,
            'is_active' => 1,
            'is_email_confirmed' => 1
        );
        $user = Models\User::with('user_profile', 'attachment', 'listing_photo')->where($condition)->first();
        if (!empty($user)) {
            $token = array(
                'token' => getToken($user->id)
            );
            insertUserToken($user->id, $token['token'], '', true);
            $userLogin = new Models\UserLogin;
            $userLogin->user_id = $loggedin_user_id;
            $userLogin->role_id = $user->role_id;
            $userLogin->user_agent = $_SERVER['HTTP_USER_AGENT'];
            $userLogin->save();
            $response = $token + $user->toArray();
            $response['error']['code'] = 0;
        } else {
            $response = array(
                'error' => array(
                    'code' => 1,
                    'message' => 'Your account is deactivated.'
                )
            );
        }
    }
    return $response;
}

/**
 * Curl _execute
 *
 * @params string $url
 * @params string $method
 * @params array $method
 * @params string $format
 *
 * @return array
 */
function _execute($url, $method = 'get', $post = array(), $format = 'plain')
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    if ($method == 'get') {
        curl_setopt($ch, CURLOPT_POST, false);
    } elseif ($method == 'post') {
        if ($format == 'json') {
            $post_string = json_encode($post);
            $header = array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($post_string)
            );
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        } else {
            $post_string = http_build_query($post, '', '&');
        }
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
    } elseif ($method == 'put') {
        if ($format == 'json') {
            $post_string = json_encode($post);
            $header = array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($post_string)
            );
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        } else {
            $post_string = http_build_query($post, '', '&');
        }
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
    } elseif ($method == 'delete') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    // Note: timeout also falls here...
    if (curl_errno($ch)) {
        $return['error']['message'] = curl_error($ch);
        curl_close($ch);
        return $return;
    }
    switch ($http_code) {
        case 201:
        case 200:
            if (isJson($response)) {
                $return = safe_json_decode($response);
            } else {
                $return = $response;
            }
            break;

        case 401:
            $return['error']['code'] = 1;
            $return['error']['message'] = 'Unauthorized';
            break;

        default:
            $return['error']['code'] = 1;
            $return['error']['message'] = 'Not Found';
    }
    curl_close($ch);
    return $return;
}
/**
 * To check whether it is json or not
 *
 * @param json $string To check string is a JSON or not
 *
 * @return mixed
 */
function isJson($string)
{
    json_decode($string);
    //check last json error
    return (json_last_error() == JSON_ERROR_NONE);
}
/**
 * safe Json code
 *
 * @param json $json   json data
 *
 * @return array
 */
function safe_json_decode($json)
{
    $return = json_decode($json, true);
    if ($return === null) {
        $error['error']['code'] = 1;
        $error['error']['message'] = 'Syntax error, malformed JSON';
        return $error;
    }
    return $return;
}
/**
 * Get request by using CURL
 *
 * @param string $url    URL to execute
 *
 * @return mixed
 */
function _doGet($url)
{
    $return = _execute($url);
    return $return;
}
/**
 * Post request by using CURL
 *
 * @param string $url    URL to execute
 * @param array  $post   Post data
 * @param string $format To differentiate post data in plain or json format
 *
 * @return mixed
 */
function _doPost($url, $post = array(), $format = 'plain')
{
    return _execute($url, 'post', $post, $format);
}
/**
 * Render Json Response
 *
 * @param array $response    response
 * @param string  $message  Messgae
 * @param string  $fields  fields
 * @param int  $isError  isError
 * @param int  $statusCode  Status code
 *
 * @return json response
 */
function renderWithJson($response, $message = '', $raw_message = '', $fields = '', $isError = 0, $statusCode = 200)
{
    global $app;
    $appResponse = $app->getContainer()->get('response');
    if (!empty($fields)) {
        $statusCode = 422;
    }
    $error = array(
        'error' => array(
            'code' => $isError,
            'message' => $message,
            'raw_message' => (RAW_MESSAGE) ? $raw_message : "",
            'fields' => $fields
        )
    );
    return $appResponse->withJson($response + $error, $statusCode);
}
/*
 * Attachment Save function
 *
 * @param class_name,file,foreign_id
 *
 *
*/
function saveImage($class_name, $file, $foreign_id, $is_multi = false)
{
    if ((!empty($file)) && (file_exists(APP_PATH . '/media/tmp/' . $file))) {
        //Removing and ree inserting new image
        $userImg = Models\Attachment::where('foreign_id', $foreign_id)->where('class', $class_name)->first();
        if (!empty($userImg) && !($is_multi)) {
            if (file_exists(APP_PATH . '/media/' . $class_name . '/' . $foreign_id . '/' . $userImg['filename'])) {
                unlink(APP_PATH . '/media/' . $class_name . '/' . $foreign_id . '/' . $userImg['filename']);
                $userImg->delete();
            }
            // Removing Thumb folder images
            $mediadir = APP_PATH . '/client/app/images/';
            $whitelist = array(
                '127.0.0.1',
                '::1'
            );
            if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
                $mediadir = APP_PATH . '/client/images/';
            }
            foreach (THUMB_SIZES as $key => $value) {
                $list = glob($mediadir . $key . '/' . $class_name . '/' . $foreign_id . '.*');
                if ($list) {
                    @unlink($list[0]);
                }
            }
        }
        $attachment = new Models\Attachment;
        if (!file_exists(APP_PATH . '/media/' . $class_name . '/' . $foreign_id)) {
            mkdir(APP_PATH . '/media/' . $class_name . '/' . $foreign_id, 0777, true);
        }
        $src = APP_PATH . '/media/tmp/' . $file;
        $dest = APP_PATH . '/media/' . $class_name . '/' . $foreign_id . '/' . $file;
        copy($src, $dest);
        unlink($src);
        $info = getimagesize($dest);
        $width = $info[0];
        $height = $info[1];
        $attachment->filename = $file;
        $attachment->width = $width;
        $attachment->height = $height;
        $attachment->dir = $class_name . '/' . $foreign_id;
        $attachment->foreign_id = $foreign_id;
        $attachment->class = $class_name;
        $attachment->mimetype = $info['mime'];
        $attachment->save();
        $ext = strtolower(substr($file, -4));
        return $attachment->id;
    }
}
function saveImageData($class_name, $file, $foreign_id, $is_multi = false)
{
    if (!empty($file)) {
        $data = explode(',', $file);
        $file = $data[1];
        $image = base64_decode($file);
        $f = finfo_open();
        $mime_type = finfo_buffer($f, $image, FILEINFO_MIME_TYPE);
        $imageextension = explode("/", $mime_type);
        $name = md5(time());
        $file = $name . '.' . $imageextension[1];
        //Removing and ree inserting new image
        $userImg = Models\Attachment::where('foreign_id', $foreign_id)->where('class', $class_name)->first();
        if (!empty($userImg) && !($is_multi)) {
            if (file_exists(APP_PATH . '/media/' . $class_name . '/' . $foreign_id . '/' . $userImg['filename'])) {
                unlink(APP_PATH . '/media/' . $class_name . '/' . $foreign_id . '/' . $userImg['filename']);
                $userImg->delete();
            }
            // Removing Thumb folder images
            $mediadir = APP_PATH . '/client/app/images/';
            $whitelist = array(
                '127.0.0.1',
                '::1'
            );
            if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
                $mediadir = APP_PATH . '/client/images/';
            }
            foreach (THUMB_SIZES as $key => $value) {
                $list = glob($mediadir . $key . '/' . $class_name . '/' . $foreign_id . '.*');
                if ($list) {
                    @unlink($list[0]);
                }
            }
        }
        $attachment = new Models\Attachment;
        if (!file_exists(APP_PATH . '/media/' . $class_name . '/' . $foreign_id)) {
            mkdir(APP_PATH . '/media/' . $class_name . '/' . $foreign_id, 0777, true);
        }
        $fp = fopen(APP_PATH . '/media/' . $class_name . '/' . $foreign_id . '/' . $file, 'w+') or die("Unable to open file!");
        fwrite($fp, $image);
        fclose($fp);
        $dest = APP_PATH . '/media/' . $class_name . '/' . $foreign_id . '/' . $file;
        $info = getimagesize($dest);
        $width = $info[0];
        $height = $info[1];
        $attachment->filename = $file;
        $attachment->width = $width;
        $attachment->height = $height;
        $attachment->dir = $class_name . '/' . $foreign_id;
        $attachment->foreign_id = $foreign_id;
        $attachment->class = $class_name;
        $attachment->mimetype = $info['mime'];
        $attachment->save();
        return $attachment->id;
    }
}
function human_filesize($bytes, $decimals = 2)
{
    $factor = floor((strlen($bytes) - 1) / 3);
    if ($factor > 0) {
        $sz = 'KMGT';
    }
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor - 1] . 'B';
}
function listFolderFiles($dir, $parent_key = '', $main_dir = array())
{
    $directories = scandir($dir);
    foreach ($directories as $key => $directory) {
        if ($directory != '.' && $directory != '..') {
            if (is_dir($dir . '/' . $directory)) {
                $main_directory = $directory;
                if ($parent_key) {
                    $main_directory = $parent_key . '/' . $directory;
                }
                $main_directories = listFolderFiles($dir . '/' . $directory, $main_directory, $main_dir);
                if ($main_directories) {
                    foreach ($main_directories as $main_directory) {
                        $main_dir[] = ['name' => $main_directory['name'], 'size' => human_filesize(filesize($dir . '/' . $directory)) ];
                    }
                }
            } else {
                if ($parent_key) {
                    $main_dir[] = ['name' => $parent_key . '/' . $directory, 'size' => human_filesize(filesize($dir . '/' . $directory)) ];
                } else {
                    $main_dir[] = ['name' => $directory, 'size' => human_filesize(filesize($dir . '/' . $directory)) ];
                }
            }
        }
    }
    return $main_dir;
}
function gen_uuid()
{
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),
        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,
        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,
        // 48 bits for "node"
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}
function getSlug($title)
{
    $slug = '';
    $pagesCount = Models\Page::where('title', $title);
    $pagesCount = $pagesCount->count();
    if (!empty($pagesCount)) {
        $slug = Inflector::slug(strtolower($title), '-') . '-' . $pagesCount;
    } else {
        $slug = Inflector::slug(strtolower($title), '-');
    }
    return $slug;
}
function rmdir_recursive($dirname)
{
    if (!is_dir($dirname)) {
        trigger_error(__FUNCTION__ . "({$dirname}): No such file or directory", E_USER_WARNING);
        return false;
    }
    if ($handle = opendir($dirname)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                if (is_dir("{$dirname}/{$file}")) {
                    call_user_func(__FUNCTION__, "{$dirname}/{$file}");
                } else {
                    if (unlink("{$dirname}/{$file}") === false) {
                        return false;
                    }
                }
            }
        }
        closedir($handle);
        if (rmdir($dirname) === false) {
            return false;
        }
        return true;
    }
    return false;
}
function merge_details($tables, $table)
{
    foreach ($table as $key => $merge_table) {
        if (isset($merge_table['listview'])) {
            if (isset($merge_table['listview']['filters'])) {
                $tables[$key]['listview']['filters'] = array_merge($tables[$key]['listview']['filters'], $merge_table['listview']['filters']);
            }
            if (isset($merge_table['listview']['fields'])) {
                $tables[$key]['listview']['fields'] = array_merge($tables[$key]['listview']['fields'], $merge_table['listview']['fields']);
            }
        }
        if (isset($merge_table['showview'])) {
            $tables[$key]['showview']['fields'] = array_merge($tables[$key]['showview']['fields'], $merge_table['showview']['fields']);
        }
        if (isset($merge_table['editionview'])) {
            $tables[$key]['editionview']['fields'] = array_merge($tables[$key]['editionview']['fields'], $merge_table['editionview']['fields']);
        }
        if (isset($merge_table['creationview'])) {
            $tables[$key]['creationview']['fields'] = array_merge($tables[$key]['creationview']['fields'], $merge_table['creationview']['fields']);
        }
    }
    return $tables;
}
function merged_menus($menus, $merge_menus)
{
    foreach ($merge_menus as $key => $menu) {
        $menus[$key]['child_sub_menu'] = array_merge($menus[$key]['child_sub_menu'], $menu['child_sub_menu']);
    }
    return $menus;
}
function menu_sub_array_sorting($array, $on, $order = SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();
    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }
        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
                break;

            case SORT_DESC:
                arsort($sortable_array);
                break;
        }
        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }
    return $new_array;
}
function get_mime($filename)
{
    $mime = false;
    if ($img_size_arr = getimagesize($filename)) {
        if (isset($img_size_arr['mime'])) {
            $mime = $img_size_arr['mime'];
        }
    }
    if (!$mime) {
        if (function_exists('mime_content_type')) { // if mime_content_type exists use it.
            $mime = mime_content_type($filename);
        } elseif (function_exists('finfo_open')) { // if Pecl installed use it
            $finfo = finfo_open(FILEINFO_MIME);
            $mime = finfo_file($finfo, $filename);
            finfo_close($finfo);
        } else { // if nothing left try shell
            if (stripos(PHP_OS, 'WIN') !== false) { // Nothing to do on windows
                $mime = false;
            } elseif (stripos(PHP_OS, 'mac') !== false) { // Correct output on macs
                $mime = trim(exec('file -b --mime ' . escapeshellarg($filename)));
            } else { // Regular unix systems
                $mime = trim(exec('file -bi ' . escapeshellarg($filename)));
            }
        }
    }
    return $mime;
}
function insertUserToken($user_id, $jwt_token, $request = '', $manual_oauth = false)
{
    if($manual_oauth) {
        $oauth_clients = Models\OauthClient::where('name', 'Web')->first();
    } elseif (!empty($request->getHeaders() ['HTTP_X_AG_APP_SECRET']) && !empty($request->getHeaders() ['HTTP_X_AG_APP_ID'])) {
        $api_key = $request->getHeaders() ['HTTP_X_AG_APP_ID'][0];
        $api_secret = $request->getHeaders() ['HTTP_X_AG_APP_SECRET'][0];
        $oauth_clients = Models\OauthClient::where(['api_key' => $api_key, 'api_secret' => $api_secret])->first();
    }
    if ($oauth_clients) {
        $issuedAt = time();
        $notBefore = $issuedAt + 11000; //Adding 1000 seconds
        $expire = $notBefore + getenv("JWT_TOKEN_EXP_TIME"); // Adding 6000 seconds
        $expirationDateTime = date('Y-m-d H:i:s', $expire);
        $insertToken = array(
            'user_id' => $user_id,
            'token' => $jwt_token,
            'expires' => $expirationDateTime,
            'oauth_client_id' => $oauth_clients->id
        );
        $jwtToken = new Models\UserToken();
        $jwtToken->fill($insertToken);
        $jwtToken->save();
    }    
}
function uploadFile($file)
{
    $newfile = $file['file'];
    $size = $newfile->getSize();
    $type = pathinfo($newfile->getClientFilename(), PATHINFO_EXTENSION);
    $alloted_types = array(
        'gif',
        'jpg',
        'jpeg',
        'png',
        'swf',
        'psd',
        'wbmp',
        'JPG',
        'plain',
    );
    $mime_type_ext = array(
        'image/png' => 'png',
        'image/jpeg' => 'jpeg',
        'image/jpeg' => 'jpg',
        'image/jpeg' => 'JPG',
        'image/gif' => 'gif',
        'image/bmp' => 'bmp',
        'image/vnd.adobe.photoshop' => 'psd',
        'application/x-shockwave-flash' => 'swf'
    );
    if ($newfile->getClientMediaType() == "application/octet-stream") {
        $imageMime = getimagesize($newfile->file); // get temporary file REAL info
        $realMime = $imageMime['mime']; //set in our array the correct mime
        $type = $mime_type_ext[$realMime];
    }
    $bytes = random_bytes(5);
    $code = bin2hex($bytes);
    $name = md5(time()) . $code;
    $max_size = MAX_UPLOAD_SIZE * 1000;
    if (($size <= $max_size)) {
        if (in_array($type, $alloted_types)) {
            if (!file_exists(APP_PATH . '/media/tmp/')) {
                mkdir(APP_PATH . '/media/tmp/');
            }
            $filePath = APP_PATH . '/media/tmp/';
            try {
                $newfile->moveTo($filePath . $name . "." . $type);
                $filename = $name . '.' . $type;
                $response = array(
                    'attachment' => $filename,
                    'error' => array(
                        'code' => 0,
                        'message' => 'upload successfull'
                    )
                );
            } catch (Exception $e) {
                $response = array(
                    'error' => array(
                        'code' => 1,
                        'message' => $e->getMessage() ,
                        'fields' => ''
                    )
                );
            }
        } else {
            $response = array(
                'error' => array(
                    'code' => 1,
                    'message' => 'Attachment could not be added. Should be Upload valid image.',
                    'fields' => ''
                )
            );            
        }
    } else {
        $response = array(
            'error' => array(
                'code' => 1,
                'message' => 'Attachment could not be added. Image size should be less than 8Mb.',
                'fields' => ''
            )
        );
    }
    return $response;
}
/**
 * Findorsave city details
 *
 * @params string $data
 * @params int $country_id
 * @params int $state_id
 */
function findOrSaveAndGetCityId($data, $country_id, $state_id = '')
{
    $city = new Models\City;
    if (!empty($state_id)) {
        $city_list = $city->where('name', $data)->where('state_id', $state_id)->where('country_id', $country_id)->select('id')->first();
        $city->state_id = $state_id;
    } else {
        $city_list = $city->where('name', $data)->where('country_id', $country_id)->select('id')->first();
    }
    if (!empty($city_list)) {
        return $city_list['id'];
    } else {
        $city->name = $data;
        $city->country_id = $country_id;
        $city->save();
        return $city->id;
    }
}
/**
 * Findorsave state details
 *
 * @params string $data
 * @params int $country_id
 */
function findOrSaveAndGetStateId($data, $country_id)
{
    $state = new Models\State;
    $state_list = $state->where('name', $data)->where('country_id', $country_id)->select('id')->first();
    if (!empty($state_list)) {
        return $state_list['id'];
    } else {
        $state->name = $data;
        $state->country_id = $country_id;
        $state->save();
        return $state->id;
    }
}
/**
 * Get country id
 *
 * @param int $iso2  ISO2
 *
 * @return int country Id
 */
function findCountryIdFromIso2($iso2)
{
    $country = Models\Country::where('iso2', $iso2)->select('id')->first();
    if (!empty($country)) {
        return $country['id'];
    }
}
function saveMessage($depth, $path, $user_id, $other_user_id, $message_content_id, $parent_id, $class, $foreign_id, $is_sender, $model_id = 0, $is_private)
{
    $message = new Models\Message;
    $message->depth = $depth;
    $message->user_id = $user_id;
    $message->other_user_id = $other_user_id;
    $message->message_content_id = $message_content_id;
    $message->parent_id = $parent_id;
    $message->class = $class;
    $message->is_sender = $is_sender;
    $message->foreign_id = $foreign_id;
    $message->model_id = $model_id;
    $message->is_private = $is_private;
    $message->save();
    $idConverted = base_convert($message->id, 10, 36);
    $materialized_path = sprintf("%08s", $idConverted);
    if (empty($path)) {
        $message->materialized_path = $materialized_path;
    } else {
        $message->materialized_path = $path . '-' . $materialized_path;
    }
    $message->root = checkParentMessage($parent_id, $message->id);
    $message->save();
    Models\Message::where('root', $message->root)->update(array(
        'freshness_ts' => date('Y-m-d h:i:s')
    ));
    return $message->id;
}
function checkParentMessage($parent_id, $id)
{
    $parentMessage = Models\Message::where('id', $parent_id)->select('id', 'parent_id')->first();
    if (!empty($parentMessage)) {
        if (!empty($parentMessage->parent_id)) {
            checkParentMessage($parentMessage->parent_id, $parentMessage->id);
        } else {
            return $parentMessage->id;
        }
    }
    return $id;
}

function captchaCheck()
{
    $remoteip = $_SERVER['REMOTE_ADDR'];
    $secret = CAPTCHA_SECRET_KEY;
    $recaptcha = new ReCaptcha($secret);
    $response = $recaptcha->verify($captcha, $remoteip);
    if ($response->isSuccess()) {
        return true;
    } else {
        return false;
    }
}
function getLatitudeLongtitude($location)
{
    if ($location != '') {
        $mapUrlData = urlencode($location);
        $request_url = "http://maps.googleapis.com/maps/api/geocode/xml?address=" . $mapUrlData . "&sensor=true";
        $xml = simplexml_load_file($request_url) or die("url not loading");
        echo $xml;
        die;
        $status = $xml->status;
        $LatLng = '';
        if ($status == "OK") {
            $Lat = $xml->result->geometry->location->lat;
            $Lon = $xml->result->geometry->location->lng;
            $LatLng = "$Lat,$Lon";
        }
        return $LatLng;
    } else {
        return "Null";
    }
}
function insertTransaction($user_id, $to_user_id, $foreign_id, $class, $transaction_type, $payment_gateway_id, $amount, $site_revenue_from_freelancer, $gateway_fees = 0, $coupon_id = 0, $site_revenue_from_employer = 0, $model_id = '', $zazpay_gateway_id = '')
{
    $transaction = new Models\Transaction;
    $transaction->user_id = $user_id;
    $transaction->to_user_id = $to_user_id;
    $transaction->foreign_id = $foreign_id;
    $transaction->class = $class;
    $transaction->transaction_type = $transaction_type;
    $transaction->payment_gateway_id = $payment_gateway_id;
    $transaction->amount = $amount;
    $transaction->site_revenue_from_freelancer = $site_revenue_from_freelancer;
    $transaction->site_revenue_from_employer = $site_revenue_from_employer;
    $transaction->coupon_id = $coupon_id;
    $transaction->save();
    return $transaction->id;
}
function isPluginEnabled($pluginName)
{
    $plugins = explode(',', SITE_ENABLED_PLUGINS);
    $plugins = array_map('trim', $plugins);
    if (in_array($pluginName, $plugins)) {
        return true;
    }
    return false;
}
function getReturnURL($model, $response)
{
    global $_server_domain_url;
    $result = array();
    if ($model == 'Appointment') {
        $result['success_url'] = $_server_domain_url . '/booking/all?error_code=0';
        if ($response->success_url) {
            $result['success_url'] = $response->success_url;
        }
        $result['failure_url'] = $_server_domain_url . '/booking/all?error_code=512';
    } elseif ($model == 'User') {
        $result['success_url'] = $_server_domain_url . '/user_dashboard?error_code=0';       
        $result['failure_url'] = $_server_domain_url . '/user_dashboard?error_code=512';
    }
    return $result;
}
function addPushNotification($user_id, $pushMessage = array(), $is_immediate = false)
{
    $user = Models\User::select('is_push_notification_enabled', 'is_app_device_available')->where('id', $user_id)->first();
    $apnsDevice = Models\ApnsDevice::select('id')->where('user_id', $user_id)->first();
    if (!empty($apnsDevice) && $user->is_push_notification_enabled && $user->is_app_device_available) {
        $pushNotification = new Models\PushNotification;
        $pushNotification->user_device_id = $apnsDevice->id;
        $pushNotification->message_type = $pushMessage['message_type'];
        $pushNotification->message = getNotificationMessage($pushMessage);
        $pushNotification->save();
        if ($is_immediate == true) {
            Models\PushNotification::sendSinglePushNotification($pushNotification->id);
        }
    }
}
function getNotificationMessage($pushMessage = array())
{
    global $_server_domain_url;
    $notificationMessage = Models\Setting::where('name', $pushMessage['message_type'])->first();
    $default_content = array(
        '##SITE_NAME##' => SITE_NAME
    );
    if (isset($pushMessage['appointment_id'])) {
        $appointment = Models\Appointment::with('user.user_profile', 'provider_user', 'service')->find($pushMessage['appointment_id']);
        if (!empty($appointment)) {
            if (!empty($appointment['user'])) {
                if (!empty($appointment['user']['user_profile']['first_name']) || !empty($appointment['user']['user_profile']['last_name'])) {
                    $request_username = $appointment['user']['user_profile']['first_name'].' '.$appointment['user']['user_profile']['last_name'];
                } else {
                    $request_username = $appointment['user']['email']; 
                }                
                $default_content['##REQUESTOR_NAME##'] = $request_username;
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
    if(isset($pushMessage['request_user_id'])){
        $request_user = Models\RequestsUser::find($pushMessage['request_user_id']);
        if(!empty($request_user)){
            $service_provider = Models\User::with('user_profile')->find($request_user['user_id']);
			$request = Models\Request::with('service','user')->find($request_user['request_id']);
            $default_content['##USERNAME##'] = $request['user']['username'];
            $default_content['##SERVICE_PROVIDER_FIRSTNAME##'] = $service_provider['user_profile']['first_name'];
            $default_content['##SERVICE_PROVIDER_LASTNAME##'] = $service_provider['user_profile']['last_name'];
            $default_content['##SERVICENAME##'] = $request['service']['name'];
            $default_content['##CALENDAR_URL##'] = $_server_domain_url.'/users/'.$service_provider['user_id'].'/'.$service_provider['user_profile']['first_name'].'+'.$service_provider['user_profile']['last_name'];
        }
    }
    if(isset($pushMessage['user_search_id'])){
        $user_searches = Models\UserSearch::find($pushMessage['user_search_id']);
        if(!empty($user_searches)){
            $service_provider = Models\User::with('user_profile')->find($pushMessage['service_provider_id']);
            $service = Models\Service::find($user_searches['service_id']);
			$user = Models\User::with('user_profile')->find($user_searches['user_id']);
            $default_content['##SERVICE_PROVIDER_FIRSTNAME##'] = $service_provider['user_profile']['first_name'];
            $default_content['##SERVICE_PROVIDER_LASTNAME##'] = $service_provider['user_profile']['last_name'];
            $default_content['##SERVICENAME##'] = $service['name'];
            $default_content['##CALENDAR_URL##'] = $_server_domain_url.'/users/'.$pushMessage['service_provider_id'].'/'.$service_provider['user_profile']['first_name'].'+'.$service_provider['user_profile']['last_name'];
        }
    }    
    if (!empty($notificationMessage)) {
        $notificationMessages = strtr($notificationMessage->value, $default_content);
    } else {
        $notificationMessages = $message_type;
    }
    return $notificationMessages;
}
function generateSlot($date, $start_time, $end_time, $slot_id, $array = array())
{
    $dates = array();
    $actual_end_time = date("H:i:s", strtotime($end_time));
    for($i = strtotime($start_time); $i< strtotime($end_time); $i = $i + $slot_id * 60) {
        $array['start'] = date('Y-m-d', $date) ."T". date("H:i:s", $i);
        $array['end'] = date('Y-m-d', $date) ."T". date("H:i:s", $i + $slot_id * 60);
        if(strtotime(date("H:i:s", $i + $slot_id * 60)) == strtotime('00:00:00')){
            $array['end'] = date('Y-m-d', $date) ."T". $actual_end_time;
        }
        if(!empty($array['status']))
        {

            if($array['status'] == 'unavailable')
            {
                $array['title'] = 'Unavailable';
                $array['color'] = !empty($array['color']) ? $array['color'] : '#bc0101'; 
            }
            elseif($array['status'] == 'booked')
            {
                if($array['appointment']['user']['username'])
                {
                    $array['title'] = 'Booked by '.$array['appointment']['user']['username'];
                    $array['color'] = !empty($array['color']) ? $array['color'] : '#59d771'; 
                }
            }elseif($array['status'] == 'full-day-off'){
                $array['title'] = 'full-day-off';
                $array['color'] = !empty($array['color']) ? $array['color'] : '#bc0101'; 
            }elseif($array['status'] == 'week-off'){
                $array['title'] = 'week-off';
                $array['color'] = !empty($array['color']) ? $array['color'] : '#bc0101'; 
            }else{
                $array['title'] = 'Available';
                $array['status'] = 'Available';
                $array['color'] = !empty($array['color']) ? $array['color'] : '#B8EAAD'; 
            }
        }else{
            $array['title'] = 'Available';
            $array['status'] = 'Available';
            $array['color'] = !empty($array['color']) ? $array['color'] : '#B8EAAD'; 
        }
        $array['rendering'] = 'background'; 
        $dates[date("H:i:s", $i)] = $array;  
    }
    return $dates;
}
function getIsDayName($date)
{
    $date_name = date("l", strtotime($date));
    $is_open_day = '';
    $practice_open = '';
    $practice_close = '';
    if ($date_name == 'Monday') {
        $is_open_day = 'is_monday_open';
        $practice_open = 'monday_practice_open';
        $practice_close = 'monday_practice_close';
    } elseif ($date_name == 'Tuesday') {
        $is_open_day = 'is_tuesday_open';
        $practice_open = 'tuesday_practice_open';
        $practice_close = 'tuesday_practice_close';
    } elseif ($date_name == 'Wednesday') {
        $is_open_day = 'is_wednesday_open';
        $practice_open = 'wednesday_practice_open';
        $practice_close = 'wednesday_practice_close';
    } elseif ($date_name == 'Thursday') {
        $is_open_day = 'is_thursday_open';
        $practice_open = 'thursday_practice_open';
        $practice_close = 'thursday_practice_close';
    } elseif ($date_name == 'Friday') {
        $is_open_day = 'is_friday_open';
        $practice_open = 'friday_practice_open';
        $practice_close = 'friday_practice_close';
    } elseif ($date_name == 'Saturday') {
        $is_open_day = 'is_saturday_open';
        $practice_open = 'saturday_practice_open';
        $practice_close = 'saturday_practice_close';
    } elseif ($date_name == 'Sunday') {
        $is_open_day = 'is_sunday_open';
        $practice_open = 'sunday_practice_open';
        $practice_close = 'sunday_practice_close';
    }
    return array(
        'is_open_day' => $is_open_day,
        'practice_open' => $practice_open,
        'practice_close' => $practice_close
    );
}