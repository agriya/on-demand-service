<?php

use \Db\Migration\Migration;

class ServicesUsers extends Migration
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
        $services_users = $this->table('services_users');
        $services_users
              ->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')        
              ->addColumn('service_id', 'integer', ['null' => true])
              ->addColumn('user_id', 'integer', ['null' => true])
              ->addColumn('rate', 'decimal', ['null' => true])
              ->addColumn('cancellation_policy_id', 'integer', ['null' => true])
              ->addIndex('service_id')
              ->addIndex('user_id')
              ->addIndex('cancellation_policy_id')
              ->addForeignKey('service_id','services','id',['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
              ->addForeignKey('user_id','users','id',['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
              ->addForeignKey('cancellation_policy_id','cancellation_policies','id',['delete' => 'SET_NULL', 'update' => 'NO_ACTION'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('services_users');
    }
}
