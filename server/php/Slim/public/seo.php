<?php
/**
 * For SEO Purpose
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    GETLANCERV3
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya
 * @license    http://www.agriya.com/ Agriya Licence
 * @link       http://www.agriya.com
 */
require_once '../lib/bootstrap.php';
ini_set('error_reporting', E_ALL);
global $_server_domain_url;
$inflector = new Inflector();
$php_path = PHP_BINDIR . DIRECTORY_SEPARATOR . 'php';
$api_url_map = array(     
    '/\/users\/(?P<user_id>\d+)\/(?P<slug>.*)/' => array(
        'api_url' => '/api/v1/users/{id}',
    ) ,
    '/\/users(.*)/' => array(
        'api_url' => '/api/v1/users',
        'title' => 'Users'
    ) ,
    '/^\/users\/login$/' => array(
        'api_url' => null,
        'title' => 'Login'
    ) ,
    '/^\/users\/register$/' => array(
        'api_url' => null,
        'title' => 'Register'
    ) ,
    '/^\/users\/forgot_password$/' => array(
        'api_url' => null,
        'title' => 'Forgot Password'
    ) ,
    '/\/page\/(?P<page_id>\d+)\/(?P<slug>.*)/' => array(
        'api_url' => '/api/v1/pages/{id}',
    ) ,
    '/^\/$/' => array(
        'api_url' => null,
        'title' => 'Home'
    ) ,
);
$meta_keywords = $meta_description = $title = $site_name = $api_url = '';
$og_image = $_server_domain_url . '/images/no_image_available.png';
$og_type = 'website';
$og_url = $_server_domain_url . '' . $_GET['_escaped_fragment_'];
$res = Models\Setting::whereIn('name', array(
    'META_KEYWORDS',
    'META_DESCRIPTION',
    'SITE_NAME'
))->get()->toArray();
foreach ($res as $key => $arr) {
    if ($res[$key]['name'] == 'META_KEYWORDS') {
        $meta_keywords = $res[$key]['value'];
    }
    if ($res[$key]['name'] == 'META_DESCRIPTION') {
        $meta_description = $res[$key]['value'];
    }
    if ($res[$key]['name'] == 'SITE_NAME') {
        $title = $site_name = $res[$key]['value'];
    }
}
if (!empty($_GET['_escaped_fragment_'])) {
    foreach ($api_url_map as $url_pattern => $values) {
        if (preg_match($url_pattern, $_GET['_escaped_fragment_'], $matches)) {
             // Match _escaped_fragment_ with our api_url_map array; For selecting API call
            if (!empty($values['business_name'])) { //Default title; We will change title for course and user page below;
                $title = $site_name . ' | ' . $values['business_name'];
            }  
            if (!empty($values['api_url'])) {
                $id = (!empty($matches['user_id']) ? $matches['user_id'] :
                      (!empty($matches['page_id']) ? $matches['page_id'] : 0
                ));
                if (!empty($id)) {
                    $api_url = str_replace('{id}', $id, $values['api_url']); // replacing id value
                } else {
                    $api_url = $values['api_url']; // using defined api_url
                }
               $query_string = !empty($matches[1]) ? $matches[1] : '';
                if ($values['api_url'] == '/api/v1/pages/{id}') {
                    $api_url = '/api/v1/pages/{id}';
                    $page = Models\Page::where('id', $id)->first();
                    if (!empty($page)) {
                        $meta_keywords = !empty($page->title) ? $page->title : '';                            
                        $meta_description = !empty($page->page_content) ? $page->page_content : '';
                        $published = !empty($page->created_at) ? $page->created_at : '';
                    }
                    $og_type = 'Page';
                    $actor['@type'] = 'Page';
                    $actor['@id'] = 'Page';
                    $actor['displayName'] = $meta_keywords;
                    $object['@type'] = 'Note';
                    $object['content'] = $meta_description;
                    
                } 
                elseif ($values['api_url'] == '/api/v1/users/{id}') {
                    $api_url = '/api/v1/users/{id}';
                    $user = Models\User::with('user_profile', 'attachment', 'service_users.service', 'listing_photo')->where('id', $id)->first();
                    $meta_description = !empty($user->user_profile->listing_description) ? $user->user_profile->listing_description : $meta_description;
                    $user_id = $user->id;
                    $keywords = '';
                    if (!empty($user->service_users)) {
                        foreach ($user->service_users as $service_user) {
                            $keywords .= $service_user->service->name.',';
                        }
                        $keywords = rtrim($keywords,',');
                    }
                    $meta_keywords = !empty($keywords) ? $keywords : '';                    
                    $title =  !empty($user->user_profile->listing_title) ? $user->user_profile->listing_title: $user->user_profile->first_name. ' '. $user->user_profile->last_name;
                    $name =  !empty($user->username) ? $user->username: $title;
                    $mobile = !empty($user->phone_number) ? $user->phone_number : '';
                    $og_type = 'Person';
                    $jobTitle = 'User';
                    if ($user->listing_photo) {
                        $og_image = $_server_domain_url . '/images/seo_listing_thumb/ListingPhoto/' . $user->listing_photo[0]->id . '.' . md5('ListingPhoto' . $user->listing_photo[0]->id . 'png' . 'seo_listing_thumb') . '.' . 'png';
                    } elseif ($user->attachment) {
                        $og_image = $_server_domain_url . '/images/seo_listing_thumb/UserAvatar/' . $user->attachment->id . '.' . md5('UserAvatar' . $user->attachment->id . 'png' . 'seo_listing_thumb') . '.' . 'png';
                    }
                    $og_url = $_server_domain_url . '/users/' . $user_id.'/'.$user->username;
                }
            }
            break;
        } 
    }
}

