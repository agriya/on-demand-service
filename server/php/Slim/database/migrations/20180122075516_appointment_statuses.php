<?php

use \Db\Migration\Migration;

class AppointmentStatuses extends Migration
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
        $exists = $this->hasTable('appointment_statuses');
        if (!$exists) {
            $appointment_statuses = $this->table('appointment_statuses');
            $appointment_statuses->addColumn('created_at', 'datetime')
                ->addColumn('updated_at', 'datetime')        
                ->addColumn('name', 'string',['limit' => 255, 'null' => true])
                ->addColumn('appointment_count', 'biginteger',['limit' => 20])
                ->save();
        }
    }
    /**
     * Migrate Down.
     */
    public function down()
    {
        $exists = $this->hasTable('appointment_statuses');
        if ($exists) {
            $this->dropTable('appointment_statuses');
        }
    }
}
