<?php

use \Db\Migration\Migration;
use Phinx\Db\Adapter\MysqlAdapter;
class FormFieldGroups extends Migration
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
        $exists = $this->hasTable('form_field_groups');
        if (!$exists) {
            $form_field_groups = $this->table('form_field_groups');
            $form_field_groups->addColumn('created_at', 'datetime')
                ->addColumn('updated_at', 'datetime')        
                ->addColumn('name', 'string',['limit' => 255,'null' => true])
                ->addColumn('slug', 'string',['limit' => 255,'null' => true])
                ->addColumn('foreign_id', 'biginteger',['limit' => 20,'null' => true])
                ->addColumn('info', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true])
                ->addColumn('order', 'biginteger', ['limit' => 20,'null' => true])
                ->addColumn('class', 'string', ['limit' => 255,'null' => true])
                ->addColumn('is_deletable', 'boolean', ['limit' => 4, 'default' => 1,'null' => true])
                ->addColumn('is_editable', 'boolean', ['limit' => 4, 'default' => 1,'null' => true])
                ->addColumn('is_belongs_to_service_provider', 'boolean', ['limit' => 4, 'default' => 1,'null' => true])
                ->addIndex('class')
                ->addIndex('foreign_id')
                ->addIndex('name')
                ->addIndex('slug')
                ->save();
        }
    }
    /**
     * Migrate Down.
     */
    public function down()
    {
        $exists = $this->hasTable('form_field_groups');
        if ($exists) {
            $this->dropTable('form_field_groups');
        }
    }
}