$app_id = Models\Provider::where('name', 'Facebook')->first();
?>
<!DOCTYPE html>
<html>
<head>
 <title><?php echo $title; ?></title>
  <meta charset="UTF-8">
  <meta name="description" content="<?php
    echo $meta_description; ?>"/>
  <meta name="keywords" content="<?php
    echo $meta_keywords; ?>"/>
  <meta property="og:app_id" content="<?php
    echo $app_id->api_key; ?>"/>
  <meta property="og:type" content="<?php
    echo $og_type; ?>"/>
  <meta property="og:title" content="<?php
    echo $title; ?>"/>
  <meta property="og:description" content="<?php
    echo $meta_description; ?>"/>
  <meta property="og:type" content="<?php
    echo $og_type; ?>"/>
  <meta property="og:image" content="<?php
    echo $og_image; ?>"/>
  <meta property="og:site_name" content="<?php
    echo $site_name; ?>"/>
  <meta property="og:url" content="<?php
    echo $og_url; ?>"/> 
    <?php
        if ($api_url == '/api/v1/users/{id}'){
            $datas['@context'] = "http://www.schema.org";
            $datas['@type'] =  $og_type;
            $datas['name'] = $meta_keywords;
            $datas['image'] = $og_image;
            $datas['url'] = $og_url;
            $datas['jobTitle'] = $jobTitle;
            $datas['telephone'] = $mobile;
        }
        if ($api_url == '/api/v1/pages/{id}'){
            $datas['@context'] = "http://www.schema.org";
            $datas['@type'] =  $og_type;
            $datas['actor'] = $actor;
            $datas['object'] = $object;
            $datas['published'] = $published;
        }
        if ($api_url == ''){
            $datas['@context'] = "http://www.schema.org";
            $datas['@type'] =  $og_type;
            $datas['image'] = $og_image;
            $datas['jobTitle'] = $title;
            $datas['url'] = $og_url;
        }
   ?>
   <script type = "application/ld+json">
        <?php
           echo json_encode($datas, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        ?>
    </script>
</head>
<body>
</body>
</html>