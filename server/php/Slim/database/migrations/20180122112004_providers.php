<?php

use \Db\Migration\Migration;

class Providers extends Migration
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
        $providers = $this->table('providers');
        $providers->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')        
              ->addColumn('name', 'string', ['limit' => 255])
              ->addColumn('slug','string', ['limit' => 255])
              ->addColumn('secret_key', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('api_key', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('display_order', 'biginteger', ['default' => 0])
              ->addColumn('is_active', 'boolean', ['default' => true])
              ->addIndex('slug')
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('providers');
    }
}
