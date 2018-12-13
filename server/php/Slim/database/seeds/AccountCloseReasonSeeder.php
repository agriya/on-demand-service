<?php

use Phinx\Seed\AbstractSeed;

class AccountCloseReasonSeeder extends AbstractSeed
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
                'reasons' => 'I don\'t work as a Ondemandservicemarketplace.com anymore',
                'display_order' => 1
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'reasons' => 'My experience with the customer was not great.',
                'display_order' => 2
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'reasons' => 'My experience with the Ondemandservicemarketplace.com team was not great',
                'display_order' => 3
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'reasons' => 'My experience with the customers and the Ondemandservicemarketplace.com team was not great',
                'display_order' => 4
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'reasons' => 'Other',
                'display_order' => 5
            ]        
        ];

        $roles = $this->table('account_close_reasons');
        $roles->insert($data)
              ->save();
    }
}
