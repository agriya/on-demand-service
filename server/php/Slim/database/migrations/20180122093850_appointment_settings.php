<?php

use \Db\Migration\Migration;

class AppointmentSettings extends Migration
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
        $exists = $this->hasTable('appointment_settings');
        if (!$exists) {
            $appointment_settings = $this->table('appointment_settings');
            $appointment_settings->addColumn('created_at', 'datetime')
                ->addColumn('updated_at', 'datetime')        
                ->addColumn('user_id', 'integer',['limit' => 20, 'null' => true])        
                ->addColumn('is_today_first_day', 'boolean',['limit' => 4, 'default' => 0])        
                ->addColumn('calendar_slot_id', 'integer',['limit' => 10, 'null' => true])        
                ->addColumn('practice_open', 'time',['null' => true])        
                ->addColumn('practice_close', 'time',['null' => true])        
                ->addColumn('type', 'boolean',['limit' => 4,'default' => 0, 'comment' => '0: Same for All, 1: Individual Days'])   
                ->addColumn('is_sunday_open', 'boolean',['limit' => 4, 'default' => 1])
                ->addColumn('sunday_practice_open', 'time',['null' => true])
                ->addColumn('sunday_practice_close', 'time',['null' => true])
                ->addColumn('is_monday_open', 'boolean',['limit' => 4, 'default' => 1])  
                ->addColumn('monday_practice_open', 'time',['null' => true])
                ->addColumn('monday_practice_close', 'time',['null' => true])   
                ->addColumn('is_tuesday_open', 'boolean',['limit' => 4, 'default' => 1])  
                ->addColumn('tuesday_practice_open', 'time',['null' => true])
                ->addColumn('tuesday_practice_close', 'time',['null' => true])  
                ->addColumn('is_wednesday_open', 'boolean',['limit' => 4, 'default' => 1])  
                ->addColumn('wednesday_practice_open', 'time',['null' => true])
                ->addColumn('wednesday_practice_close', 'time',['null' => true])     
                ->addColumn('is_thursday_open', 'boolean',['limit' => 4, 'default' => 1])  
                ->addColumn('thursday_practice_open', 'time',['null' => true])
                ->addColumn('thursday_practice_close', 'time',['null' => true])  
                ->addColumn('is_friday_open', 'boolean',['limit' => 4, 'default' => 1])  
                ->addColumn('friday_practice_open', 'time',['null' => true])
                ->addColumn('friday_practice_close', 'time',['null' => true])      
                ->addColumn('is_saturday_open', 'boolean',['limit' => 4, 'default' => 1])  
                ->addColumn('saturday_practice_open', 'time',['null' => true])
                ->addColumn('saturday_practice_close', 'time',['null' => true]) 
                ->addIndex('user_id')
                ->addIndex('calendar_slot_id')
                ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])                  
                ->save();
        }
    }
    /**
     * Migrate Down.
     */
    public function down()
    {
        $exists = $this->hasTable('appointment_settings');
        if ($exists) {
            $this->dropTable('appointment_settings');
        }
    }
}
