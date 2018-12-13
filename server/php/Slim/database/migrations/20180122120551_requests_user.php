<?php

use \Db\Migration\Migration;

class RequestsUser extends Migration
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
        $requests_users = $this->table('requests_users');
        $requests_users
              ->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')        
              ->addColumn('request_id', 'integer', ['null' => true])
              ->addColumn('user_id', 'integer', ['null' => true])
              ->addIndex('request_id')
              ->addIndex('user_id')
              ->addForeignKey('request_id','requests','id',['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
              ->addForeignKey('user_id','users','id',['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('requests_users');
    }
}
