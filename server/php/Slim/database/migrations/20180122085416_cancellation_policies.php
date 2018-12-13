<?php

use \Db\Migration\Migration;

class CancellationPolicies extends Migration
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
        $exists = $this->hasTable('cancellation_policies');
        if (!$exists) {
            $cancellation_policies = $this->table('cancellation_policies');
            $cancellation_policies->addColumn('created_at', 'datetime')
                ->addColumn('updated_at', 'datetime')        
                ->addColumn('name', 'string',['limit' => 255, 'null' => true])
                ->addColumn('description', 'text',['null' => true])
                ->addColumn('days_before', 'biginteger',['limit' => 20, 'null' => true])
                ->addColumn('days_before_refund_percentage', 'biginteger',['limit' => 20, 'null' => true])
                ->addColumn('days_after', 'biginteger',['limit' => 20, 'null' => true])
                ->addColumn('days_after_refund_percentage', 'biginteger',['limit' => 20, 'null' => true])
                ->save();
        }
    }
    /**
     * Migrate Down.
     */
    public function down()
    {
        $exists = $this->hasTable('cancellation_policies');
        if ($exists) {
            $this->dropTable('cancellation_policies');
        }
    }
}
