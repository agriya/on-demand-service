<?php

use \Db\Migration\Migration;

class UserCashWithdrawals extends Migration
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
        $user_cash_withdrawals = $this->table('user_cash_withdrawals');
        $user_cash_withdrawals
              ->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')        
              ->addColumn('user_id', 'integer',['null' => true])
              ->addColumn('withdrawal_status_id', 'integer',['default' => 1])
              ->addColumn('amount', 'decimal',['precision' => 10, 'scale' => 2,'default' => '0.00'])
              ->addColumn('money_transfer_account_id', 'integer',['null' => true])
              ->addColumn('remark', 'text',['null' => true])
              ->addColumn('withdrawal_fee', 'decimal',['precision' => 10, 'scale' => 2,'default' => '0.00'])
              ->addIndex('user_id')
              ->addIndex('money_transfer_account_id')
              ->addIndex('withdrawal_status_id')
              ->addForeignKey('user_id','users','id',['delete' => 'CASCADE','update' => 'NO_ACTION'])
              ->addForeignKey('money_transfer_account_id','money_transfer_accounts','id',['delete' => 'SET_NULL','update' => 'NO_ACTION'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('user_cash_withdrawals');
    }
}
