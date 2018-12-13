<?php

use \Db\Migration\Migration;

class PaypalTransactionLogs extends Migration
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
        $paypal_transaction_logs = $this->table('paypal_transaction_logs');
        $paypal_transaction_logs->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')        
              ->addColumn('amount', 'decimal',['precision' => 10, 'scale' => 2, 'default' => '0.00'])
              ->addColumn('user_id', 'integer',['limit' => 20])
              ->addColumn('payer_id', 'string',['limit' => 255, 'null' => true])
              ->addColumn('token', 'string')   
              ->addColumn('transaction_type', 'boolean',['limit' => 4])
              ->addColumn('paypal_response', 'text')
              ->addColumn('status', 'string', ['limit' => 255])
              ->addIndex('user_id')
              ->addIndex('payer_id')
              ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('paypal_transaction_logs');
    }
}
