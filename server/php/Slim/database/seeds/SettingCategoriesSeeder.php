<?php

use Phinx\Seed\AbstractSeed;

class SettingCategoriesSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $json_string = '[{"created_at":"2016-05-13 11:08:13","updated_at":"2016-05-13 11:08:13","name":"System","description":"Manage site name, currency details, language details, email controls.","display_order":"1","is_active":"1","plugin":""},{"created_at":"2016-05-13 11:08:13","updated_at":"2016-05-13 11:08:13","name":"SEO","description":"Manage content, meta data and other information relevant to browsers or search engines","display_order":"2","is_active":"1","plugin":""},{"created_at":"2016-05-13 11:08:13","updated_at":"2016-05-13 11:08:13","name":"Follow Us","description":"manage site\'s social network links. Enter full URL, Leave it blank if not available.","display_order":"4","is_active":"1","plugin":""},{"created_at":"2016-05-13 11:08:13","updated_at":"2016-05-13 11:08:13","name":"Account","description":"Manage account settings such as admin approval, email verification, and other site account settings.","display_order":"5","is_active":"1","plugin":""},{"created_at":"2016-05-13 11:08:13","updated_at":"2016-05-13 11:08:13","name":"Withdrawal","description":"Manage cash withdraw settings for a user such as enabling withdrawal and setting withdraw limit.","display_order":"7","is_active":"1","plugin":""},{"created_at":"2016-05-13 11:08:13","updated_at":"2016-05-13 11:08:13","name":"Mobile","description":"Manage all App Store ID.","display_order":"9","is_active":"1","plugin":"Mobile\/Mobile"},{"created_at":"2016-05-13 11:08:13","updated_at":"2016-05-13 11:08:13","name":"Captcha","description":"Captchas are meant to protect your website from spam and abuse.","display_order":"10","is_active":"1","plugin":"Captcha"},{"created_at":"2016-05-13 11:08:13","updated_at":"2016-05-13 11:08:13","name":"Banner","description":"Banner for all page bottom, all page top, profile page sidebar.","display_order":"11","is_active":"1","plugin":"Banner"},{"created_at":"2016-06-09 11:08:13","updated_at":"2016-06-09 11:08:13","name":"SMS","description":"Manage SMS Gateway settings","display_order":"16","is_active":"1","plugin":"SMS\/SMS"},{"created_at":"2016-06-10 11:08:13","updated_at":"2016-06-10 11:08:13","name":"Timeformat","description":"Manage Site Time Format here 12 Hrs or 24 Hrs","display_order":"17","is_active":"1","plugin":"Timeformat"},{"created_at":"2016-06-10 13:08:13","updated_at":"2016-06-10 11:08:13","name":"Timezone","description":"For Mange Site Time Zone Here.","display_order":"18","is_active":"1","plugin":"Timezone"},{"created_at":"2016-06-10 13:08:13","updated_at":"2016-06-10 11:08:13","name":"Push Notification Settings","description":"For Manage Android and IOS Push Notification Settings","display_order":"21","is_active":"1","plugin":""},{"created_at":"2017-10-13 15:58:09","updated_at":"2017-10-13 15:58:09","name":"Booking","description":"Manage Booking related settings.","display_order":"23","is_active":"1","plugin":""},{"created_at":"2017-10-13 15:58:09","updated_at":"2017-10-13 15:58:09","name":"Affiliates","description":"Manage Affiliates related settings.","display_order":"24","is_active":"1","plugin":"Referral\/Referral"},{"created_at":"2018-01-09 13:18:09","updated_at":"2018-01-09 13:18:09","name":"ProUser","description":"Manage ProUser related settings.","display_order":"25","is_active":"1","plugin":"ProUser\/ProUser"},{"created_at":"2018-01-09 13:18:09","updated_at":"2018-01-09 13:18:09","name":"TopUser","description":"Manage TopUser related settings.","display_order":"25","is_active":"1","plugin":"TopUser\/TopUser"}]';

        $data = json_decode($json_string, true);
        for($i=0; $i < count($data); $i++){
            $data[$i]['created_at'] = date('Y-m-d H:i:s');
            $data[$i]['updated_at'] = date('Y-m-d H:i:s');
        }
        $posts = $this->table('setting_categories');
        $posts->insert($data)
              ->save();
    }
}
