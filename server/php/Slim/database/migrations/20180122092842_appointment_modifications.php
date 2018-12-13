<?php

use \Db\Migration\Migration;

class AppointmentModifications extends Migration
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
        $exists = $this->hasTable('appointment_modifications');
        if (!$exists) {
            $appointment_modifications = $this->table('appointment_modifications');
            $appointment_modifications->addColumn('created_at', 'datetime')
                ->addColumn('updated_at', 'datetime')        
                ->addColumn('user_id', 'integer',['limit' => 20, 'null' => true])        
                ->addColumn('type', 'boolean',['limit' => 2, 'default' => 0, 'comment' => '0-Unavailable in Particular Date And Time; 1 - Make a Day Fully Off; 2 - Unavailable In Every Particular Day And Time Recursively; 3 - Make a Day Fully On;'])        
                ->addColumn('unavailable_date', 'date',['null' => true])   
                ->addColumn('unavailable_from_time', 'time',['null' => true])
                ->addColumn('unavailable_to_time', 'time',['null' => true])
                ->addColumn('day', 'string',['null' => true, 'comment' => 'AllDay, Sunday, Monday, Tuesday, Wednesday, Thursday, Friday, Saturday'])
                ->addIndex('user_id')
                ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])                  
                ->save();
        }
    }
    /**
     * Migrate Down.
     */
    public function down()
    {
        $exists = $this->hasTable('appointment_modifications');
        if ($exists) {
            $this->dropTable('appointment_modifications');
        }
    }
}
