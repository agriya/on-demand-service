<?php

use Phinx\Seed\AbstractSeed;

class ProvidersSeeder extends AbstractSeed
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
                'name' => 'Twitter',
                'slug' => 'twitter',
                'secret_key' => 'DdWvly6bSk05rsnTSKRzVBaBUJb9FjXEeIoP3gKaHRFDTjIQf4',
                'api_key' => 'wNTee1LnJu83Fzu6KThFR46pL',
                'display_order' => 1,
                'is_active' => 1
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Facebook',
                'slug' => 'facebook',
                'secret_key' => 'c04bc4fa02998d70823dec0d78e9ff11',
                'api_key' => '759710867554541',
                'display_order' => 2,
                'is_active' => 1
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Google',
                'slug' => 'google',
                'secret_key' => 'xC-mA9t1gTWCsR6Pleyeswjk',
                'api_key' => '374943874821-k50vr4nm336gn1fc2vjeus703hr9v776.apps.googleusercontent.com',
                'display_order' => 3,
                'is_active' => 1
            ]            
        ];

        $roles = $this->table('providers');
        $roles->insert($data)
              ->save();
    }
}
