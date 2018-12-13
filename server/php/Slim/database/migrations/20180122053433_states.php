<?php

use \Db\Migration\Migration;

class States extends Migration
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
        $states = $this->table('states');
        $states
              ->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')        
              ->addColumn('name', 'string',['limit' => 45, 'null' => true])
              ->addColumn('country_id', 'integer',['limit' => 20, 'null' => true])              
              ->addColumn('is_active', 'boolean',['default' => 0])
              ->addIndex('country_id')
              ->addForeignKey('country_id', 'countries', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('states');
    }
}
