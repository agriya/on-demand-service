<?php

use \Db\Migration\Migration;

class InviteFriends extends Migration
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
        $exists = $this->hasTable('invite_friends');
        if (!$exists) {
            $invite_friends = $this->table('invite_friends');
            $invite_friends->addColumn('created_at', 'datetime')
                ->addColumn('updated_at', 'datetime')        
                ->addColumn('user_id', 'integer',['limit' => 20,'null' => true])
                ->addColumn('name', 'string',['limit' => 255])
                ->addColumn('email', 'string',['limit' => 255])
                ->addColumn('is_send', 'boolean', ['default' => 0])
                ->addIndex('user_id')
                ->addForeignKey('user_id', 'users', 'id', ['delete' => 'SET_NULL', 'update' => 'NO_ACTION'])
                ->save();
        }
    }
    /**
     * Migrate Down.
     */
    public function down()
    {
        $exists = $this->hasTable('invite_friends');
        if ($exists) {
            $this->dropTable('invite_friends');
        }
    }
}
