<?php

use Phinx\Seed\AbstractSeed;

class CancellationPoliciesSeeder extends AbstractSeed
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
                'name' => 'Flexible',
                'description' => 'Full refund up to a day before service starts',
                'days_before' => 1,
                'days_before_refund_percentage' => 100,
                'days_after' => 1,
                'days_after_refund_percentage' => 0
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Moderate',
                'description' => 'Full refund with 5 days notice, 50% refund thereafter.',
                'days_before' => 5,
                'days_before_refund_percentage' => 100,
                'days_after' => 5,
                'days_after_refund_percentage' => 50
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Strict',
                'description' => '50% refund with 7 days notice, no refund thereafter.',
                'days_before' => 7,
                'days_before_refund_percentage' => 50,
                'days_after' => 7,
                'days_after_refund_percentage' => 0
            ]      
        ];

        $roles = $this->table('cancellation_policies');
        $roles->insert($data)
              ->save();
    }
}
