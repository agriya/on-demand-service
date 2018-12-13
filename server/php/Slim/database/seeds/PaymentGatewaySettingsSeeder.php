<?php

use Phinx\Seed\AbstractSeed;

class PaymentGatewaySettingsSeeder extends AbstractSeed
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
                'payment_gateway_id' => 1,
                'name' => 'paypal_client_Secret',
                'type' => 'text',
                'test_mode_value' => 'EJXe_BG9vLowm7ZO1Pv8ZSvM9TB1-2yVxzeBGa7VF8TQiXnHmoRpCX0a32DPkdWfZgLMA04YgkD6Miyw',
                'label' => 'Client Secret',
                'description' => 'Client Secret'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'payment_gateway_id' => 1,
                'name' => 'paypal_client_id',
                'type' => 'text',
                'test_mode_value' => 'AV2XE66Q9HyDT_S2RxBU95oWhhUQcXrwIW_s4hrQbhuTpenUPf54MYQBRO13KFpXyOHQmfNFb2rKHwRr',
                'label' => 'Client ID',
                'description' => 'Client ID'
            ]            
        ];

        $roles = $this->table('payment_gateway_settings');
        $roles->insert($data)
              ->save();
    }
}
