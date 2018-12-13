<?php

use Phinx\Seed\AbstractSeed;

class FaqsSeeder extends AbstractSeed
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
                'faq_question' => 'What is a Ondemandservicemarketplace.com?',
                'faq_answer' => 'A Ondemandservicemarketplace.com is a dog sitter - they love dogs, caring for them in their own home as if they were part of their own family. Each of our reliable assistants has been vetted by us and reviewed by other assistant.'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'faq_question' => 'How does it work?',
                'faq_answer' => 'We provide an easy way to find and book a reliable and caring dog sitter in your local area, in a few simple steps online. For added peace of mind all bookings are covered by vet and public liability insurance as well as 24/7 emergency veterinary support.'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'faq_question' => 'What services are available?',
                'faq_answer' => 'You can choose from \'Dog Boarding\' (24 hours - day and night - in the assistant\'s home), \'Dog Day Care\' (during the day only), and \'Dog Walking\'. Our dog sitters may offer all, or some of these services, as displayed on their listing.'
            ] ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'faq_question' => 'How do I book?',
                'faq_answer' => 'After you send a reservation request your assistant will have 24 hours to confirm or decline. You will only be charged when your booking is confirmed.'
            ]  ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'faq_question' => 'Can I meet the assistant before the booking starts?',
                'faq_answer' => 'Of course! After your booking is confirmed, you can arrange a \'meet & greet\' - a chance for your dog and their new assistant to get to know each other before the booking begins.'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'faq_question' => 'Can I cancel my booking?',
                'faq_answer' => 'You can cancel your booking at any time. If your booking hasn\'t been confirmed you\'ll receive a full refund. If your booking has already been confirmed, the refund will depend on your assistant\'s cancellation policy as shown on their listing.'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'faq_question' => 'How do I know if my dog is safe?',
                'faq_answer' => 'Your dog\'s safety is our highest priority. You\'ll receive regular photo updates of your happy hound while your dog is with their assistant. Plus, for any unforeseen circumstances all bookings made with ondemandservicemarketplace.com include free, comprehensive vet and public liability insurance, and 24/7 emergency veterinary support.'
            ]     
        ];

        $roles = $this->table('faqs');
        $roles->insert($data)
              ->save();
    }
}
