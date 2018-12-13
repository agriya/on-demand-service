<?php

use \Db\Migration\Migration;
use Phinx\Db\Adapter\MysqlAdapter;
class MessageContents extends Migration
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
        $message_contents = $this->table('message_contents');
        $message_contents->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')        
              ->addColumn('subject', 'text', ['limit' => MysqlAdapter::TEXT_LONG])              
              ->addColumn('message', 'text', ['limit' => MysqlAdapter::TEXT_LONG])              
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('message_contents');
    }
}
