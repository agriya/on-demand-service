<?php

use \Db\Migration\Migration;

class UserViews extends Migration
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
        $exists = $this->hasTable('user_views');
        if (!$exists) {
            $user_views = $this->table('user_views');
            $user_views
                    ->addColumn('created_at', 'datetime')
                    ->addColumn('updated_at', 'datetime')        
                    ->addColumn('user_id', 'integer',['null' => true])
                    ->addColumn('viewing_user_id', 'integer',['null' => true])
                    ->addIndex('user_id')
                    ->addIndex('viewing_user_id')
                    ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
                    ->addForeignKey('viewing_user_id', 'users', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
                    ->save();
        }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $exists = $this->hasTable('user_views');
        if ($exists) {
            $this->dropTable('user_views');
        }
    }
}
