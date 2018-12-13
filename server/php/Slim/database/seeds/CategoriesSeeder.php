<?php

use Phinx\Seed\AbstractSeed;

class CategoriesSeeder extends AbstractSeed
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
        $data = [
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Dog Care',
                'is_enable_multiple_booking' => 0,
                'service_count' => 3,
                'is_active' => 1,
                'is_enable_common_hourly_rate_for_all_sub_services' => 1,
                'is_featured' => 1
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Baby and Elder Care',
                'is_enable_multiple_booking' => 0,
                'service_count' => 4,
                'is_active' => 1,
                'is_enable_common_hourly_rate_for_all_sub_services' => 1,
                'is_featured' => 1
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Worker and Assistant',
                'is_enable_multiple_booking' => 0,
                'service_count' => 5,
                'is_active' => 1,
                'is_enable_common_hourly_rate_for_all_sub_services' => 0,
                'is_featured' => 0
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Health Care and Wellness',
                'is_enable_multiple_booking' => 0,
                'service_count' => 5,
                'is_active' => 1,
                'is_enable_common_hourly_rate_for_all_sub_services' => 1,
                'is_featured' => 0
            ]        
        ];

        $roles = $this->table('categories');
        $roles->insert($data)
              ->save();
    }
}
