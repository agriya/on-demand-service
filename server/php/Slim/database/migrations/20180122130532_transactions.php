<?php

use \Db\Migration\Migration;

class Transactions extends Migration
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
        $transactions = $this->table('transactions');
        $transactions
              ->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')        
              ->addColumn('user_id', 'integer',['null' => true])
              ->addColumn('to_user_id', 'integer',['null' => true])
              ->addColumn('foreign_id', 'integer',['null' => true])
              ->addColumn('class', 'string',['null' => true])
              ->addColumn('transaction_type', 'string',['null' => true])
              ->addColumn('payment_gateway_id', 'integer',['null' => true])
              ->addColumn('amount', 'decimal',['null' => true])
              ->addColumn('site_revenue_from_freelancer', 'decimal',['null' => true])
              ->addColumn('coupon_id', 'integer',['null' => true])
              ->addColumn('site_revenue_from_employer', 'decimal',['default' => 0])
              ->addIndex('foreign_id')
              ->addIndex('payment_gateway_id')
              ->addIndex('to_user_id')
              ->addIndex('user_id')
              ->addForeignKey('user_id','users','id',['delete' => 'CASCADE','update' => 'NO_ACTION'])
              ->addForeignKey('to_user_id','users','id',['delete' => 'CASCADE','update' => 'NO_ACTION'])
              ->addForeignKey('payment_gateway_id','payment_gateways','id',['delete' => 'SET_NULL','update' => 'NO_ACTION'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('transactions');
    }
}
