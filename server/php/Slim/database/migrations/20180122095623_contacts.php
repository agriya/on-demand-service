<?php

use \Db\Migration\Migration;

class Contacts extends Migration
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
        $contacts = $this->table('contacts');
        $contacts->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')        
              ->addColumn('user_id', 'integer',['limit' => 20, 'null' => true])        
              ->addColumn('first_name', 'string',['limit' => 255, 'null' => true])
              ->addColumn('last_name', 'string',['limit' => 255, 'null' => true])
              ->addColumn('email', 'string',['limit' => 255, 'null' => true])
              ->addColumn('subject', 'string',['limit' => 255, 'null' => true])
              ->addColumn('message', 'string',['limit' => 50, 'null' => true])
              ->addColumn('telephone', 'biginteger',['limit' => 20, 'null' => true])
              ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('contacts');
    }
}
