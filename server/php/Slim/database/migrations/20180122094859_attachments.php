<?php

use \Db\Migration\Migration;
use Phinx\Db\Adapter\MysqlAdapter;
class Attachments extends Migration
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
        $attachments = $this->table('attachments');
        $attachments->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')        
              ->addColumn('class', 'string', ['limit' => 255])
              ->addColumn('foreign_id', 'biginteger')
              ->addColumn('filename', 'string', ['limit' => 255])
              ->addColumn('dir', 'string', ['limit' => 255])
              ->addColumn('mimetype', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('filesize', 'biginteger', ['null' => true])
              ->addColumn('height', 'biginteger', ['null' => true])
              ->addColumn('width', 'biginteger', ['null' => true])
              ->addColumn('thumb', 'boolean', ['limit' => 4,'null' => true])
              ->addColumn('description', 'text', ['limit' => MysqlAdapter::TEXT_LONG])
              ->addIndex(['foreign_id'])
              ->addIndex(['class'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('attachments');
    }
}
