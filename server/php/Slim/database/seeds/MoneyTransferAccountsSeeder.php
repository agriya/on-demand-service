<?php

use Phinx\Seed\AbstractSeed;

class MoneyTransferAccountsSeeder extends AbstractSeed
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
                'user_id' => 7,
                'account' => 'Bank: HDFC, Account No: 453321133400, IFSC Code: CDF5002',
                'is_primary' => 0
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'user_id' => 3,
                'account' => 'Bank: HDFC Account No: 1234-3456-124-4321, IFSC Code: HDFC345673',
                'is_primary' => 0
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'user_id' => 3,
                'account' => 'PayPal: example@gmail.com',
                'is_primary' => 0
            ] ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'user_id' => 8,
                'account' => 'Bank: SCB Account No: 45332113340023, IFSC Code: SCB5007',
                'is_primary' => 0
            ] ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'user_id' => 22,
                'account' => 'PayPal Account: example@example.com',
                'is_primary' => 0
            ] ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'user_id' => 16,
                'account' => 'Bank: HDFC Account No: 1234-3256-524-4621, IFSC Code: HDFC345674',
                'is_primary' => 0
            ]             
        ];

        $roles = $this->table('money_transfer_accounts');
        $roles->insert($data)
              ->save();
    }
}
