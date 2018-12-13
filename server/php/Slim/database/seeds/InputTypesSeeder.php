<?php

use Phinx\Seed\AbstractSeed;

class InputTypesSeeder extends AbstractSeed
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
                'name' => 'Single Line of Text',
                'value' => 'textInput'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Multiple Lines of Text',
                'value' => 'textArea'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Select Box',
                'value' => 'select'
            ] ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Check Boxes',
                'value' => 'checkbox'
            ]  ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Radio Buttons',
                'value' => 'radio'
            ]
        ];

        $roles = $this->table('input_types');
        $roles->insert($data)
              ->save();
    }
}
