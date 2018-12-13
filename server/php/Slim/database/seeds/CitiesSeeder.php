<?php

use Phinx\Seed\AbstractSeed;

class CitiesSeeder extends AbstractSeed
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
        $json_string = '[{"created_at":"2017-12-08 12:38:47","updated_at":"2017-12-08 12:38:47","country_id":"83","state_id":"1","name":"Chemnitz","is_active":"0"},{"created_at":"2017-12-08 13:08:23","updated_at":"2017-12-08 13:08:23","country_id":"83","state_id":"2","name":"Middle Franconia","is_active":"0"},{"created_at":"2017-12-08 13:14:52","updated_at":"2017-12-08 13:14:52","country_id":"109","state_id":"3","name":"Citt\u00e0 Metropolitana di Roma","is_active":"0"},{"created_at":"2017-12-08 13:17:46","updated_at":"2017-12-08 13:17:46","country_id":"239","state_id":"4","name":"Greater London","is_active":"0"},{"created_at":"2017-12-08 13:20:29","updated_at":"2017-12-08 13:20:29","country_id":"213","state_id":"5","name":"Madrid","is_active":"0"},{"created_at":"2017-12-11 06:48:17","updated_at":"2017-12-11 06:48:17","country_id":"213","state_id":"6","name":"Soria","is_active":"0"},{"created_at":"2017-12-16 11:42:13","updated_at":"2017-12-16 11:42:13","country_id":"167","state_id":"7","name":"Alesund","is_active":"0"},{"created_at":"2017-12-16 11:51:16","updated_at":"2017-12-16 11:51:16","country_id":"167","state_id":"8","name":"Fl\u00e5m","is_active":"0"},{"created_at":"2017-12-21 12:45:31","updated_at":"2017-12-21 12:45:31","country_id":"102","state_id":"9","name":"Chennai","is_active":"0"},{"created_at":"2017-12-22 06:37:32","updated_at":"2017-12-22 06:37:32","country_id":"102","state_id":"10","name":"Vishakhapatnam","is_active":"0"},{"created_at":"2017-12-22 08:10:42","updated_at":"2017-12-22 08:10:42","country_id":"36","state_id":"11","name":"Velchevo, Lovech Province","is_active":"0"},{"created_at":"2017-12-26 11:10:01","updated_at":"2017-12-26 11:10:01","country_id":"167","state_id":"12","name":"Oslo","is_active":"0"},{"created_at":"2017-12-28 06:38:01","updated_at":"2017-12-28 06:38:01","country_id":"83","state_id":"13","name":"Berlin","is_active":"0"},{"created_at":"2017-12-29 07:25:42","updated_at":"2017-12-29 07:25:42","country_id":"239","state_id":"4","name":"Greater Manchester","is_active":"0"},{"created_at":"2017-12-29 09:32:26","updated_at":"2017-12-29 09:32:26","country_id":"239","state_id":"4","name":"Cambridgeshire","is_active":"0"},{"created_at":"2017-12-29 10:29:07","updated_at":"2017-12-29 10:29:07","country_id":"239","state_id":"4","name":"Hartford County","is_active":"0"},{"created_at":"2017-12-30 15:00:40","updated_at":"2017-12-30 15:00:40","country_id":"14","state_id":"14","name":"Sydney","is_active":"0"},{"created_at":"2017-12-30 15:01:56","updated_at":"2017-12-30 15:01:56","country_id":"14","state_id":"15","name":"Melbourne","is_active":"0"},{"created_at":"2017-12-30 15:02:59","updated_at":"2017-12-30 15:02:59","country_id":"112","state_id":"16","name":"Melbourne","is_active":"0"},{"created_at":"2017-12-30 15:05:58","updated_at":"2017-12-30 15:05:58","country_id":"112","state_id":"17","name":"Yokohama","is_active":"0"},{"created_at":"2018-01-02 11:53:15","updated_at":"2018-01-02 11:53:15","country_id":"14","state_id":"14","name":"Council of the City of Sydney","is_active":"0"}]';

        $data = json_decode($json_string, true);
        for($i=0; $i < count($data); $i++){
            $data[$i]['created_at'] = date('Y-m-d H:i:s');
            $data[$i]['updated_at'] = date('Y-m-d H:i:s');
        }
        $posts = $this->table('cities');
        $posts->insert($data)
              ->save();
    }
}
