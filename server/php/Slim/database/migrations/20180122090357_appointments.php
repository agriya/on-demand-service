<?php

use \Db\Migration\Migration;

class Appointments extends Migration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()     
    {
        $exists = $this->hasTable('appointments');
        if (!$exists) {
            $appointments = $this->table('appointments');
            $appointments->addColumn('created_at', 'datetime')
                ->addColumn('updated_at', 'datetime')        
                ->addColumn('user_id', 'integer',['limit' => 20, 'null' => true])        
                ->addColumn('provider_user_id', 'integer',['limit' => 20, 'null' => true])        
                ->addColumn('appointment_status_id', 'integer',['limit' => 20, 'default' => 1])        
                ->addColumn('appointment_from_date', 'date',['null' => true])        
                ->addColumn('appointment_to_date', 'date',['null' => true])        
                ->addColumn('appointment_from_time', 'string',['null' => true])        
                ->addColumn('appointment_to_time', 'string',['null' => true])        
                ->addColumn('customer_note', 'text',['null' => true])        
                ->addColumn('provider_note', 'text',['null' => true])        
                ->addColumn('total_booking_amount', 'decimal',['default' => 0])        
                ->addColumn('no_of_days', 'integer',['default' => 0])        
                ->addColumn('site_commission_from_freelancer', 'decimal',['precision' => 10, 'scale' =>2,'default' => '0.00'])        
                ->addColumn('paid_escrow_amount_at', 'datetime',['null' => true])        
                ->addColumn('used_affiliate_amount', 'decimal',['precision' => 10, 'scale' =>2,'default' => '0.00'])        
                ->addColumn('payment_gateway_id', 'integer',['limit' => 20, 'null' => true])   
                ->addColumn('paypal_status', 'string',['limit' => 255, 'null' => true])   
                ->addColumn('authorization_id', 'string',['limit' => 255, 'null' => true])
                ->addColumn('capture_id', 'string',['null' => true])
                ->addColumn('payment_type', 'string',['null' => true, 'default' => 'authorize', 'comment' => 'sale/authorize'])
                ->addColumn('service_id', 'integer',['limit' => 20,'null' => true])
                ->addColumn('cancellation_policy_id', 'integer',['limit' => 20,'null' => true])
                ->addColumn('refunded_amount', 'decimal',['default' => 0])
                ->addColumn('first_response_time', 'biginteger',['limit' => 20,'default' => 0,'null' => true, 'comment' => 'In minutes'])
                ->addColumn('booked_minutes', 'biginteger',['default' => 0])
                ->addColumn('work_location_address', 'string',['null' => true])
                ->addColumn('work_location_address1', 'string',['null' => true])
                ->addColumn('work_location_city_id', 'biginteger',['null' => true])
                ->addColumn('work_location_state_id', 'biginteger',['null' => true])
                ->addColumn('work_location_country_id', 'biginteger',['null' => true])
                ->addColumn('work_location_postal_code', 'string',['limit' => 20,'null' => true])
                ->addColumn('number_of_item', 'biginteger',['limit' => 20,'default' => 1, 'comment' => 'Like number of children or dogs'])
                ->addColumn('user_credit_card_id', 'integer',['limit' => 20,'null' => true])
                ->addColumn('site_commission_from_customer', 'decimal',['precision' => 10, 'scale' =>2,'default' => '0.00'])
                ->addColumn('is_review_posted', 'boolean',['default' => 0])
                ->addColumn('review_reminder_notification_sent_count', 'boolean',['default' => 0])
                ->addIndex('user_id')
                ->addIndex('provider_user_id')
                ->addIndex('appointment_status_id')
                ->addIndex('service_id')
                ->addIndex('cancellation_policy_id')
                ->addIndex('payment_gateway_id')
                ->addIndex('user_credit_card_id')
                ->addForeignKey('service_id', 'services', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])                       
                ->addForeignKey('cancellation_policy_id', 'cancellation_policies', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])                       
                ->addForeignKey('payment_gateway_id', 'payment_gateways', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])                       
                ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])                       
                ->addForeignKey('provider_user_id', 'users', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])                       
                ->addForeignKey('user_credit_card_id', 'user_credit_cards', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])                       
                ->save();
        }
    }
    /**
     * Migrate Down.
     */
    public function down()
    {
        $exists = $this->hasTable('appointments');
        if ($exists) {
            $this->dropTable('appointments');
        }
    }
}
