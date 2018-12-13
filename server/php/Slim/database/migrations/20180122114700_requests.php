<?php

use \Db\Migration\Migration;

class Requests extends Migration
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
        $requests = $this->table('requests');
        $requests
              ->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')        
              ->addColumn('user_id', 'integer', ['null' => true])
              ->addColumn('service_id', 'integer', ['null' => true])
              ->addColumn('request_status_id', 'integer', ['default' => true, 'comment' => '1. Open, 2. Closed'])
              ->addColumn('job_type_id', 'integer', ['default' => true, 'comment' => '1. One time Job, 2. Part Time, 3. Full Time','null' => true])
              ->addColumn('appointment_timing_type_id', 'integer', ['default' => true, 'comment' => '1. Specify times, 2. During the day, 3. During the night','null' => true])
              ->addColumn('description','text', ['null' => true])
              ->addColumn('appointment_from_date','datetime', ['null' => true])
              ->addColumn('appointment_from_time','time', ['null' => true])
              ->addColumn('appointment_to_date','datetime', ['null' => true])
              ->addColumn('appointment_to_time','time', ['null' => true])              
              ->addColumn('price_per_hour','decimal', ['null' => true])
              ->addColumn('work_location_address','string', ['null' => true])
              ->addColumn('work_location_address1','string', ['null' => true])
              ->addColumn('work_location_state_id','integer', ['null' => true])
              ->addColumn('work_location_city_id','integer', ['null' => true])
              ->addColumn('work_location_country_id','integer', ['null' => true])
              ->addColumn('work_location_postal_code','string', ['null' => true])
              ->addColumn('work_location_latitude','decimal', ['null' => true])
              ->addColumn('work_location_longitude','decimal', ['null' => true])
              ->addColumn('payment_mode_id','biginteger', ['null' => true, 'comment' => '1. Through Site, 2. Pay Cash'])
              ->addColumn('number_of_item','integer', ['null' => true])
              ->addColumn('is_sunday_needed','boolean', ['default' => 0])
              ->addColumn('sunday_appointment_from_time','time', ['null' => true])
              ->addColumn('sunday_appointment_to_time','time', ['null' => true])
              ->addColumn('is_monday_needed','boolean', ['default' => 0])
              ->addColumn('monday_appointment_from_time','time', ['null' => true])
              ->addColumn('monday_appointment_to_time','time', ['null' => true])    
              ->addColumn('is_tuesday_needed','boolean', ['default' => 0])
              ->addColumn('tuesday_appointment_from_time','time', ['null' => true])
              ->addColumn('tuesday_appointment_to_time','time', ['null' => true])    
              ->addColumn('is_wednesday_needed','boolean', ['default' => 0])
              ->addColumn('wednesday_appointment_from_time','time', ['null' => true])
              ->addColumn('wednesday_appointment_to_time','time', ['null' => true])   
              ->addColumn('is_thursday_needed','boolean', ['default' => 0])
              ->addColumn('thursday_appointment_from_time','time', ['null' => true])
              ->addColumn('thursday_appointment_to_time','time', ['null' => true])    
              ->addColumn('is_friday_needed','boolean', ['default' => 0])
              ->addColumn('friday_appointment_from_time','time', ['null' => true])
              ->addColumn('friday_appointment_to_time','time', ['null' => true])                        ->addColumn('is_saturday_needed','boolean', ['default' => 0])
              ->addColumn('saturday_appointment_from_time','time', ['null' => true])
              ->addColumn('saturday_appointment_to_time','time', ['null' => true])                      
              ->addColumn('requests_user_count','integer', ['default' => 0])                      
              ->addIndex('user_id')
              ->addIndex('service_id')
              ->addIndex('request_status_id')
              ->addIndex('appointment_timing_type_id')
              ->addIndex('work_location_country_id')
              ->addIndex('work_location_city_id')
              ->addIndex('work_location_state_id')
              ->addForeignKey('user_id','users','id',['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
              ->addForeignKey('service_id','services','id',['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('requests');
    }
}
