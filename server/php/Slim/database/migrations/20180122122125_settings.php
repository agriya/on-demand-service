<?php

use \Db\Migration\Migration;

class Settings extends Migration
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
        $settings = $this->table('settings');
        $settings
              ->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')        
              ->addColumn('setting_category_id', 'integer',['null' => true])
              ->addColumn('name', 'string',['limit' => 255, 'null' => true])
              ->addColumn('value', 'text',['null' => true])
              ->addColumn('label', 'string',['limit' => 255, 'null' => true])
              ->addColumn('description', 'text',['null' => true])
              ->addColumn('is_active', 'boolean',['default' => true])
              ->addColumn('display_order', 'integer',['null' => true])
              ->addColumn('plugin', 'string',['limit' => 255, 'null' => true])
              ->addColumn('is_front_end_access', 'boolean',['default' => false])
              ->addIndex('setting_category_id')
              ->addForeignKey('setting_category_id','setting_categories','id',['delete' => 'NO_ACTION','update' => 'NO_ACTION'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('settings');
    }
}
