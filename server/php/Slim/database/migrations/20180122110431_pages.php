<?php

use \Db\Migration\Migration;

class Pages extends Migration
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
        $pages = $this->table('pages');
        $pages->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')        
              ->addColumn('language_id', 'integer',['limit' => 20, 'null' => true])        
              ->addColumn('title', 'string',['limit' => 255])
              ->addColumn('slug', 'string',['limit' => 255])
              ->addColumn('page_content', 'text')
              ->addColumn('is_active', 'boolean',['default' => true])
              ->addIndex('language_id')
              ->addForeignKey('language_id','languages','id',['delete' => 'SET_NULL', 'delete' => 'NO_ACTION'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('pages');
    }
}
