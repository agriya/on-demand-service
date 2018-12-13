<?php

use \Db\Migration\Migration;

class Users extends Migration
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
        $exists = $this->hasTable('users');
        if (!$exists) {
            $users = $this->table('users');
            $users->addColumn('created_at', 'timestamp',['null' => true])
                    ->addColumn('updated_at', 'timestamp',['null' => true])     
                    ->addColumn('role_id', 'integer',['limit' => 20, 'null' => true])                
                    ->addColumn('username', 'string',['limit' => 255])
                    ->addColumn('email', 'string',['limit' => 255])
                    ->addColumn('password', 'string',['limit' => 255])
                    ->addColumn('available_wallet_amount', 'decimal',['precision' => 10,'scale' => 2,'default' => '0.00'])
                    ->addColumn('blocked_amount', 'decimal',['precision' => 10,'scale' => 2,'default' => '0.00'])
                    ->addColumn('user_login_count', 'biginteger',['limit' => 20,'default' => 0])
                    ->addColumn('is_agree_terms_conditions', 'boolean',['default' => 0])
                    ->addColumn('is_active', 'boolean',['default' => 0])
                    ->addColumn('is_email_confirmed', 'boolean',['default' => 0])
                    ->addColumn('remember_token', 'string',['limit' => 100, 'null' => true])
                    ->addColumn('activate_hash', 'integer',['null' => true])
                    ->addColumn('address', 'string',['limit' => 255, 'null' => true])
                    ->addColumn('address1', 'string',['limit' => 255, 'null' => true])
                    ->addColumn('city_id', 'integer', ['null' => true])
                    ->addColumn('state_id', 'integer', ['null' => true])
                    ->addColumn('country_id', 'integer', ['null' => true])
                    ->addColumn('referred_by_user_id', 'integer', ['null' => true])
                    ->addColumn('reference_code', 'string',['null' => true])
                    ->addColumn('affiliate_pending_amount', 'decimal',['precision' => 10, 'scale' => 2,'default' => '0.00'])
                    ->addColumn('affiliate_paid_amount', 'decimal',['precision' => 10, 'scale' => 2,'default' => '0.00'])
                    ->addColumn('is_profile_updated', 'boolean',['default' => false])
                    ->addColumn('latitude', 'decimal',['default' => false])
                    ->addColumn('longitude', 'decimal',['default' => false])
                    ->addColumn('postal_code', 'string',['limit' => 255,'null' => true])
                    ->addColumn('phone_number', 'string',['limit' => 255,'null' => true])
                    ->addColumn('secondary_phone_number', 'string',['limit' => 255,'null' => true])
                    ->addColumn('review_count_as_service_provider', 'biginteger',['limit' => 20,'default' => false])
                    ->addColumn('total_rating_as_service_provider', 'biginteger',['limit' => 20,'default' => false])
                    ->addColumn('review_count_as_employer', 'biginteger',['limit' => 20,'default' => false])
                    ->addColumn('total_rating_as_employer', 'biginteger',['limit' => 20,'default' => false])
                    ->addColumn('average_rating_as_service_provider', 'decimal',['default' => false])  
                    ->addColumn('average_rating_as_employer', 'decimal',['default' => false])  
                    ->addColumn('is_deleted', 'boolean',['default' => 0])
                    ->addColumn('account_close_reason_id', 'integer',['limit' => 20,'null' => true])
                    ->addColumn('account_close_reason', 'text',['null' => true])
                    ->addColumn('is_email_subscribed', 'boolean',['default' => true])
                    ->addColumn('is_sms_notification', 'boolean',['default' => true])
                    ->addColumn('apns_device_last_update_time', 'datetime',['null' => true])
                    ->addColumn('mobile_number_verification_otp', 'integer',['null' => true])
                    ->addColumn('is_mobile_number_verified', 'boolean',['default' => false])
                    ->addColumn('is_app_device_available', 'boolean',['default' => 0])
                    ->addColumn('mobile_code', 'string',['limit' => 255,'null' => true])
                    ->addColumn('secondary_mobile_code', 'string',['limit' => 255,'null' => true])
                    ->addColumn('is_push_notification_enabled', 'boolean',['default' => true])
                    ->addColumn('appointment_count', 'biginteger',['default' => 0])
                    ->addColumn('category_id', 'integer',['null' => true])
                    ->addColumn('display_order', 'integer',['null' => true])
                    ->addColumn('pay_key', 'string',['limit' => 255, 'null' => true])
                    ->addColumn('payment_gateway_id', 'integer',['null' => true])
                    ->addColumn('request_count', 'biginteger',['default' => 0])
                    ->addIndex('city_id')
                    ->addIndex('country_id')
                    ->addIndex('email')
                    ->addIndex('referred_by_user_id')
                    ->addIndex('category_id')
                    ->addIndex('account_close_reason_id')
                    ->addIndex('role_id')
                    ->addIndex('state_id')
                    ->addIndex('username')
                    ->addForeignKey('account_close_reason_id', 'account_close_reasons', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])                    
                    ->addForeignKey('referred_by_user_id', 'users', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])                    
                    ->addForeignKey('role_id', 'roles', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])                    
                    ->addForeignKey('category_id', 'categories', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])                    
                    ->save();
        }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $exists = $this->hasTable('users');
        if ($exists) {
            $this->dropTable('users');
        }
    }
}
