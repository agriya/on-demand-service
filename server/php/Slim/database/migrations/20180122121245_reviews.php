<?php

use \Db\Migration\Migration;
use Phinx\Db\Adapter\MysqlAdapter;
class Reviews extends Migration
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
        $reviews = $this->table('reviews');
        $reviews
              ->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')        
              ->addColumn('user_id', 'integer', ['null' => true])
              ->addColumn('to_user_id', 'integer', ['null' => true])
              ->addColumn('foreign_id', 'integer', ['null' => true])
              ->addColumn('class', 'string')
              ->addColumn('rating', 'integer',['default' => 0])
              ->addColumn('message', 'text', ['limit' => MysqlAdapter::TEXT_LONG])              
              ->addColumn('is_reviewed_as_service_provider', 'boolean', ['limit' => 4, 'null' => true]) 
              ->addColumn('model_id', 'integer', ['null' => true]) 
              ->addColumn('model_class', 'string', ['null' => true]) 
              ->addIndex('foreign_id')
              ->addIndex('model_id')
              ->addIndex('to_user_id')
              ->addIndex('user_id')
              ->addForeignKey('user_id','users','id',['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
              ->addForeignKey('to_user_id','users','id',['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('reviews');
    }
}
