<?php

use \Db\Migration\Migration;

class UserProfiles extends Migration
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
        $user_profiles = $this->table('user_profiles');
        $user_profiles
              ->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')        
              ->addColumn('user_id', 'integer',['null' => true])
              ->addColumn('first_name', 'string',['null' => true])
              ->addColumn('last_name', 'string',['null' => true])
              ->addColumn('language_id', 'text',['null' => true])
              ->addColumn('gender_id', 'integer',['null' => true])
              ->addColumn('dob', 'date',['null' => true])
              ->addColumn('phone', 'string',['limit' => 15,'null' => true])
              ->addColumn('work_phone_number', 'string',['limit' => 20,'null' => true])
              ->addColumn('about_me', 'text',['null' => true])
              ->addColumn('listing_address', 'text',['null' => true])
              ->addColumn('listing_address1', 'text',['null' => true])
              ->addColumn('listing_city_id', 'integer',['limit' => 20,'null' => true])
              ->addColumn('listing_state_id', 'integer',['limit' => 20,'null' => true])
              ->addColumn('listing_country_id', 'integer',['limit' => 20,'null' => true])
              ->addColumn('listing_postal_code', 'string',['limit' => 10,'null' => true])
              ->addColumn('notification_type_id', 'integer',['null' => true])
              ->addColumn('is_listing_updated', 'boolean',['default' => false])
              ->addColumn('is_online_assessment_test_completed', 'boolean',['default' => false])
              ->addColumn('awards', 'text',['null' => true])
              ->addColumn('listing_latitude', 'decimal',['null' => true])
              ->addColumn('listing_longitude', 'decimal',['null' => true])
              ->addColumn('listing_title', 'string',['null' => true])
              ->addColumn('listing_description', 'text',['null' => true])
              ->addColumn('listing_status_id', 'integer',['default' => 1])
              ->addColumn('is_service_profile_updated', 'boolean',['default' => 0])
              ->addColumn('photo_count', 'integer',['default' => 0])
              ->addColumn('services_user_count', 'integer',['default' => 0])
              ->addColumn('is_listing_address_verified', 'boolean',['default' => 0])
              ->addColumn('repeat_client_count', 'decimal',['precision' => 10, 'scale' => 0, 'default' => 0])
              ->addColumn('completed_appointment_count', 'biginteger',['limit' => 20])
              ->addColumn('response_rate', 'biginteger',['limit' => 20, 'null' => true, 'comment' => 'In Percentage'])
              ->addColumn('response_time', 'biginteger',['limit' => 20, 'null' => true, 'comment' => 'In minutes'])
              ->addColumn('listing_appointment_count', 'biginteger',['limit' => 20, 'default' => 0])
              ->addColumn('site_revenue_as_service_provider', 'decimal',['precision' => 10, 'scale' => 2, 'default' => '0.00'])
              ->addColumn('earned_amount_as_service_provider', 'decimal',['precision' => 10, 'scale' => 2, 'default' => '0.00'])
              ->addColumn('total_spent_amount_as_customer', 'decimal',['precision' => 10, 'scale' => 2, 'default' => '0.00'])
              ->addColumn('user_view_count', 'biginteger',['default' => 0])
              ->addColumn('user_favorite_count', 'biginteger',['default' => 0])
              ->addColumn('site_revenue_as_customer', 'decimal',['precision' => 10, 'scale' => 2, 'default' => '0.00'])
              ->addColumn('pro_account_status_id', 'biginteger',['default' => 1, 'comment' => '1. Not Paid, 2. Paid and Pending Approval, 3. Approved'])
              ->addColumn('paid_pro_amount', 'decimal',['precision' => 10, 'scale' => 2, 'default' => '0.00'])
              ->addColumn('is_top_listed', 'boolean',['default' => 0])
              ->addColumn('top_user_expiry', 'datetime',['null' => true])
              ->addIndex('user_id')
              ->addIndex('gender_id')
              ->addIndex('listing_status_id')
              ->addForeignKey('user_id','users','id',['delete' => 'CASCADE','update' => 'NO_ACTION'])
              ->addForeignKey('gender_id','genders','id',['delete' => 'SET_NULL ','update' => 'NO_ACTION'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('user_profiles');
    }
}
