<?php

use Phinx\Seed\AbstractSeed;

class ReviewsSeeder extends AbstractSeed
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
                'user_id' => 4,
                'to_user_id' => 8,
                'foreign_id' => 19,
                'class' => 'Appointment',
                'rating' => 5,
                'message' => 'I have used Mekides for babysitting many times, she is always my first choice. She is extremely reliable, punctual and friendly. She has a happy and helpful disposition, is calm and understanding and has a genuine love for children. I would not hesitate to recommend her for any child caring role.',
                'is_reviewed_as_service_provider' => 0
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'user_id' => 2,
                'to_user_id' => 18,
                'foreign_id' => 20,
                'class' => 'Appointment',
                'rating' => 5,
                'message' => 'Very good. Thanks to Ana belan for making our dog welcome.',
                'is_reviewed_as_service_provider' => 0
            ]          
        ];

        $roles = $this->table('reviews');
        $roles->insert($data)
              ->save();
    }
}
