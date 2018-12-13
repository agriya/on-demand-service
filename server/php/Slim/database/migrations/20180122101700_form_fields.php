<?php

use \Db\Migration\Migration;
use Phinx\Db\Adapter\MysqlAdapter;
class FormFields extends Migration
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
        $exists = $this->hasTable('form_fields');
        if (!$exists) {
            $form_fields = $this->table('form_fields');
            $form_fields->addColumn('created_at', 'datetime')
                ->addColumn('updated_at', 'datetime')        
                ->addColumn('name', 'string',['limit' => 510,'null' => true])
                ->addColumn('label', 'string',['limit' => 510,'null' => true])
                ->addColumn('info', 'string',['limit' => 510,'null' => true])
                ->addColumn('label_for_search_form', 'string',['limit' => 510,'null' => true])
                ->addColumn('length', 'biginteger', ['null' => true])
                ->addColumn('options', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true])
                ->addColumn('class', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true])
                ->addColumn('input_type_id', 'integer', ['null' => true])
                ->addColumn('foreign_id', 'integer', ['null' => true])
                ->addColumn('form_field_group_id', 'integer', ['null' => true])
                ->addColumn('is_required', 'boolean', ['limit' => 4])
                ->addColumn('is_active', 'boolean', ['limit' => 4])
                ->addColumn('display_order', 'integer')
                ->addColumn('depends_on', 'string',['limit' => 45, 'null' => true])
                ->addColumn('depends_value', 'string',['limit' => 45, 'null' => true])
                ->addColumn('is_enable_this_field_in_search_form', 'boolean',['default' => 0])
                ->addIndex('foreign_id')
                ->addIndex('form_field_group_id')
                ->addIndex('input_type_id')
                ->addForeignKey('input_type_id','input_types','id',['delete' => 'SET_NULL','update' => 'NO_ACTION'])
                ->addForeignKey('form_field_group_id','form_field_groups','id',['delete' => 'SET_NULL','update' => 'NO_ACTION'])
                ->save();
        }
    }
    /**
     * Migrate Down.
     */
    public function down()
    {
        $exists = $this->hasTable('form_fields');
        if ($exists) {
            $this->dropTable('form_fields');
        }
    }
}
