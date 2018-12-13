<?php

use Phinx\Seed\AbstractSeed;

class QuizzesSeeder extends AbstractSeed
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
                'name' => 'Getting Bookings',
                'description' => 'ondemandservicemarketplace.com is the leading assistant market platform in Europe, the US and the Carribean. Our website and apps are used everyday by thousands of costomers looking for assistants just like you.
With the help of ondemandservicemarketplace.com, you have an exciting opportunity to regularly work on wide range of private yachts, sailboats and mega yachts and to build lasting relationships with their owners and crew.
After we approve your listing, captains and costomers in your area will start contacting you. You\'ll be notified by email, SMS or push notifications if you\'ve downloaded our free iPhone or Android apps. Owners and captains can contact you in two different ways:
Enquiries: The owner / captian hasn\'t paid yet, but has specified some dates and included a personal message with questions. They need you to \'Pre-approve\' their request (and answer any questions) so they can then pay and confirm.
Direct Bookings: The owner / captain has already pre-paid and is waiting for you to \'Accept\' so that the booking is then confirmed.
The key to getting more bookings is responding and confirming quickly, as well as keeping your calendar up to date each week. How quickly you respond to messages (both Enquiries and Direct Bookings) will determine your response rate and your response time. These are both displayed on your listing when owners search for a assistant.
Your response rate is the percentage of all enquiries and direct bookings you receive that you respond to. If this falls below 80% your listing will be hidden from local search results.
Your response time is the average time it takes you to send an initial response to an owner. Registered assistants are required to respond to new requests within four hours, letting the owner know whether they will accept or decline a booking. Remember, most owners contact a few assistants at the same time – the sooner you reply, the more likely you are to win the booking.',
                'display_order' => 1
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Once you get a confirmed booking',
                'description' => 'Payment We transfer payment directly into your bank account after your work is complete (typically within three working days after the performance of the booking). Please note: accepting cash payments is strictly prohibited. Assistant only works with secure online payments - any assistants suspected of accepting cash payments will be immediately and permanently removed from ondemandservicemarketplace.com.
Service fee for assistants We apply a sitter service fee on the value of the booking before processing the payment. Take a minute now to read this quick article about fees to learn a little more about the fees in your country.
Peace of mind All bookings made and confirmed through ondemandservicemarketplace.com are covered by our comprehensive public liability insurance.
Medivac We also offer 24/7 emergency support for assistants to make sure you have all the help you need in the event of an accident during a booking made through ondemandservicemarketplace.com.',
                'display_order' => 2
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Becoming a great assistant',
                'description' => 'All of the best assistants have these things in common. They all:
Communicate quickly and effectively
Impress yacht captains and owners through the quality of their work
Build long lasting relationships
Yacht captains and owners expect prompt responses to booking requests. The easiest way to do this on the go is by using our iPhone or Android mobile apps on your smartphone.
Using our mobile apps you can:
Get instantly notified about new booking requests and respond instantly
Manage your bookings and calendar, quickly and conveniently
Keep all the information about the yacht handy at all times
If you provide a great experience, we do the rest.
The best assistants on ondemandservicemarketplace.com rank higher in local search results and get more bookings depending on:
Positive reviews The more five-star reviews you get, the better your listing will rank.
Number of bookings The more bookings you complete, the higher your listing will rank.
Number of re-bookings Yacht captains / owners re-booking with you shows that you provide a great service - this is the best way to improve your ranking.
Response time Quickly responding to booking requests will boost your ranking (our best assistants respond within minutes!). We ask that all assistants respond within a maximum of four hours of the booking request being sent by a yacht captain / owner.
Response rate Answering all messages is necessary to avoid a low ranking. We regularly conduct listing reviews – assistants with a response rate of less than 80% will have their listings hidden from local search results.
Confirmation rate Accepting a high percentage of requests will improve your ranking. Make sure to keep your calendar up to date (we recommend updating it at least once a week) to get requests only when you\'re available. Also, make sure to only choose the services you offer when signing up and to clearly highlight in your listing description any special services you can (or cannot) offer.
Never accepting bookings in cash In fact accepting or encouraging bookings outside of ondemandservicemarketplace.com is a serious violation of our terms and conditions. Sitters who are suspected of taking bookings offline will be immediately deactivated.',
                'display_order' => 3
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Cancellations',
                'description' => 'Yacht captains and owners’ plans or personal schedules may change and as a result, they may choose to cancel a booking after it has been confirmed. To help you deal with these cancellations more effectively, ondemandservicemarketplace.com lets you choose a cancellation policy.
There are three cancellation policies that you can choose:
Flexible Full refund for the yacht captain / owner up to a day before service starts (11:59pm the day before the booking start date)
Moderate Full refund for the yacht captain / owner with five days notice, 50% refund thereafter (e.g. if the booking start date is Friday, the owner can cancel with a full refund up to 11:59pm the Saturday before)
Strict 50% refund for the costomers within seven days notice, no refund thereafter (e.g. if the booking start date is Friday, the costomers can cancel with a 50% refund up to 11:59pm the Thursday before)
For overnight (24hr) bookings, cancellation policies are always set to Strict by default. These policies are in place to compensate you for any opportunities lost as a result of the cancelled booking. Sometimes, you may choose to waive the cancellation penalty with the owner, and for those special cases you can contact us to arrange a full or partial refund.
Assistant also has to make sure that costomers feel secure after they have confirmed their booking with a assistant, and they\'re not left looking for a assistant the day before they arrive in a marina or before a busy week onboard their yacht. For this, assistants cannot directly cancel confirmed bookings. Instead, they must contact the ondemandservicemarketplace.com Support Team. We know that sometimes things pop up, but please keep in mind that last-minute cancellations from a assistant without adequate cause will result in the permanent deactivation of the assistant\'s listing from ondemandservicemarketplace.com.',
                'display_order' => 4
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Being part of our crew',
                'description' => 'YSafe online bookings and payment guarantee Yacht captains / owners pay for their bookings upfront so you never have to chase them for a payment after a booking. Your payment is automatically paid directly into your bank account after the booking is complete.
Promoting your listing Once your listing is live, we will promote it in a number of ways, through a variety of marketing campaigns, to help you get more bookings. You\'ll also get your own dedicated link so you can share it with Yacht captains / owners you meet in your everyday life, and we\'ll reward you $10 for every new yacht captain / owner you introduce to ondemandservicemarketplace.com, through our assistant referral program. On your third completed booking, we will also send you personalized ondemandservicemarketplace.com business cards, so you can promote your listing with Yacht captains / owners you meet in your local area.
Visibility in search Engines We work hard to make sure that when yacht captains / owners search for local assistants on Google, the first results they find are ondemandservicemarketplace.com’s. Through ondemandservicemarketplace.com you will get unprecedented exposure to potential customers. The more you use ondemandservicemarketplace.com and the more repeat customers who book with you, the higher you will rank in our local search results too.
Keep all bookings online Make sure all new and returning bookings are made and paid through ondemandservicemarketplace.com – never accept any cash payments.
Follow local regulations Make sure you always uphold the highest level of quality before, during and after a booking and check with your local authority regarding any self employed regulations.
Important Only bookings paid via ondemandservicemarketplace.com.com are covered by our public liability (up to $TO BE CONFIRMED) and medi-vac insurance. Accepting cash payments is a serious violation of our terms of service and will result in immediate and permanent removal from ondemandservicemarketplace.com as well as a fine of $500.',
                'display_order' => 5
            ]             
        ];

        $roles = $this->table('quizzes');
        $roles->insert($data)
              ->save();
    }
}
