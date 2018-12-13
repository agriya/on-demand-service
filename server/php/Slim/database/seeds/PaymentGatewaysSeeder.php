<?php

use Phinx\Seed\AbstractSeed;

class PaymentGatewaysSeeder extends AbstractSeed
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
                'name' => 'PayPal Standard',
                'display_name' => 'PayPal Standard',
                'description' => 'If enabled, you\'ll need to enable "Wallet" option to aggregate users\' amount.',
                'is_test_mode' => 1,
                'is_active' => 1
            ]
        ];

        $roles = $this->table('payment_gateways');
        $roles->insert($data)
              ->save();
    }
}
