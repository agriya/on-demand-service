<?php

use Phinx\Seed\AbstractSeed;

class StatesSeeder extends AbstractSeed
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
        $json_string = '[{"created_at":"2017-12-08 12:38:47","updated_at":"2017-12-08 12:38:47","name":"Sachsen","country_id":"83","is_active":"0"},{"created_at":"2017-12-08 13:08:23","updated_at":"2017-12-08 13:08:23","name":"Bavaria","country_id":"83","is_active":"0"},{"created_at":"2017-12-08 13:14:51","updated_at":"2017-12-08 13:14:51","name":"Lazio","country_id":"109","is_active":"0"},{"created_at":"2017-12-08 13:17:46","updated_at":"2017-12-08 13:17:46","name":"England","country_id":"239","is_active":"0"},{"created_at":"2017-12-08 13:20:29","updated_at":"2017-12-08 13:20:29","name":"Community of Madrid","country_id":"213","is_active":"0"},{"created_at":"2017-12-11 06:48:17","updated_at":"2017-12-11 06:48:17","name":"Castilla y Le\u00f3n","country_id":"213","is_active":"0"},{"created_at":"2017-12-16 11:42:13","updated_at":"2017-12-16 11:42:13","name":"M\u00f8re og Romsdal","country_id":"167","is_active":"0"},{"created_at":"2017-12-16 11:51:16","updated_at":"2017-12-16 11:51:16","name":"Sogn og Fjordane","country_id":"167","is_active":"0"},{"created_at":"2017-12-21 12:45:31","updated_at":"2017-12-21 12:45:31","name":"Tamil Nadu","country_id":"102","is_active":"0"},{"created_at":"2017-12-22 06:37:32","updated_at":"2017-12-22 06:37:32","name":"Andhra Pradesh","country_id":"102","is_active":"0"},{"created_at":"2017-12-22 08:10:42","updated_at":"2017-12-22 08:10:42","name":"Lovec","country_id":"36","is_active":"0"},{"created_at":"2017-12-26 11:10:01","updated_at":"2017-12-26 11:10:01","name":"Oslo","country_id":"167","is_active":"0"},{"created_at":"2017-12-28 06:38:01","updated_at":"2017-12-28 06:38:01","name":"Berlin","country_id":"83","is_active":"0"},{"created_at":"2017-12-30 15:00:40","updated_at":"2017-12-30 15:00:40","name":"New South Wales","country_id":"14","is_active":"0"},{"created_at":"2017-12-30 15:01:56","updated_at":"2017-12-30 15:01:56","name":"Victoria","country_id":"14","is_active":"0"},{"created_at":"2017-12-30 15:02:59","updated_at":"2017-12-30 15:02:59","name":"Tokyo","country_id":"112","is_active":"0"},{"created_at":"2017-12-30 15:05:58","updated_at":"2017-12-30 15:05:58","name":"Kanagawa Prefecture","country_id":"112","is_active":"0"}]';

        $data = json_decode($json_string, true);
        for($i=0; $i < count($data); $i++){
            $data[$i]['created_at'] = date('Y-m-d H:i:s');
            $data[$i]['updated_at'] = date('Y-m-d H:i:s');
        }
        $posts = $this->table('states');
        $posts->insert($data)
              ->save();
    }
}
