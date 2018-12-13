<?php

use \Db\Migration\Migration;

class Categories extends Migration
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
        $categories = $this->table('categories');
        $categories
              ->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')        
              ->addColumn('name', 'string',['limit' => 255])
              ->addColumn('is_enable_multiple_booking', 'boolean',['default' => 0, 'signed' => false, 'null' => true])
              ->addColumn('service_count', 'biginteger',['limit' => 20, 'default' => 0, 'signed' => false, 'null' => true])   
              ->addColumn('is_active', 'boolean',['default' => 0])   
              ->addColumn('is_enable_common_hourly_rate_for_all_sub_services', 'boolean',['default' => 0])  
              ->addColumn('icon_class', 'string',['limit' => 255, 'null' => true])   
              ->addColumn('is_featured', 'boolean',['default' => 0, 'null' => true])                    
              ->addIndex('id')
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('categories');
    }
}
