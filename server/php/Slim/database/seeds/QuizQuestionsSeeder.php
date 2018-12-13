<?php

use Phinx\Seed\AbstractSeed;

class QuizQuestionsSeeder extends AbstractSeed
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
                'quiz_id' => 1,
                'question' => 'What does receiving a direct booking mean?'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'quiz_id' => 1,
                'question' => 'What should you do if you receive an enquiry?'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'quiz_id' => 2,
                'question' => 'When and how do you get paid?'
            ] ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'quiz_id' => 2,
                'question' => 'What should you do if an owner / captain offers to pay you in cash?'
            ]  ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'quiz_id' => 3,
                'question' => 'How can you improve the ranking of your ondemandservicemarketplace.com listing?'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'quiz_id' => 3,
                'question' => 'Why should you update your calendar often?'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'quiz_id' => 4,
                'question' => 'You have a very important family emergency and have to cancel a confirmed booking. What do you do?'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'quiz_id' => 4,
                'question' => 'You\'ve had a 10-day booking confirmed for a few weeks now, but the captain / owner has changed their mind 3 days before the booking start date. You have a Moderate cancellation policy. How much will the owner be refunded?'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'quiz_id' => 5,
                'question' => 'Why is it important to ONLY accept bookings through ondemandservicemarketplace.com?'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'quiz_id' => 5,
                'question' => 'How will my listing be promoted?'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'quiz_id' => 5,
                'question' => 'Why do you want to be a assistant?'
            ]     
        ];

        $roles = $this->table('quiz_questions');
        $roles->insert($data)
              ->save();
    }
}
