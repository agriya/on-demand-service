<?php

use Phinx\Seed\AbstractSeed;

class FormFieldGroupsSeeder extends AbstractSeed
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
        $json_string = '[{"created_at":"2017-12-15 14:03:37","updated_at":"2017-12-15 14:03:37","name":"Background","slug":"background","foreign_id":"2","class":"Category","is_deletable":"1","is_editable":"1","is_belongs_to_service_provider":"1"},{"created_at":"2017-12-15 14:16:18","updated_at":"2017-12-15 14:16:18","name":"Background","slug":"background","foreign_id":"1","class":"Category","is_deletable":"1","is_editable":"1","is_belongs_to_service_provider":"1"},{"created_at":"2017-12-15 14:16:18","updated_at":"2017-12-15 14:16:18","name":"Transportation","slug":"transportation","foreign_id":"1","class":"Category","is_deletable":"1","is_editable":"1","is_belongs_to_service_provider":"1"},{"created_at":"2017-12-15 14:31:14","updated_at":"2017-12-15 14:31:14","name":"Experience","slug":"experience","foreign_id":"7","class":"Service","is_deletable":"1","is_editable":"1","is_belongs_to_service_provider":"1"},{"created_at":"2017-12-18 11:27:15","updated_at":"2017-12-18 11:27:15","name":"About Child","slug":"about-child","foreign_id":"7","class":"Service","is_deletable":"1","is_editable":"1","is_belongs_to_service_provider":"0"},{"created_at":"2017-12-26 10:55:24","updated_at":"2017-12-26 10:55:24","name":"Additional Information","slug":"additional-information","foreign_id":"3","class":"Category","is_deletable":"1","is_editable":"1","is_belongs_to_service_provider":"1"},{"created_at":"2017-12-27 15:22:13","updated_at":"2017-12-27 15:22:13","name":"Additional Information","slug":"additional-information","foreign_id":"10","class":"Service","is_deletable":"1","is_editable":"1","is_belongs_to_service_provider":"1"},{"created_at":"2017-12-27 15:26:28","updated_at":"2017-12-27 15:26:28","name":"Additional Information","slug":"additional-information","foreign_id":"4","class":"Category","is_deletable":"1","is_editable":"1","is_belongs_to_service_provider":"1"},{"created_at":"2017-12-27 15:43:31","updated_at":"2017-12-27 15:43:31","name":"Additional Information","slug":"additional-information","foreign_id":"5","class":"Category","is_deletable":"1","is_editable":"1","is_belongs_to_service_provider":"1"},{"created_at":"2017-12-27 16:18:14","updated_at":"2017-12-27 16:18:14","name":"Add household details","slug":"add-household-details","foreign_id":"1","class":"Category","is_deletable":"1","is_editable":"1","is_belongs_to_service_provider":"1"},{"created_at":"2017-12-27 16:18:14","updated_at":"2017-12-27 16:18:14","name":"Add additional information","slug":"add-additional-information","foreign_id":"1","class":"Category","is_deletable":"1","is_editable":"1","is_belongs_to_service_provider":"1"},{"created_at":"2017-12-29 08:04:04","updated_at":"2017-12-29 08:04:04","name":"Other Information","slug":"other-information","foreign_id":"15","class":"Service","is_deletable":"1","is_editable":"1","is_belongs_to_service_provider":"1"},{"created_at":"2017-12-29 08:12:16","updated_at":"2017-12-29 08:12:16","name":"Other Information","slug":"other-information","foreign_id":"16","class":"Service","is_deletable":"1","is_editable":"1","is_belongs_to_service_provider":"1"}]';

        $data = json_decode($json_string, true);
        for($i=0; $i < count($data); $i++){
            $data[$i]['created_at'] = date('Y-m-d H:i:s');
            $data[$i]['updated_at'] = date('Y-m-d H:i:s');
        }
        $posts = $this->table('form_field_groups');
        $posts->insert($data)
              ->save();
    }
}
