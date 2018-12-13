<?php

use \Db\Migration\Migration;

class UserLogins extends Migration
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
        $user_logins = $this->table('user_logins');
        $user_logins
              ->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')        
              ->addColumn('user_id', 'integer',['null' => true])
              ->addColumn('role_id', 'integer',['null' => true])
              ->addColumn('user_agent', 'string',['null' => true])
              ->addIndex('user_id')
              ->addIndex('role_id')
              ->addForeignKey('user_id','users','id',['delete' => 'CASCADE','update' => 'NO_ACTION'])
              ->addForeignKey('role_id','roles','id',['delete' => 'CASCADE','update' => 'NO_ACTION'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('user_logins');
    }
}
