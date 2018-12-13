<?php

use \Db\Migration\Migration;
use Phinx\Db\Adapter\MysqlAdapter;
class FormFieldSubmissions extends Migration
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
        $exists = $this->hasTable('form_field_submissions');
        if (!$exists) {
            $form_field_submissions = $this->table('form_field_submissions');
            $form_field_submissions->addColumn('created_at', 'datetime')
                ->addColumn('updated_at', 'datetime')        
                ->addColumn('form_field_id', 'integer',['limit' => 20,'null' => true])
                ->addColumn('foreign_id', 'integer',['limit' => 20,'null' => true])
                ->addColumn('class', 'text', ['limit' => MysqlAdapter::TEXT_LONG])
                ->addColumn('response', 'text', ['limit' => MysqlAdapter::TEXT_LONG,'null' => true])
                ->addColumn('is_custom_form_field', 'boolean', ['default' => 0])
                ->addIndex('foreign_id')
                ->addIndex('form_field_id')
                ->addForeignKey('form_field_id', 'form_fields', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
                ->save();
        }
    }
    /**
     * Migrate Down.
     */
    public function down()
    {
        $exists = $this->hasTable('form_field_submissions');
        if ($exists) {
            $this->dropTable('form_field_submissions');
        }
    }
}
