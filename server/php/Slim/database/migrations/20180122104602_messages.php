<?php

use \Db\Migration\Migration;

class Messages extends Migration
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
        $messages = $this->table('messages');
        $messages->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')        
              ->addColumn('user_id', 'integer', ['null' => true])              
              ->addColumn('other_user_id', 'integer', ['null' => true])              
              ->addColumn('parent_id', 'integer', ['null' => true])              
              ->addColumn('message_content_id', 'integer', ['null' => true])              
              ->addColumn('foreign_id', 'integer', ['null' => true])              
              ->addColumn('class', 'string', ['limit' => 255, 'null' => true])              
              ->addColumn('root', 'string', ['limit' => 255, 'null' => true])              
              ->addColumn('freshness_ts', 'string', ['limit' => 255, 'null' => true])              
              ->addColumn('depth', 'biginteger', ['limit' => 20])              
              ->addColumn('materialized_path', 'string', ['limit' => 255, 'null' => true])              
              ->addColumn('path', 'string', ['limit' => 255, 'null' => true])              
              ->addColumn('size', 'integer', ['limit' => 20, 'null' => true])              
              ->addColumn('is_sender', 'boolean', ['limit' => 4])              
              ->addColumn('is_read', 'boolean', ['limit' => 4, 'null' => true])              
              ->addColumn('is_deleted', 'boolean', ['limit' => 4, 'null' => true])              
              ->addColumn('is_private', 'boolean', ['limit' => 4, 'null' => true])              
              ->addColumn('is_child_replied', 'boolean', ['limit' => 4, 'null' => true])              
              ->addColumn('model_id', 'biginteger', ['default' => 0])
              ->addIndex('foreign_id')             
              ->addIndex('message_content_id')             
              ->addIndex('other_user_id')             
              ->addIndex('parent_id')             
              ->addIndex('user_id') 
              ->addForeignKey('user_id','users','id',['delete' => 'CASCADE', 'update' => 'NO_ACTION'])  
              ->addForeignKey('other_user_id','users','id',['delete' => 'CASCADE', 'update' => 'NO_ACTION'])  
              ->addForeignKey('message_content_id','users','id',['delete' => 'SET_NULL', 'update' => 'NO_ACTION'])  
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('messages');
    }
}
