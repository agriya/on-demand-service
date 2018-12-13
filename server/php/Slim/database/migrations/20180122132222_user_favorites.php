<?php

use \Db\Migration\Migration;

class UserFavorites extends Migration
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
        $user_favorites = $this->table('user_favorites');
        $user_favorites
              ->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')        
              ->addColumn('user_id', 'integer',['null' => true])
              ->addColumn('provider_user_id', 'integer',['null' => true])
              ->addIndex('user_id')
              ->addIndex('provider_user_id')
              ->addForeignKey('user_id','users','id',['delete' => 'CASCADE','update' => 'NO_ACTION'])
              ->addForeignKey('provider_user_id','users','id',['delete' => 'CASCADE','update' => 'NO_ACTION'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('user_favorites');
    }
}
